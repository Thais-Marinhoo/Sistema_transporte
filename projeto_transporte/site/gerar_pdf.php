
<?php

session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

/* BUSCA ALUNOS */

$sql = "
    SELECT *
    FROM aluno
    ORDER BY nome ASC
";

$resultado =
mysqli_query($conexao, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<title>
    Lista de Alunos
</title>

<style>

body{

    font-family:Arial;

    padding:40px;

}

h1{

    text-align:center;

    color:#0b2c5f;

    margin-bottom:30px;

}

table{

    width:100%;

    border-collapse:collapse;

}

th{

    background:#0b2c5f;

    color:white;

    padding:12px;

    text-align:left;

}

td{

    border:1px solid #ddd;

    padding:12px;

}

@media print{

    button{

        display:none;

    }

}

</style>

</head>

<body>

<button onclick="window.print()">

    Imprimir / Salvar PDF

</button>

<h1>
    Lista de Alunos
</h1>

<table>

<tr>

    <th>Nome</th>

    <th>Série</th>

    <th>Curso</th>

    <th>Endereço</th>

</tr>

<?php

while(
    $aluno =
    mysqli_fetch_assoc($resultado)
){

?>

<tr>

    <td>
        <?php echo $aluno['nome']; ?>
    </td>

    <td>
        <?php echo $aluno['serie']; ?>
    </td>

    <td>
        <?php echo $aluno['curso']; ?>
    </td>

    <td>
        <?php echo $aluno['endereco']; ?>
    </td>

</tr>

<?php } ?>

</table>

<script>

window.onload = function(){

    window.print();

}

</script>

</body>
</html>

