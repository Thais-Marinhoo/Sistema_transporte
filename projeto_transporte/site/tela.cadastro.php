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
    <title>Cadastro Alunos</title>

    <link rel="stylesheet" href="mstyle.css?v=999">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

<?php include 'menu.php'; ?>
<?php include 'alertas.php'; ?>

<div class="conteudo">

    <h1 class="titulo">Cadastro de Alunos</h1>

    <form action="cadastroback.php" method="POST" id="formCA">

        <table class="tabela-alunos">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Série</th>
                    <th>Curso</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody id="corpoTabela">

                <!-- MODELO -->
                <tr class="linha-modelo" style="display:none;">
                    <td><input type="text" name="nome[]" disabled></td>

                    <td>
                        <select name="serie[]" disabled>
                            <option value="1">1º</option>
                            <option value="2">2º</option>
                            <option value="3">3º</option>
                        </select>
                    </td>

                    <td>
                        <select name="curso[]" disabled>
                            <option value="Informatica">Informática</option>
                            <option value="DS">DS</option>
                            <option value="Enfermagem">Enfermagem</option>
                            <option value="Administracao">Administração</option>
                        </select>
                    </td>

                    <td><input type="text" name="endereco[]" disabled></td>

                    <td>
                        <button type="button" class="btn-remover">Remover</button>
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="topo-acoes">

            <input type="number" id="quantidadeLinhas" value="1" min="1">

            <button type="button" id="btnAdd" class="btn-add">
                + Adicionar linhas
            </button>

            <button type="submit" class="btn-salvar">
                Salvar todos
            </button>

        </div>

    </form>

</div>

<script src="cadastro.js?v=1"></script>

</body>
</html>