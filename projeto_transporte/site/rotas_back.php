<?php
include_once '../conexao.php';
session_start();

// ====================== DEBUG (remova depois) ======================
if (isset($_POST['salvar_rota'])) {
    echo "<h3>DEBUG: Formulário recebido!</h3>";
    echo "Nome Rota: " . ($_POST['nome_rota'] ?? 'vazio') . "<br>";
    echo "Motorista: " . ($_POST['motorista_m'] ?? 'vazio') . "<br>";
}

// ====================== SALVAR PONTO ======================
if (isset($_POST['salvar_ponto'])) {
    $numero_ponto = trim($_POST['numero_ponto']);
    $nome_ponto   = trim($_POST['nome_ponto']);
    $endereco     = trim($_POST['endereco']);

    if (empty($numero_ponto) || empty($nome_ponto) || empty($endereco)) {
        header("Location: telarotas.php?erro=Preencha todos os campos do ponto");
        exit();
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////    
// ... (Seu código existente recebe as variáveis do formulário, ex: $endereco = $_POST['endereco'];)

// 1. Prepara o endereço garantindo que a busca foque em Crateús
$endereco_filtrado = $endereco . ", Crateús, Ceará, Brasil";

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
    header("Location: telarotas.php?status=endereco");
    exit; // Interrompe para não salvar dados vazios no banco
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $stmt = $conexao->prepare("INSERT INTO ponto (numero_ponto, nome_ponto, endereco, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issdd", $numero_ponto, $nome_ponto, $endereco, $latitude, $longitude);

    if ($stmt->execute()) {
        header("Location: telarotas.php?sucesso=Ponto cadastrado com sucesso!");
    } else {
        header("Location: telarotas.php?erro=Erro ao cadastrar ponto");
    }
    exit();
}

// ====================== SALVAR ROTA ======================
if (isset($_POST['salvar_rota'])) {
    $nome_rota     = trim($_POST['nome_rota'] ?? '');
    $motorista_m   = trim($_POST['motorista_m'] ?? '');
    $motorista_t   = trim($_POST['motorista_t'] ?? '');
    $status        = trim($_POST['status'] ?? '');
    $status_tarde  = trim($_POST['terceirizado_secundario'] ?? '');

    $pontos_selecionados = $_POST['pontos'] ?? [];

    if (empty($nome_rota) || empty($motorista_m) || empty($status)) {
        header("Location: telarotas.php?erro=Preencha os campos obrigatórios!");
        exit();
    }

    $conexao->begin_transaction();

    try {
        $stmt = $conexao->prepare("INSERT INTO rota (nome_rota, motorista_m, motorista_t, status, status_tarde) 
                                   VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome_rota, $motorista_m, $motorista_t, $status, $status_tarde);
        $stmt->execute();
        $id_rota = $conexao->insert_id;

        if (!empty($pontos_selecionados)) {
            $stmt2 = $conexao->prepare("INSERT INTO rota_ponto (id_rota, id_ponto, ordem) VALUES (?, ?, ?)");
            foreach ($pontos_selecionados as $ordem => $id_ponto) {
                $ordem++;
                $stmt2->bind_param("iii", $id_rota, $id_ponto, $ordem);
                $stmt2->execute();
            }
        }

        $conexao->commit();
        header("Location: telarotas.php?sucesso=Ônibus cadastrado com sucesso!");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: telarotas.php?erro=Erro ao salvar: " . $e->getMessage());
    }
    exit();
}

// ====================== DELETAR ======================
if (isset($_GET['deletar_ponto'])) {
    $id = (int)$_GET['deletar_ponto'];
    $stmt = $conexao->prepare("DELETE FROM ponto WHERE id_ponto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: telarotas.php?sucesso=Ponto deletado!");
    exit();
}

if (isset($_GET['deletar_rota'])) {
    $id = (int)$_GET['deletar_rota'];
    $conexao->begin_transaction();
    try {
        $conexao->prepare("DELETE FROM rota_ponto WHERE id_rota = ?")->execute([$id]);
        $conexao->prepare("DELETE FROM rota WHERE id_rota = ?")->execute([$id]);
        $conexao->commit();
        header("Location: telarotas.php?sucesso=Rota deletada!");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: telarotas.php?erro=Erro ao deletar");
    }
    exit();
}

// ====================== FUNÇÕES ======================
function listarPontos($conexao) {
    $sql = "SELECT * FROM ponto ORDER BY numero_ponto ASC";
    return mysqli_query($conexao, $sql);
}

function listarRotas($conexao) {
    $sql = "
        SELECT r.*, 
               GROUP_CONCAT(CONCAT(p.numero_ponto, ' - ', p.nome_ponto) ORDER BY rp.ordem SEPARATOR ' | ') as pontos_nomes 
        FROM rota r 
        LEFT JOIN rota_ponto rp ON r.id_rota = rp.id_rota 
        LEFT JOIN ponto p ON rp.id_ponto = p.id_ponto 
        GROUP BY r.id_rota 
        ORDER BY r.id_rota DESC";
    return mysqli_query($conexao, $sql);
}
?>