<?php

include 'conexao.php';

if(isset($_POST['nome'])){

    $nomes = $_POST['nome'];
    $series = $_POST['serie'];
    $cursos = $_POST['curso'];
    $enderecos = $_POST['endereco'];

    for($i = 0; $i < count($nomes); $i++){

        $nome = $nomes[$i];
        $serie = $series[$i];
        $curso = $cursos[$i];
        $endereco = $enderecos[$i];

        $sql = "INSERT INTO alunos
        (nome, serie, curso, endereco)

        VALUES

        ('$nome', '$serie', '$curso', '$endereco')";

        mysqli_query($conexao, $sql);
    }

    header("Location: lista.alunos.php");
}
?>