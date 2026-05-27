<?php
session_start();
include('conexao.php');

// Verifica se email e senha foram enviados
if (empty($_POST['email']) || empty($_POST['senha'])) {
    header('Location: index.php');
    exit();
}

$email = mysqli_real_escape_string($conexao, $_POST['email']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

// Busca apenas na tabela users
$query = "SELECT id_usuario, login 
          FROM users 
          WHERE login = '$email' 
          AND senha = MD5('$senha')";

$result = mysqli_query($conexao, $query);

// Verifica se encontrou usuário
if ($result && mysqli_num_rows($result) == 1) {

    $_SESSION['email'] = $email;
    $_SESSION['perfil'] = 'usuario';

    header('Location: site/main.php');
    exit();
}

// Login inválido
$_SESSION['nao_autenticado'] = true;
header('Location: index.php?status=mistake');
exit();
?>