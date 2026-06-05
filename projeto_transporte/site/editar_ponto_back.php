<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

if(!isset($_POST['id'])){
    die("ID não informado");
}

$id           = intval($_POST['id']);
$numero_ponto = mysqli_real_escape_string($conexao, trim($_POST['numero_ponto']));
$nome_ponto   = mysqli_real_escape_string($conexao, trim($_POST['nome_ponto']));
$endereco_novo = trim($_POST['endereco']);
$endereco_esc  = mysqli_real_escape_string($conexao, $endereco_novo);

// Busca os dados atuais do ponto no banco para comparar
$sqlBuscaAntigo = "SELECT endereco, latitude, longitude FROM ponto WHERE id_ponto = $id";
$resAntigo      = mysqli_query($conexao, $sqlBuscaAntigo);
$pontoAntigo    = mysqli_fetch_assoc($resAntigo);

// Preserva lat/lon atuais caso o endereço não tenha mudado
$latitude  = $pontoAntigo['latitude'];
$longitude = $pontoAntigo['longitude'];

// Só chama a API se o usuário realmente alterou o endereço
if ($pontoAntigo['endereco'] !== $endereco_novo) {

    // ---------------------------------------------------------------
    // Geocodificação via Geoapify
    // ---------------------------------------------------------------
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

    // GeoJSON retorna [longitude, latitude] — ordem invertida!
    // confidence >= 0.4 garante que a API realmente achou o endereço (não apenas chutou)
    if (!empty($resultado_dados) && isset($resultado_dados['features'][0]['geometry']['coordinates'])) {
        $confidence = (float) ($resultado_dados['features'][0]['properties']['rank']['confidence'] ?? 0);
        if ($confidence >= 0.4) {
            $lon_nova = (float) $resultado_dados['features'][0]['geometry']['coordinates'][0];
            $lat_nova = (float) $resultado_dados['features'][0]['geometry']['coordinates'][1];
        }
    }

    if ($lat_nova === null || $lon_nova === null) {
        header("Location: telarotas.php?status=endereco_invalido");
        exit();
    }

    $latitude  = $lat_nova;
    $longitude = $lon_nova;
}

// Atualiza o ponto com os dados novos (ou preservados)
$sql = "UPDATE ponto SET
            numero_ponto = '$numero_ponto',
            nome_ponto   = '$nome_ponto',
            endereco     = '$endereco_esc',
            latitude     = '$latitude',
            longitude    = '$longitude'
        WHERE id_ponto = $id";

$resultado = mysqli_query($conexao, $sql);

if(!$resultado){
    die("Erro ao atualizar: " . mysqli_error($conexao));
}

header("Location: telarotas.php?status=sucesso_peditar");
exit();
?>