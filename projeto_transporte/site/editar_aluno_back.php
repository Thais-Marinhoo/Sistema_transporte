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

$id = intval($_POST['id']);
$nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
$serie = mysqli_real_escape_string($conexao, trim($_POST['serie']));
$curso = mysqli_real_escape_string($conexao, trim($_POST['curso']));
$endereco_novo = trim($_POST['endereco']);
$endereco_esc = mysqli_real_escape_string($conexao, $endereco_novo);

// BUSCA O ENDEREÇO ANTIGO SALVO NO BANCO PARA COMPARAR
$sqlBuscaAntigo = "SELECT endereco, latitude, longitude, id_ponto FROM aluno WHERE id_aluno = $id";
$resAntigo = mysqli_query($conexao, $sqlBuscaAntigo);
$alunoAntigo = mysqli_fetch_assoc($resAntigo);

// Valores padrão caso o endereço NÃO tenha mudado
$latitude = $alunoAntigo['latitude'];
$longitude = $alunoAntigo['longitude'];
$idPontoMaisProximo = $alunoAntigo['id_ponto'];

// SÓ CONSULTA A API SE O USUÁRIO ALTEROU O TEXTO DO ENDEREÇO
if ($alunoAntigo['endereco'] !== $endereco_novo) {
    
    $endereco_filtrado = $endereco_novo . ", Crateús, Ceará, Brasil";
    $url_api = "nominatim.openstreetmap.org/search?q=" . urlencode($endereco_filtrado) . "&format=json&limit=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "SistemaTransporteCrateus/1.0 (heitor.almeida2@aluno.ce.gov.br)");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $resposta_texto = curl_exec($ch);
    curl_close($ch);

    $resultado_dados = json_decode($resposta_texto, true);

    if (!empty($resultado_dados) && isset($resultado_dados[0]['lat'])) {
        $latitude = (float) $resultado_dados[0]['lat'];
        $longitude = (float) $resultado_dados[0]['lon'];

        // Recalcula o ponto mais próximo para a nova rua
        $idPontoMaisProximo = "NULL";
        $menorDistancia = 999999;
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
    } else {
        // Se mudou o endereço e a API não achou, devolve o erro sem salvar lixo
        header("Location: lista.alunos.php?status=endereco_invalido");
        exit();
    }
}

// EXECUTA O UPDATE COM TOTAL RAPIDEZ (USANDO OS DADOS NOVOS OU PRESERVANDO OS ANTIGOS)
$sql = "UPDATE aluno SET 
            nome = '$nome', 
            serie = '$serie', 
            curso = '$curso', 
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
