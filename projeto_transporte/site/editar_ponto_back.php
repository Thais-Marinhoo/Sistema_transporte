<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

// Tipos de resultado aceitos pela Geoapify (rejeita cidade/região genérica)
$TIPOS_VALIDOS = ['street', 'amenity', 'building', 'suburb', 'district'];

if(!isset($_POST['id'])){
    header("Location: telarotas.php?status=erro_p");
    exit();
}

$id            = intval($_POST['id']);
$numero_ponto  = mysqli_real_escape_string($conexao, trim($_POST['numero_ponto']));
$nome_ponto    = mysqli_real_escape_string($conexao, trim($_POST['nome_ponto']));
$endereco_novo = trim($_POST['endereco']);
$endereco_esc  = mysqli_real_escape_string($conexao, $endereco_novo);

$sqlBuscaAntigo = "SELECT endereco, latitude, longitude FROM ponto WHERE id_ponto = $id";
$resAntigo      = mysqli_query($conexao, $sqlBuscaAntigo);
$pontoAntigo    = mysqli_fetch_assoc($resAntigo);

$latitude  = $pontoAntigo['latitude'];
$longitude = $pontoAntigo['longitude'];

if ($pontoAntigo['endereco'] !== $endereco_novo) {

    $API_KEY        = "172ff5e777874a13b995e244562a96a5";
    $endereco_busca = $endereco_novo . ", Crateús, Ceará, Brasil";
    $url_api        = "https://api.geoapify.com/v1/geocode/search"
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

    $lat_nova = null;
    $lon_nova = null;

    if (!empty($resultado_dados) && isset($resultado_dados['features'][0])) {
        $feature    = $resultado_dados['features'][0];
        $confidence = (float) ($feature['properties']['rank']['confidence'] ?? 0);
        $tipo       = $feature['properties']['result_type'] ?? '';

        // Aceita só se tiver confiança suficiente E for tipo específico (não cidade genérica)
        if ($confidence >= 0.4 && in_array($tipo, $TIPOS_VALIDOS)) {
            $lon_nova = (float) $feature['geometry']['coordinates'][0];
            $lat_nova = (float) $feature['geometry']['coordinates'][1];
        }
    }

    if ($lat_nova === null || $lon_nova === null) {
        header("Location: telarotas.php?status=endereco_invalido");
        exit();
    }

    $latitude  = $lat_nova;
    $longitude = $lon_nova;
}

$sql = "UPDATE ponto SET
            numero_ponto = '$numero_ponto',
            nome_ponto   = '$nome_ponto',
            endereco     = '$endereco_esc',
            latitude     = '$latitude',
            longitude    = '$longitude'
        WHERE id_ponto = $id";

$resultado = mysqli_query($conexao, $sql);

if(!$resultado){
    header("Location: telarotas.php?status=erro_banco");
    exit();
}

header("Location: telarotas.php?status=sucesso_peditar");
exit();
?>