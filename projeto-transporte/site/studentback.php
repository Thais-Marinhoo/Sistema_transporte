<?php
session_start();
include '../conexao.php';
//verificar se os campos estão vazios
if(empty($_POST['name']) || empty($_POST['street']) || empty($_POST['bairro']) || empty($_POST['curso'])
    || empty($_POST['curson'])) {
    $_SESSION['erro'] = "Preencha todos os campos!";
    header('Location: student.php');
    exit();
}

//Chat gpt correcting

# VALIDAR NOME (só letras e espaços)
if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ ]+$/", $_POST['name'])) {
    $_SESSION['erro'] = "O nome deve conter apenas letras.";
    header('Location: student.php');
    exit();
}

//sanitização contra atque sql injection
$nome = mysqli_escape_string($conexao, trim($_POST['name']));
$street = mysqli_escape_string($conexao, trim($_POST['street']));
$bairro = mysqli_escape_string($conexao, trim($_POST['bairro']));
$curso = mysqli_escape_string($conexao, trim($_POST['curso']));
$curson = mysqli_escape_string($conexao, trim($_POST['curson']));


//inserir um novo usuário no banco
$sqlInserir = "INSERT INTO aluno(nome, endereco, bairro, serie, curso)
                    VALUES('$nome', '$street', '$bairro', '$curson', '$curso')";

if(mysqli_query($conexao, $sqlInserir)) {
    $_SESSION['sucesso'] = "Dados salvos com sucesso.";
    header('Location: student.php');
    exit();
}else {
    $_SESSION['erro'] = "Erro ao salvar os arquivos!" . mysqli_error($conexao);
    header('Location: student.php');
    exit();
}


?>