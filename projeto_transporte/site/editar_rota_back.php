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

$id = intval($_POST['id']);

$nome_rota = mysqli_real_escape_string($conexao, $_POST['nome_rota']);
$motorista_m = mysqli_real_escape_string($conexao, $_POST['motorista_m']);
$motorista_t = mysqli_real_escape_string($conexao, $_POST['motorista_t']);
$status = mysqli_real_escape_string($conexao, $_POST['status']);
$status_tarde = mysqli_real_escape_string($conexao, $_POST['status_tarde']);

$sql = "
UPDATE rota
SET
    nome_rota = '$nome_rota',
    motorista_m = '$motorista_m',
    motorista_t = '$motorista_t',
    status = '$status',
    status_tarde = '$status_tarde'
WHERE id_rota = $id
";

mysqli_query($conexao, $sql);

header("Location: telarotas.php?sucesso=Rota atualizada com sucesso");
exit();
?>