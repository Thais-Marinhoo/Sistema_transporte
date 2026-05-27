<?php
session_start();
include('conexao.php');

if (empty($_POST['email']) || empty($_POST['senha'])) {
    header('Location: index.php');
    exit();
}

$email = mysqli_real_escape_string($conexao, $_POST['email']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

// AGORA SÓ BUSCA NA TABELA USERS
$query_user = "SELECT id_usuario, login FROM users WHERE login = '$email' AND senha = MD5('$senha')";
$result_user = mysqli_query($conexao, $query_user);

if (mysqli_num_rows($result_user) == 1) {
    $_SESSION['email'] = $email;
    $_SESSION['perfil'] = 'usuario';   // ou 'admin' se quiser dar permissão especial pra algum usuário
    header('Location: site/main.php');
    exit();
} 

// Se não encontrou
header('Location: index.php?status=mistake');
exit();
?>