<?php
include_once '../conexao.php';
session_start();


// ====================== BUSCAR PONTO PARA EDITAR ======================
$pontoEditar = null;

if (isset($_GET['editar_ponto'])) {

    $id_editar = (int) $_GET['editar_ponto'];

    $stmt = $conexao->prepare("
        SELECT * 
        FROM ponto 
        WHERE id_ponto = ?
    ");

    $stmt->bind_param("i", $id_editar);

    $stmt->execute();

    $resultado = $stmt->get_result();

    $pontoEditar = $resultado->fetch_assoc();
}

// ====================== SALVAR PONTO ======================
if (isset($_POST['salvar_ponto'])) {
    $numero_ponto = trim($_POST['numero_ponto']);
    $nome_ponto   = trim($_POST['nome_ponto']);
    $endereco     = trim($_POST['endereco']);

    if (empty($numero_ponto) || empty($nome_ponto) || empty($endereco)) {
        header("Location: telarotas.php?status=erro_p");
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
        atualizarAlunos($conexao);
        header("Location: telarotas.php?status=sucesso_p");
    } else {
        header("Location: telarotas.php?status=erro_p");
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
        header("Location: telarotas.php?status=erro_r");
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
        header("Location: telarotas.php?status=sucesso_r");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: telarotas.php?status=erro_r");
    }
    exit();
}

// ====================== DELETAR ======================
if (isset($_GET['deletar_ponto'])) {
    $id = (int)$_GET['deletar_ponto'];

    // 1. FUNÇÃO INTERNA HAVERSINE (Apenas para uso rápido aqui dentro)
    $haversine = function($lat1, $lon1, $lat2, $lon2) {
        $raioTerra = 6371;
        $vLat = deg2rad($lat2 - $lat1);
        $vLon = deg2rad($lon2 - $lon1);
        $a = sin($vLat / 2) * sin($vLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($vLon / 2) * sin($vLon / 2);
        return $raioTerra * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    };

    // 2. BUSCA TODOS OS OUTROS PONTOS DISPONÍVEIS (EXCETO O QUE SERÁ DELETADO)
    $sqlOutrosPontos = "SELECT id_ponto, latitude, longitude FROM ponto WHERE id_ponto != ?";
    $stmtOutros = $conexao->prepare($sqlOutrosPontos);
    $stmtOutros->bind_param("i", $id);
    $stmtOutros->execute();
    $resOutros = $stmtOutros->get_result();
    
    $outrosPontos = [];
    while ($p = $resOutros->fetch_assoc()) {
        $outrosPontos[] = $p;
    }

    // 3. SE NÃO HOUVER OUTRO PONTO NO SISTEMA, BLOQUEIA A DELEÇÃO
    // Como a coluna é NOT NULL, o aluno não pode ficar sem ponto nenhum
    if (empty($outrosPontos)) {
        header("Location: telarotas.php?status=erro_pdeletar");
        exit();
    }

    // 4. BUSCA TODOS OS ALUNOS QUE USAM O PONTO QUE VAI SER APAGADO
    $sqlAlunosAfetados = "SELECT id_aluno, latitude, longitude FROM aluno WHERE id_ponto = ?";
    $stmtAlunos = $conexao->prepare($sqlAlunosAfetados);
    $stmtAlunos->bind_param("i", $id);
    $stmtAlunos->execute();
    $resAlunos = $stmtAlunos->get_result();

    // 5. ATUALIZA CADA ALUNO AFETADO COM O PRÓXIMO PONTO MAIS PERTO CADASTRADO
    while ($aluno = $resAlunos->fetch_assoc()) {
        $idAluno = $aluno['id_aluno'];
        $latAluno = (float)$aluno['latitude'];
        $lonAluno = (float)$aluno['longitude'];
        
        $idPontoNovo = null;
        $menorDistancia = 999999;

        foreach ($outrosPontos as $ponto) {
            $dist = $haversine($latAluno, $lonAluno, (float)$ponto['latitude'], (float)$ponto['longitude']);
            if ($dist < $menorDistancia) {
                $menorDistancia = $dist;
                $idPontoNovo = $ponto['id_ponto'];
            }
        }

        // Salva o novo ponto diretamente para o aluno sair da restrição antiga
        if ($idPontoNovo !== null) {
            $stmtMudaAluno = $conexao->prepare("UPDATE aluno SET id_ponto = ? WHERE id_aluno = ?");
            $stmtMudaAluno->bind_param("ii", $idPontoNovo, $idAluno);
            $stmtMudaAluno->execute();
        }
    }

    // 6. REMOVE O PONTO ANTIGO DA TABELA INTERMEDIÁRIA DE ROTAS
    $stmtUpdateRotas = $conexao->prepare("DELETE FROM rota_ponto WHERE id_ponto = ?");
    $stmtUpdateRotas->bind_param("i", $id);
    $stmtUpdateRotas->execute();

    // 7. AGORA SIM, EXCLUI O PONTO DA TABELA PRINCIPAL COM SEGURANÇA
    $stmt = $conexao->prepare("DELETE FROM ponto WHERE id_ponto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: telarotas.php?status=sucesso_pdeletado");
    exit();
}

if (isset($_GET['deletar_rota'])) {
    $id = (int)$_GET['deletar_rota'];
    $conexao->begin_transaction();
    try {
        // Primeira query: rota_ponto
        $stmt1 = $conexao->prepare("DELETE FROM rota_ponto WHERE id_rota = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();

        // Segunda query: rota
        $stmt2 = $conexao->prepare("DELETE FROM rota WHERE id_rota = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();

        $conexao->commit();
        header("Location: telarotas.php?status=sucesso_rdeletada");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: telarotas.php?status=erro_rdeletada");
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
///////////////////listar/////////////////////////////////


function atualizarAlunos($conexao) {
    // 1. Função matemática interna para o cálculo de distância
    $haversine = function($lat1, $lon1, $lat2, $lon2) {
        $raioTerra = 6371;
        $vLat = deg2rad($lat2 - $lat1);
        $vLon = deg2rad($lon2 - $lon1);
        $a = sin($vLat / 2) * sin($vLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($vLon / 2) * sin($vLon / 2);
        return $raioTerra * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    };

    // 2. Busca todos os pontos ativos no banco
    $sqlPontos = "SELECT id_ponto, latitude, longitude FROM ponto";
    $resPontos = mysqli_query($conexao, $sqlPontos);
    $pontosAtivos = [];

    if ($resPontos && mysqli_num_rows($resPontos) > 0) {
        while ($ponto = mysqli_fetch_assoc($resPontos)) {
            $pontosAtivos[] = $ponto;
        }
    }

    // Se não existirem pontos, interrompe para não quebrar a restrição NOT NULL
    if (empty($pontosAtivos)) {
        return false;
    }

    // 3. Busca todos os alunos cadastrados
    $sqlTodosAlunos = "SELECT id_aluno, latitude, longitude, id_ponto FROM aluno";
    $resAlunos = mysqli_query($conexao, $sqlTodosAlunos);

    if ($resAlunos && mysqli_num_rows($resAlunos) > 0) {
        while ($aluno = mysqli_fetch_assoc($resAlunos)) {
            $idAluno = $aluno['id_aluno'];
            $idPontoAtual = $aluno['id_ponto'];
            $latAluno = (float)$aluno['latitude'];
            $lonAluno = (float)$aluno['longitude'];
            
            $idPontoMaisProximo = null;
            $menorDistancia = 999999;

            // Encontra o ponto ideal atual
            foreach ($pontosAtivos as $ponto) {
                $dist = $haversine($latAluno, $lonAluno, (float)$ponto['latitude'], (float)$ponto['longitude']);
                if ($dist < $menorDistancia) {
                    $menorDistancia = $dist;
                    $idPontoMaisProximo = $ponto['id_ponto'];
                }
            }

            // 4. Atualiza apenas se houve mudança de ponto
            if ($idPontoMaisProximo !== null && $idPontoMaisProximo != $idPontoAtual) {
                $sqlUpdateAluno = "UPDATE aluno SET id_ponto = $idPontoMaisProximo WHERE id_aluno = $idAluno";
                mysqli_query($conexao, $sqlUpdateAluno);
            }
        }
    }
    return true;
}

////////////////////////////////////////////////////
?>