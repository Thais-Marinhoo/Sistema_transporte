<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

function Haversine($lat1, $lon1, $lat2, $lon2) {
    $raioTerra = 6371; // Raio da Terra em Quilômetros

    // Converte as coordenadas de graus para radianos
    $vLat = deg2rad($lat2 - $lat1);
    $vLon = deg2rad($lon2 - $lon1);

    // Aplicação matemática da fórmula de Haversine
    $a = sin($vLat / 2) * sin($vLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($vLon / 2) * sin($vLon / 2);
         
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    $distancia = $raioTerra * $c; // Retorna o valor em quilômetros
    return $distancia; 
}

$sqlVerificaPontos = "SELECT COUNT(*) as total FROM ponto";
$resPontos = mysqli_query($conexao, $sqlVerificaPontos);
$dadosPontos = mysqli_fetch_assoc($resPontos);

if (!$dadosPontos || $dadosPontos['total'] == 0) {
    header("Location: tela.cadastro.php?status=sem_pontos");
    exit();
}

if(!isset($_POST['nome'])){
    die("Nenhum dado recebido!");
}

$nomes = $_POST['nome'];
$series = $_POST['serie'];
$cursos = $_POST['curso'];
$endereco = $_POST['endereco'];

// Inicializa o contador para saber se alguém foi salvo de verdade
$alunosSalvos = 0;

for($i = 0; $i < count($nomes); $i++){

    $nome = trim($nomes[$i]);
    $serie = trim($series[$i]);
    $curso = trim($cursos[$i]);
    $enderecos = trim($endereco[$i]);

    // PROTEÇÃO EXTRA: Se por acaso chegar uma linha fantasma vazia, ignora e pula ela
    if (empty($nome) && empty($enderecos)) {
        continue;
    }

    // Prepara o endereço garantindo que a busca foque em Crateús
    $endereco_filtrado = $enderecos . ", Crateús, Ceará, Brasil";
    $url_api = "https://nominatim.openstreetmap.org/search?q=" . urlencode($endereco_filtrado) . "&format=json&limit=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "SistemaTransporteCrateus/1.0 (heitor.almeida2@aluno.ce.gov.br)");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $resposta_texto = curl_exec($ch);
    curl_close($ch);

    $resultado_dados = json_decode($resposta_texto, true);

    // Se a API falhar ou não achar o endereço no PHP, pula a linha para não salvar dados nulos
    if (empty($resultado_dados) || !isset($resultado_dados[0]['lat'])) {
        continue; 
    }

    $latitude = (float) $resultado_dados[0]['lat'];
    $longitude = (float) $resultado_dados[0]['lon'];

    // ESCAPA DADOS
    $nomeEsc = mysqli_real_escape_string($conexao, $nome);

    // VERIFICA SE O ALUNO JÁ EXISTE
    $sqlVerifica = "SELECT id_aluno FROM aluno WHERE nome = '$nomeEsc'";
    $resultado = mysqli_query($conexao, $sqlVerifica);

    if($resultado && mysqli_num_rows($resultado) > 0){
        continue;
    }

    $idPontoMaisProximo = "NULL"; 
    $menorDistancia = 999999;     

    // Busca todos os pontos salvos na tabela "ponto"
    $sqlPontos = "SELECT id_ponto, latitude, longitude FROM ponto";
    $resultadoPontos = mysqli_query($conexao, $sqlPontos);

    if ($resultadoPontos && mysqli_num_rows($resultadoPontos) > 0) {
        while ($ponto = mysqli_fetch_assoc($resultadoPontos)) {
            $distancia = Haversine($latitude, $longitude, $ponto['latitude'], $ponto['longitude']);
            if ($distancia < $menorDistancia) {
                $menorDistancia = $distancia;
                $idPontoMaisProximo = $ponto['id_ponto']; 
            }
        }
    }

    // INSERT
    $sql = "
        INSERT INTO aluno (nome, endereco, serie, curso, latitude, longitude, id_ponto)
        VALUES (
            '".mysqli_real_escape_string($conexao, $nome)."',
            '".mysqli_real_escape_string($conexao, $enderecos)."',
            '".mysqli_real_escape_string($conexao, $serie)."',
            '".mysqli_real_escape_string($conexao, $curso)."',
            '".$latitude."',
            '".$longitude."',
            ".$idPontoMaisProximo."
        )
    ";

    $resultadoInsert = mysqli_query($conexao, $sql);

    if(!$resultadoInsert){
        die("Erro no banco: " . mysqli_error($conexao));
    } else {
        $alunosSalvos++; 
    }
}

// REDIRECIONA APENAS SE HOUVER ALUNOS SALVOS
if ($alunosSalvos > 0) {
    header("Location: lista.alunos.php?status=sucesso_aluno");
    exit();
} else {
    // Se o JS falhou e nenhum aluno pôde ser salvo, interrompemos 
    // com uma mensagem simples para não redirecionar e NÃO apagar a tela!
    die("Nenhum aluno válido pôde ser cadastrado. Por favor, volte e verifique os dados.");
}
?>