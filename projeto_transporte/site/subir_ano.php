<?php

session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

/* REMOVE ALUNOS DO 3º ANO */

$sqlExcluir = "
    DELETE FROM aluno
    WHERE serie = '3'
";

mysqli_query($conexao, $sqlExcluir);

/* 2º ANO -> 3º ANO */

$sqlSegundo = "
    UPDATE aluno
    SET serie = '3'
    WHERE serie = '2'
";

mysqli_query($conexao, $sqlSegundo);

/* 1º ANO -> 2º ANO */

$sqlPrimeiro = "
    UPDATE aluno
    SET serie = '2'
    WHERE serie = '1'
";

mysqli_query($conexao, $sqlPrimeiro);

header("Location: lista.alunos.php");
exit();

?>