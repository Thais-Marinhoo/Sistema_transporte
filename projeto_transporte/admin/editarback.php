<?php
session_start();
include 'conexao.php';

$id = $_GET['id_usuario'];

//Ajuda do gepeto
# RECEBE CAMPOS
$login = $_POST['login'];

# ATUALIZA NO BANCO
$sql = "UPDATE users SET 
    login = '$login' 
    WHERE id_usuario = $id";

mysqli_query($conexao, $sql);

header("Location: telaadmin.php");
exit();