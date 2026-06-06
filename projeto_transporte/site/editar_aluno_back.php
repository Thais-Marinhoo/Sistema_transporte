<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

function Haversine($lat1, $lon1, $lat2, $lon2) {
    $raioTerra = 6371;
    $vLat = deg2rad($lat2 - $lat1); $vLon = deg2rad($lon2 - $lon1);
    $a = sin($vLat / 2) * sin($vLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($vLon / 2) * sin($vLon / 2);
    return $raioTerra * (2 * atan2(sqrt($a), sqrt(1 - $a))); 
}

if(!isset($_POST['id']) || empty($_POST['id'])){
    header("Location: lista.alunos.php");
    exit();
}

$id            = intval($_POST['id']);
$nome          = mysqli_real_escape_string($conexao, trim($_POST['nome']));
$serie         = mysqli_real_escape_string($conexao, trim($_POST['serie']));
$curso         = mysqli_real_escape_string($conexao, trim($_POST['curso']));
$endereco_novo = trim($_POST['endereco']);
$endereco_esc  = mysqli_real_escape_string($conexao, $endereco_novo);

// Busca os dados atuais do aluno no banco para comparar
$sqlBuscaAntigo = "SELECT endereco, latitude, longitude, id_ponto FROM aluno WHERE id_aluno = $id";
$resAntigo      = mysqli_query($conexao, $sqlBuscaAntigo);
$alunoAntigo    = mysqli_fetch_assoc($resAntigo);

// Valores padrão — preserva os dados salvos caso o endereço não tenha mudado
$latitude           = $alunoAntigo['latitude'];
$longitude          = $alunoAntigo['longitude'];
$idPontoMaisProximo = $alunoAntigo['id_ponto'];

// Só chama a API se o usuário realmente alterou o texto do endereço
if ($alunoAntigo['endereco'] !== $endereco_novo) {

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
        header("Location: lista.alunos.php?status=endereco_invalido");
        exit();
    }

    $latitude  = $lat_nova;
    $longitude = $lon_nova;

    // Recalcula o ponto de embarque mais próximo para o novo endereço
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
}

// Salva tudo no banco (dados novos ou preservados)
$sql = "UPDATE aluno SET 
            nome     = '$nome', 
            serie    = '$serie', 
            curso    = '$curso', 
            endereco = '$endereco_esc', 
            latitude = '$latitude', 
            longitude = '$longitude', 
            id_ponto = $idPontoMaisProximo 
        WHERE id_aluno = $id";

if(mysqli_query($conexao, $sql)){
    header("Location: lista.alunos.php?status=sucesso_edicao");
} else {
    die("Erro ao atualizar no banco: " . mysqli_error($conexao));
}
exit();
?>