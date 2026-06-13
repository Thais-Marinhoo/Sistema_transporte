<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

if(!isset($_GET['id'])){
    die("ID não informado");
}

$id = intval($_GET['id']);

$sql = "DELETE FROM aluno WHERE id_aluno = $id";
$resultado = mysqli_query($conexao, $sql);

if(!$resultado){
    die("Erro ao excluir: " . mysqli_error($conexao));
}

header("Location: lista.alunos.php?status=sucesso_exclusao");
exit();
?>
