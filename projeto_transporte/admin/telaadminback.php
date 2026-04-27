<?php
session_start();
include "../conexao.php";



//Verificar se os campos estão preenchidos e preencher o array alunos
if(isset($_POST['login']) && $_POST['login'] != '') {
    $conta = array();
    
    $conta['login'] = $_POST['login'];
}

if(isset($_POST['senha']) && $_POST['senha'] != '') {
    $conta['senha'] = $_POST['senha'];
}

$condicao = "";

if (!empty($_GET['busca'])) {
    $campo = $_GET['campo'];
    $valor = $_GET['busca'];
    $valor = "%$valor%";

    if (!empty($_GET['busca'])) {

    $campo = $_GET['campo'];
    $valor = $_GET['busca'];
    // Busca normal (LIKE)
    $valor = "%$valor%";
    $condicao = "WHERE $campo LIKE ?";
    }
}

$sql = "SELECT * FROM users $condicao ORDER BY login";
$stmt = $conexao->prepare($sql);

$stmt->execute();
$result = $stmt->get_result();
$lista_contas = $result->fetch_all(MYSQLI_ASSOC);