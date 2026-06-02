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

$numero_ponto = mysqli_real_escape_string($conexao, $_POST['numero_ponto']);
$nome_ponto   = mysqli_real_escape_string($conexao, $_POST['nome_ponto']);
$endereco     = mysqli_real_escape_string($conexao, $_POST['endereco']);

$sql = "

UPDATE ponto
SET
    numero_ponto = '$numero_ponto',
    nome_ponto = '$nome_ponto',
    endereco = '$endereco'
WHERE id_ponto = $id

";

$resultado = mysqli_query($conexao, $sql);

if(!$resultado){
    die("Erro ao atualizar: " . mysqli_error($conexao));
}

header("Location: telarotas.php?sucesso=Ponto atualizado");
exit();
?>