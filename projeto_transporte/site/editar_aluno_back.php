```php
<?php

session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

/* VERIFICA SE VEIO O ID */

if(!isset($_POST['id'])){

    die("ID não informado");

}

/* RECEBE DADOS */

$id =
intval($_POST['id']);

$nome =
mysqli_real_escape_string(
    $conexao,
    $_POST['nome']
);

$serie =
mysqli_real_escape_string(
    $conexao,
    $_POST['serie']
);

$curso =
mysqli_real_escape_string(
    $conexao,
    $_POST['curso']
);

$endereco =
mysqli_real_escape_string(
    $conexao,
    $_POST['endereco']
);

/* UPDATE */

$sql = "

    UPDATE aluno

    SET

    nome = '$nome',

    serie = '$serie',

    curso = '$curso',

    endereco = '$endereco'

    WHERE id_aluno = $id

";

$resultado =
mysqli_query($conexao, $sql);

/* ERRO */

if(!$resultado){

    die(
        "Erro ao atualizar: "
        .
        mysqli_error($conexao)
    );

}

/* VOLTA */

header("Location: lista.alunos.php");

exit();

?>
```
