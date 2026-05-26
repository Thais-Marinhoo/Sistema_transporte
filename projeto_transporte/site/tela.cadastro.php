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

    <title>
        Cadastro Alunos - Rota Certa
    </title>

    <link rel="stylesheet" href="mstyle.css?v=999">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>

<?php include 'menu.php'; ?>

<div class="conteudo">

    <h1 class="titulo">
        Cadastro de Alunos
    </h1>

    <!-- FORM -->
    <form action="cadastrooback.php" method="POST">

        <table class="tabela-alunos" id="tabelaAlunos">

            <thead>

                <tr>

                    <th>Nome</th>

                    <th>Série</th>

                    <th>Curso</th>

                    <th>Onde mora?</th>

                    <th class="coluna-acoes">
                        Ações
                    </th>

                </tr>

            </thead>

            <tbody id="corpoTabela">

                <!-- LINHA MODELO -->
                <tr class="linha-modelo" style="display:none;">

                    <td>

                        <input 
                     type="text"
                     name="nome[]"
                        >

                    </td>

                    <td>

                        <select name="serie[]">

                            <option value="1">1º</option>
                            <option value="2">2º</option>
                            <option value="3">3º</option>

                        </select>

                    </td>

                    <td>

                        <select name="curso[]">

                            <option value="Informatica">
                             Informática
                        </option>

                        <option value="DS">
                                 Desenvolvimento de Sistemas
                                </option>

                        </select>

                    </td>

                    <td>

                        <input 
                     type="text"
                     name="endereco[]"
                        >

                    </td>

                    <td class="coluna-acoes">

                        <div class="lista-acoes">

                            <button 
                                type="button"
                                class="btn-remover"
                            >

                                Remover

                            </button>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

        <!-- BOTÕES -->
        <div class="topo-acoes">

            <div class="quantidade-box">

                <label>
                    Quantidade
                </label>

                <input 
                    type="number"
                    id="quantidadeLinhas"
                    class="input-quantidade"
                    min="1"
                    value="1"
                >

            </div>

            <button 
                type="button"
                id="btnAdd"
                class="btn-add"
            >

                + Adicionar linhas

            </button>

            <button 
                type="submit"
                class="btn-salvar"
            >

                Salvar todos

            </button>

        </div>

    </form>

</div>

<script src="cadastro.js?v=999"></script>

</body>
</html>