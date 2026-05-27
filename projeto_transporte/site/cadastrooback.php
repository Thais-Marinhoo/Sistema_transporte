<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

if(!isset($_POST['nome'])){
    die("Nenhum dado recebido!");
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

    // IGNORA LINHAS VAZIAS
    if(
        $nome == "" ||
        $serie == "" ||
        $curso == "" ||
        $endereco == ""
    ){
        continue;
    }

    // ESCAPA DADOS
    $nomeEsc = mysqli_real_escape_string($conexao, $nome);

    // VERIFICA SE O ALUNO JÁ EXISTE
    $sqlVerifica = "
        SELECT id_aluno
        FROM aluno
        WHERE nome = '$nomeEsc'
    ";

    $resultado = mysqli_query($conexao, $sqlVerifica);

    if($resultado && mysqli_num_rows($resultado) > 0){
        continue;
    }

    // INSERT
    $sql = "
        INSERT INTO aluno
        (
            nome,
            endereco,
            serie,
            curso,
            id_ponto
        )

        VALUES
        (
            '".mysqli_real_escape_string($conexao, $nome)."',

            '".mysqli_real_escape_string($conexao, $endereco)."',

            '".mysqli_real_escape_string($conexao, $serie)."',

            '".mysqli_real_escape_string($conexao, $curso)."',

            NULL
        )
    ";

    $resultadoInsert = mysqli_query($conexao, $sql);

    // MOSTRA ERRO SE DER PROBLEMA
    if(!$resultadoInsert){

        die("Erro no banco: " . mysqli_error($conexao));

    }

}

// REDIRECIONA
header("Location: lista.alunos.php");
exit();

?>
