<?php

include('conexao.php');

$email = mysqli_real_escape_string($conexao,$_POST['email']);
$codigo = mysqli_real_escape_string($conexao,$_POST['codigo']);
$senha = mysqli_real_escape_string($conexao,$_POST['senha']);

$sql = mysqli_query(
    $conexao,
    "SELECT *
     FROM users
     WHERE login='$email'
     AND codigo_recuperacao='$codigo'
     AND codigo_expira > NOW()"
);

if(mysqli_num_rows($sql) == 0){

    die("Código inválido ou expirado");

}

mysqli_query(
    $conexao,
    "UPDATE users
     SET senha = SHA2('$senha',256),
         codigo_recuperacao = NULL,
         codigo_expira = NULL
     WHERE login='$email'"
);

header("Location:index.php?status=senha_alterada");