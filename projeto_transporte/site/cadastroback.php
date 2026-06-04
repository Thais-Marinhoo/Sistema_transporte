<?php

session_start();

// DEBUG — APAGA DEPOIS
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
    
    $distancia = $raioTerra * $c; // Retorna o valor em quilômetros (ex: 0.350 = 350 metros)
    return $distancia; 
}

$sqlVerificaPontos = "SELECT COUNT(*) as total FROM ponto";
$resPontos = mysqli_query($conexao, $sqlVerificaPontos);
$dadosPontos = mysqli_fetch_assoc($resPontos);

if (!$dadosPontos || $dadosPontos['total'] == 0) {
    // Redireciona o usuário de volta pois a vinculação é obrigatória
    header("Location: tela.cadastro.php?status=sem_pontos");
    exit(); // Interrompe a execução completa do script aqui
}


if(!isset($_POST['nome'])){
    die("Nenhum dado recebido!");

}



$nomes = $_POST['nome'];
$series = $_POST['serie'];
$cursos = $_POST['curso'];
$endereco = $_POST['endereco'];






for($i = 0; $i < count($nomes); $i++){

    $nome = trim($nomes[$i]);
    $serie = trim($series[$i]);
    $curso = trim($cursos[$i]);
    $enderecos = trim($endereco[$i]);



    // SE HOUVER CAMPOS VAZIOS, BLOQUEIA E REDIRECIONA
    if(
        $nome == "" ||
        $serie == "" ||
        $curso == "" ||
        $enderecos == ""
    ){
        // Envia de volta para a tela de cadastro avisando que faltam dados
        header("Location: tela.cadastro.php?status=falta_info");
        exit(); // Interrompe completamente o script aqui
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////    
// ... (Seu código existente recebe as variáveis do formulário, ex: $endereco = $_POST['endereco'];)

// 1. Prepara o endereço garantindo que a busca foque em Crateús
$endereco_filtrado = $enderecos . ", Crateús, Ceará, Brasil";

// ... seu código de preparar o endereço filtrado ...

$url_api = "https://nominatim.openstreetmap.org/search?q=" . urlencode($endereco_filtrado) . "&format=json&limit=1";

// Nova forma de disparar usando cURL (ignora travas do file_get_contents no Windows)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "SistemaTransporteCrateus/1.0 (heitor.almeida2@aluno.ce.gov.br)");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora erros de certificado SSL locais do XAMPP

$resposta_texto = curl_exec($ch);
curl_close($ch);

$resultado_dados = json_decode($resposta_texto, true);

// Verifica se a API encontrou o local com sucesso
if (!empty($resultado_dados) && isset($resultado_dados[0]['lat'])) {
    
    // Sucesso! Aqui estão as duas variáveis numéricas prontinhas que você precisava
    $latitude = (float) $resultado_dados[0]['lat'];
    $longitude = (float) $resultado_dados[0]['lon'];
    
    // ... (A partir daqui você coloca o seu código de INSERT no banco de dados)
    // Exemplo: usar $latitude e $longitude na sua query do banco.

} else {
    // Caso o endereço digitado seja inválido ou inexistente em Crateús
    header("Location: tela.cadastro.php?status=endereco");
    exit; // Interrompe para não salvar dados vazios no banco
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // ESCAPA DADOS
    $nomeEsc = mysqli_real_escape_string($conexao, $nome);

    // VERIFICA SE O ALUNO JÁ EXISTE
    $sqlVerifica = "
        SELECT id_aluno
        FROM aluno
        WHERE nome = '$nomeEsc'
    ";

    $resultado = mysqli_query($conexao, $sqlVerifica);

    if($resultado && mysqli_num_rows($resultado) > 0){
        continue;
    }

    $idPontoMaisProximo = "NULL"; // Valor padrão caso não existam pontos cadastrados
    $menorDistancia = 999999;     // Começa com um valor absurdamente alto

    // Busca todos os pontos salvos na tabela "ponto"
    $sqlPontos = "SELECT id_ponto, latitude, longitude FROM ponto";
    $resultadoPontos = mysqli_query($conexao, $sqlPontos);

    if ($resultadoPontos && mysqli_num_rows($resultadoPontos) > 0) {
        while ($ponto = mysqli_fetch_assoc($resultadoPontos)) {
            
            // Calcula a distância do aluno atual para este ponto específico do loop
            $distancia = Haversine($latitude, $longitude, $ponto['latitude'], $ponto['longitude']);

            // Se a distância calculada for menor que a menor registrada até agora, atualiza
            if ($distancia < $menorDistancia) {
                $menorDistancia = $distancia;
                $idPontoMaisProximo = $ponto['id_ponto']; // Armazena o ID do ponto vencedor
            }
        }
    }
    echo "PASSOU 3<br>";
    // INSERT
    $sql = "
        INSERT INTO aluno
        (
            nome,
            endereco,
            serie,
            curso,
            latitude,
            longitude,
            id_ponto
        )

        VALUES
        (
            '".mysqli_real_escape_string($conexao, $nome)."',

            '".mysqli_real_escape_string($conexao, $enderecos)."',

            '".mysqli_real_escape_string($conexao, $serie)."',

            '".mysqli_real_escape_string($conexao, $curso)."',

            '".mysqli_real_escape_string($conexao, $latitude)."',

            '".mysqli_real_escape_string($conexao, $longitude)."',
            
            '".mysqli_real_escape_string($conexao, $idPontoMaisProximo)."'

        )
    ";
echo "PASSOU 4<br>";
    $resultadoInsert = mysqli_query($conexao, $sql);

    // MOSTRA ERRO SE DER PROBLEMA
    if(!$resultadoInsert){

        die("Erro no banco: " . mysqli_error($conexao));

    }

}

// REDIRECIONA
header("Location: lista.alunos.php?status=sucesso_aluno");
exit();

?>
