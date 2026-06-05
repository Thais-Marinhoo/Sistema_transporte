<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

// =====================================================================
// Fórmula de Haversine — calcula distância em km entre dois pontos GPS
// =====================================================================
function Haversine($lat1, $lon1, $lat2, $lon2) {
    $raioTerra = 6371;
    $vLat = deg2rad($lat2 - $lat1);
    $vLon = deg2rad($lon2 - $lon1);
    $a = sin($vLat / 2) * sin($vLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($vLon / 2) * sin($vLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $raioTerra * $c;
}

// =====================================================================
// Verifica se existem pontos cadastrados (obrigatório para vincular aluno)
// =====================================================================
$sqlVerificaPontos = "SELECT COUNT(*) as total FROM ponto";
$resPontos         = mysqli_query($conexao, $sqlVerificaPontos);
$dadosPontos       = mysqli_fetch_assoc($resPontos);

if (!$dadosPontos || $dadosPontos['total'] == 0) {
    header("Location: tela.cadastro.php?status=sem_pontos");
    exit();
}

if(!isset($_POST['nome'])){
    die("Nenhum dado recebido!");
}

$nomes    = $_POST['nome'];
$series   = $_POST['serie'];
$cursos   = $_POST['curso'];
$endereco = $_POST['endereco'];

$API_KEY = "172ff5e777874a13b995e244562a96a5";

// =====================================================================
// Loop — processa cada aluno enviado no formulário
// =====================================================================
for($i = 0; $i < count($nomes); $i++){

    $nome      = trim($nomes[$i]);
    $serie     = trim($series[$i]);
    $curso     = trim($cursos[$i]);
    $enderecos = trim($endereco[$i]);

    // Bloqueia se algum campo estiver vazio
    if($nome == "" || $serie == "" || $curso == "" || $enderecos == ""){
        header("Location: tela.cadastro.php?status=falta_info");
        exit();
    }

    // ---------------------------------------------------------------
    // Geocodificação via Geoapify
    // bias=proximity força a busca em torno do centro de Crateús-CE
    // ---------------------------------------------------------------
    $endereco_busca = $enderecos . ", Crateús, Ceará, Brasil";
    $url_api = "https://api.geoapify.com/v1/geocode/search"
             . "?text="  . urlencode($endereco_busca)
             . "&bias=proximity:-40.6617,-4.9782"
             . "&limit=1"
             . "&apiKey=" . $API_KEY;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resposta_texto = curl_exec($ch);
    curl_close($ch);

    $resultado_dados = json_decode($resposta_texto, true);

    $latitude  = null;
    $longitude = null;

    // GeoJSON retorna [longitude, latitude] — ordem invertida!
    // confidence >= 0.4 garante que a API realmente achou o endereço (não apenas chutou)
    if (!empty($resultado_dados) && isset($resultado_dados['features'][0]['geometry']['coordinates'])) {
        $confidence = (float) ($resultado_dados['features'][0]['properties']['rank']['confidence'] ?? 0);
        if ($confidence >= 0.4) {
            $longitude = (float) $resultado_dados['features'][0]['geometry']['coordinates'][0];
            $latitude  = (float) $resultado_dados['features'][0]['geometry']['coordinates'][1];
        }
    }

    if ($latitude === null || $longitude === null) {
        header("Location: tela.cadastro.php?status=endereco");
        exit();
    }

    // ---------------------------------------------------------------
    // Verifica se o aluno já está cadastrado pelo nome
    // ---------------------------------------------------------------
    $nomeEsc     = mysqli_real_escape_string($conexao, $nome);
    $sqlVerifica = "SELECT id_aluno FROM aluno WHERE nome = '$nomeEsc'";
    $resultado   = mysqli_query($conexao, $sqlVerifica);

    if($resultado && mysqli_num_rows($resultado) > 0){
        continue; // Pula duplicatas sem parar o loop
    }

    // ---------------------------------------------------------------
    // Encontra o ponto de embarque mais próximo usando Haversine
    // ---------------------------------------------------------------
    $idPontoMaisProximo = "NULL";
    $menorDistancia     = 999999;

    $sqlPontos       = "SELECT id_ponto, latitude, longitude FROM ponto";
    $resultadoPontos = mysqli_query($conexao, $sqlPontos);

    if ($resultadoPontos && mysqli_num_rows($resultadoPontos) > 0) {
        while ($ponto = mysqli_fetch_assoc($resultadoPontos)) {
            $distancia = Haversine($latitude, $longitude, $ponto['latitude'], $ponto['longitude']);
            if ($distancia < $menorDistancia) {
                $menorDistancia     = $distancia;
                $idPontoMaisProximo = $ponto['id_ponto'];
            }
        }
    }

    // ---------------------------------------------------------------
    // INSERT no banco de dados
    // ---------------------------------------------------------------
    $sql = "
        INSERT INTO aluno (nome, endereco, serie, curso, latitude, longitude, id_ponto)
        VALUES (
            '" . mysqli_real_escape_string($conexao, $nome)               . "',
            '" . mysqli_real_escape_string($conexao, $enderecos)          . "',
            '" . mysqli_real_escape_string($conexao, $serie)              . "',
            '" . mysqli_real_escape_string($conexao, $curso)              . "',
            '" . mysqli_real_escape_string($conexao, $latitude)           . "',
            '" . mysqli_real_escape_string($conexao, $longitude)          . "',
            '" . mysqli_real_escape_string($conexao, $idPontoMaisProximo) . "'
        )
    ";

    $resultadoInsert = mysqli_query($conexao, $sql);

    if(!$resultadoInsert){
        die("Erro no banco: " . mysqli_error($conexao));
    }

} // fim do loop

header("Location: lista.alunos.php?status=sucesso_aluno");
exit();

?>