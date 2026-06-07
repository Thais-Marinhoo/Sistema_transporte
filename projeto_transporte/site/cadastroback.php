<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

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

// Tipos de resultado aceitos pela Geoapify (rejeita cidade/região genérica)
$TIPOS_VALIDOS = ['street', 'amenity', 'building', 'suburb', 'district'];

$sqlVerificaPontos = "SELECT COUNT(*) as total FROM ponto";
$resPontos         = mysqli_query($conexao, $sqlVerificaPontos);
$dadosPontos       = mysqli_fetch_assoc($resPontos);

if (!$dadosPontos || $dadosPontos['total'] == 0) {
    header("Location: tela.cadastro.php?status=sem_pontos");
    exit();
}

if(!isset($_POST['nome'])){
    header("Location: tela.cadastro.php?status=falta_info");
    exit();
}

$nomes    = $_POST['nome'];
$series   = $_POST['serie'];
$cursos   = $_POST['curso'];
$endereco = $_POST['endereco'];

$API_KEY = "172ff5e777874a13b995e244562a96a5";

for($i = 0; $i < count($nomes); $i++){

    $nome      = trim($nomes[$i]);
    $serie     = trim($series[$i]);
    $curso     = trim($cursos[$i]);
    $enderecos = trim($endereco[$i]);

    if($nome == "" || $serie == "" || $curso == "" || $enderecos == ""){
        header("Location: tela.cadastro.php?status=falta_info");
        exit();
    }

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

    if (!empty($resultado_dados) && isset($resultado_dados['features'][0])) {
        $feature    = $resultado_dados['features'][0];
        $confidence = (float) ($feature['properties']['rank']['confidence'] ?? 0);
        $tipo       = $feature['properties']['result_type'] ?? '';

        // Aceita só se tiver confiança >= 0.4 E for um tipo específico (rua, ponto, bairro)
        // Rejeita resultados genéricos de cidade/estado que a API chuta quando não acha nada
        if ($confidence >= 0.4 && in_array($tipo, $TIPOS_VALIDOS)) {
            $longitude = (float) $feature['geometry']['coordinates'][0];
            $latitude  = (float) $feature['geometry']['coordinates'][1];
        }
    }

    if ($latitude === null || $longitude === null) {
        header("Location: tela.cadastro.php?status=endereco");
        exit();
    }

    $nomeEsc     = mysqli_real_escape_string($conexao, $nome);
    $sqlVerifica = "SELECT id_aluno FROM aluno WHERE nome = '$nomeEsc'";
    $resultado   = mysqli_query($conexao, $sqlVerifica);

    if($resultado && mysqli_num_rows($resultado) > 0){
        continue;
    }

    $idPontoMaisProximo = "NULL";
    $menorDistancia     = 999999;
    $sqlPontos          = "SELECT id_ponto, latitude, longitude FROM ponto";
    $resultadoPontos    = mysqli_query($conexao, $sqlPontos);

    if ($resultadoPontos && mysqli_num_rows($resultadoPontos) > 0) {
        while ($ponto = mysqli_fetch_assoc($resultadoPontos)) {
            $distancia = Haversine($latitude, $longitude, $ponto['latitude'], $ponto['longitude']);
            if ($distancia < $menorDistancia) {
                $menorDistancia     = $distancia;
                $idPontoMaisProximo = $ponto['id_ponto'];
            }
        }
    }

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
        header("Location: tela.cadastro.php?status=erro_banco");
        exit();
    }

}

header("Location: lista.alunos.php?status=sucesso_aluno");
exit();

?>