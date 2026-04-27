<?php
session_start();
include '../conexao.php';
//verificar se os campos estão vazios
if(empty($_POST['login'] || empty($_POST['senha']) || empty($_POST['senha2']))) {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    header('Location: cadastro.php');
    exit();
}

if($_POST['senha2'] != $_POST['senha']) {
    $_SESSION['mensagem'] = "A senha de confirmação está errada.";
    header('Location: cadastro.php');
    exit();
}

//sanitização contra atque sql injection
$email = mysqli_escape_string($conexao, trim($_POST['login']));
$senha = mysqli_escape_string($conexao, trim($_POST['senha2']));

//verificar se já não existem campos iguais nos bancos
$sql = "SELECT count(*) AS total FROM users WHERE login = '$email'";
$result = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($result);

if($row['total'] > 0) {
    $_SESSION['mensagem'] = "Email já cadastrado!";
    header('Location: cadastro.php');
    exit();
}

//inserir um novo usuário no banco
$sqlInserir = "INSERT INTO users(login, senha)
                    VALUES('$email', MD5('$senha'))";

if(mysqli_query($conexao, $sqlInserir)) {
    $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça Login.";
    header('Location: telaadmin.php');
    exit();
}else {
    $_SESSION['mensagem'] = "Erro ao cadastrar!" . mysqli_error($conexao);
    header('Location: cadastro.php');
    exit();
}
?>