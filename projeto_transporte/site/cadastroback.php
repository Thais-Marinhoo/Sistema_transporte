<?php
include '../conexao.php';

if(!isset($_POST['nome'])){
    die("Nenhum dado recebido");
}

$nomes = $_POST['nome'];
$series = $_POST['serie'];
$cursos = $_POST['curso'];
$enderecos = $_POST['endereco'];

for($i = 0; $i < count($nomes); $i++){

    $nome = trim($nomes[$i]);
    $serie = trim($series[$i]);
    $curso = trim($cursos[$i]);
    $endereco = trim($enderecos[$i]);

    if($nome == "" || $serie == "" || $curso == "" || $endereco == ""){
        header("Location: lista.alunos.php?status=erro");
        exit;
    }

    // INSERE NO BANCO
    $sql = "INSERT INTO aluno (nome, serie, curso, endereco)
            VALUES (
            '".mysqli_real_escape_string($conexao,$nome)."',
            '".mysqli_real_escape_string($conexao,$serie)."',
            '".mysqli_real_escape_string($conexao,$curso)."',
            '".mysqli_real_escape_string($conexao,$endereco)."'
            )";

    mysqli_query($conexao, $sql);
}

header("Location: lista.alunos.php?status=sucesso_aluno");
exit;