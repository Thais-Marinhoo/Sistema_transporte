<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Alunos - Rota Certa</title>

    <!-- Seu CSS -->
    <link rel="stylesheet" href="mstyle.css">

    <!-- Ícones -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="conteudo">

    <h1 class="titulo">Cadastro de Alunos </h1>

    <form action="salvar_lote.php" method="POST">

        <table class="tabela-alunos" id="tabelaAlunos">
            <tr>
                <th>Nome</th>
                <th>Série</th>
                <th>Curso</th>
                <th>Onde mora?</th>
            </tr>

            <!-- LINHA MODELO -->
            <tr class="linha-modelo">
                <td><input type="text" name="nome[]" required></td>

                <td>
                    <select name="serie[]">
                        <option value="1">1º</option>
                        <option value="2">2º</option>
                        <option value="3">3º</option>
                    </select>
                </td>

                <td>
                    <select name="curso[]">
                        <option value="Info">Informática</option>
                        <option value="DS">Desenvolvimento de Sistemas</option>
                    </select>
                </td>

                <td><input type="text" name="endereco[]" required></td>
            </tr>
        </table>

        <br>

        <button type="button" id="btnAdd" class="btn-add">
            + Adicionar linha
        </button>

        <button type="submit" class="btn-salvar">
            Salvar todos
        </button>

    </form>

</div>

<!-- JS separado -->
<script src="cadastro.js"></script>

</body>
</html>
