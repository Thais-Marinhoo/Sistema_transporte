<?php
include_once '../conexao.php';
session_start();

// ====================== SALVAR PONTO ======================
if (isset($_POST['salvar_ponto'])) {
    $numero_ponto = trim($_POST['numero_ponto']);
    $nome_ponto   = trim($_POST['nome_ponto']);
    $endereco     = trim($_POST['endereco']);

    if (empty($numero_ponto) || empty($nome_ponto) || empty($endereco)) {
        header("Location: telarotas.php?erro=Preencha todos os campos do ponto");
        exit();
    }

    $stmt = $conexao->prepare("INSERT INTO ponto (numero_ponto, nome_ponto, endereco) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $numero_ponto, $nome_ponto, $endereco);

    if ($stmt->execute()) {
        header("Location: telarotas.php?sucesso=Ponto cadastrado com sucesso!");
    } else {
        header("Location: telarotas.php?erro=Erro ao cadastrar ponto");
    }
    exit();
}

// ====================== SALVAR ROTA ======================
if (isset($_POST['salvar_rota'])) {
    $nome_rota           = trim($_POST['nome_rota']);
    $motorista_m         = trim($_POST['motorista_m']);
    $motorista_t         = trim($_POST['motorista_t'] ?? '');
    $status              = trim($_POST['status']);
    $status_secundario   = trim($_POST['terceirizado_secundario'] ?? '');

    $pontos_selecionados = $_POST['pontos'] ?? [];

    if (empty($nome_rota) || empty($motorista_m) || empty($status)) {
        header("Location: telarotas.php?erro=Preencha os campos obrigatórios da rota");
        exit();
    }

    $conexao->begin_transaction();

    try {
        $stmt = $conexao->prepare("INSERT INTO rota 
            (nome_rota, motorista_m, motorista_t, status, status_secundario) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome_rota, $motorista_m, $motorista_t, $status, $status_secundario);
        $stmt->execute();
        $id_rota = $conexao->insert_id;

        if (!empty($pontos_selecionados)) {
            $stmt = $conexao->prepare("INSERT INTO rota_ponto (id_rota, id_ponto, ordem) VALUES (?, ?, ?)");
            foreach ($pontos_selecionados as $ordem => $id_ponto) {
                $ordem++;
                $stmt->bind_param("iii", $id_rota, $id_ponto, $ordem);
                $stmt->execute();
            }
        }

        $conexao->commit();
        header("Location: telarotas.php?sucesso=Rota cadastrada com sucesso!");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: telarotas.php?erro=Erro ao salvar rota");
    }
    exit();
}

// ====================== DELETAR ======================
if (isset($_GET['deletar_ponto'])) {
    $id = (int)$_GET['deletar_ponto'];
    $stmt = $conexao->prepare("DELETE FROM ponto WHERE id_ponto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: telarotas.php?sucesso=Ponto deletado com sucesso!");
    exit();
}

if (isset($_GET['deletar_rota'])) {
    $id = (int)$_GET['deletar_rota'];
    $conexao->begin_transaction();
    try {
        $stmt = $conexao->prepare("DELETE FROM rota_ponto WHERE id_rota = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt = $conexao->prepare("DELETE FROM rota WHERE id_rota = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $conexao->commit();
        header("Location: telarotas.php?sucesso=Rota deletada com sucesso!");
    } catch (Exception $e) {
        $conexao->rollback();
        header("Location: telarotas.php?erro=Erro ao deletar rota");
    }
    exit();
}

// ====================== LISTAR ======================
function listarPontos($conexao) {
    $sql = "SELECT * FROM ponto ORDER BY numero_ponto ASC";
    return mysqli_query($conexao, $sql);
}
