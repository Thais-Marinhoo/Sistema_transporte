<?php
include '../conexao.php';

if (!isset($_POST['nome'], $_POST['serie'], $_POST['curso'], $_POST['endereco'])) {
    echo "<script>alert('Dados não recebidos!'); window.location='cadastro.php';</script>";
    exit();
}

$nomes = $_POST['nome'];
$series = $_POST['serie'];
$cursos = $_POST['curso'];
$enderecos = $_POST['endereco'];

for ($i = 0; $i < count($nomes); $i++) {

    $nome = trim($nomes[$i]);
    $serie = trim($series[$i]);
    $curso = trim($cursos[$i]);
    $endereco = trim($enderecos[$i]);

    if ($nome == "" || $serie == "" || $curso == "" || $endereco == "") {
        echo "<script>alert('Por favor, preencha todos os campos!'); window.location='tela.cadastro.php';</script>";
        exit(); //verificar depois quando tiver tempo livre
    }

    // ESCAPA STRINGS (IMPORTANTE)
    $nomeEsc = mysqli_real_escape_string($conexao, $nome);

    $sqlVerifica = "SELECT id FROM aluno WHERE nome = '$nomeEsc'";
    $resultado = mysqli_query($conexao, $sqlVerifica);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        continue; // NÃO PARA O SISTEMA TODO
    }

    $sql = "INSERT INTO aluno (nome, endereco, serie, curso)
            VALUES (
                '" . mysqli_real_escape_string($conexao, $nome) . "',
                '" . mysqli_real_escape_string($conexao, $endereco) . "',
                '" . mysqli_real_escape_string($conexao, $serie) . "',
                '" . mysqli_real_escape_string($conexao, $curso) . "'
            )";

    mysqli_query($conexao, $sql);
}

echo "<script>
    alert('Alunos cadastrados com sucesso!');
    window.location='lista_alunos.php';
</script>";
?>