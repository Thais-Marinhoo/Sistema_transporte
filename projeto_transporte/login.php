<?php
session_start();
include('conexao.php');

//Isso aqui faz voltar caso n esteja preenchido
if (empty($_POST['email']) || empty($_POST['senha'])) {
    header('Location: index.php');
    exit();
}

//cria uma var e atribui a email e senha
$email = mysqli_real_escape_string($conexao, $_POST['email']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

// 1. TENTA BUSCAR NA TABELA ADMIN
$query_admin = "SELECT id_admin, login FROM admin WHERE login = '$email' AND senha = MD5('$senha')";
$result_admin = mysqli_query($conexao, $query_admin);

if (mysqli_num_rows($result_admin) == 1) {
    $_SESSION['email'] = $email;
    $_SESSION['perfil'] = 'admin'; // Opcional: para saber que é admin nas outras páginas
    header('Location: admin/telaadmin.php'); // Redireciona para tela de Admin
    exit();
}

// 2. SE NÃO FOR ADMIN, TENTA BUSCAR NA TABELA USERS
$query_user = "SELECT id_usuario, login FROM users WHERE login = '$email' AND senha = MD5('$senha')";
$result_user = mysqli_query($conexao, $query_user);

if (mysqli_num_rows($result_user) == 1) {
    $_SESSION['email'] = $email;
    $_SESSION['perfil'] = 'usuario';
    header('Location: site/main.php'); // Redireciona para tela de Usuário comum
    exit();
} 

// 3. SE NÃO ACHOU EM NENHUMA DAS DUAS
$_SESSION['nao_autenticado'] = true;
header('Location: index.php?status=mistake');
exit();
?>