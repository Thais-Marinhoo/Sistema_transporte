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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lista de Alunos - Rota Certa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="mstyle.css?v=1">

</head>

<body>

<?php include "menu.php"; ?>

<div class="lista-alunos-page">

    <div class="lista-alunos-container">

        <!-- TÍTULOS -->
        <h1 class="lista-alunos-titulo">
            Lista de Alunos
        </h1>

        <p class="lista-alunos-subtitulo">
            Visualize todos os alunos cadastrados no sistema.
        </p>

        <!-- FILTROS -->
        <div class="lista-topo-filtros">

            <!-- BUSCA -->
            <div class="lista-campo-busca">

                <span class="material-icons">
                    search
                </span>

                <input
                    type="text"
                    id="buscarAluno"
                    placeholder="Buscar aluno por nome, bairro ou turma..."
                >

            </div>

            <!-- SÉRIE -->
            <select id="filtroSerie">

                <option value="">
                    Série - Todas
                </option>

                <option value="1º">
                    1º
                </option>

                <option value="2º">
                    2º
                </option>

                <option value="3º">
                    3º
                </option>

            </select>

            <!-- CURSO -->
            <select id="filtroCurso">

                <option value="">
                    Curso - Todos
                </option>

                <option value="Informática">
                    Informática
                </option>

                <option value="Desenvolvimento de Sistemas">
                    Desenvolvimento de Sistemas
                </option>

            </select>

            <!-- PDF -->
            <button class="lista-btn-pdf">

                <span class="material-icons">
                    picture_as_pdf
                </span>

                Gerar PDF

            </button>

        </div>

        <!-- TABELA -->
        <div class="table-responsive">

            <table class="lista-tabela" id="tabelaLista">

                <thead>

                    <tr>

                        <th>Nome do Aluno</th>
                        <th>Série</th>
                        <th>Curso</th>
                        <th>Onde Mora</th>
                        <th>Ações</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>Nayra Souza Silva</td>
                        <td>1º</td>
                        <td>Informática</td>
                        <td>Centro</td>

                        <td>

                            <div class="lista-acoes">

                                <button class="lista-btn-acao lista-btn-azul">

                                    <span class="material-icons">
                                        visibility
                                    </span>

                                </button>

                                <button class="lista-btn-acao lista-btn-amarelo">

                                    <span class="material-icons">
                                        edit
                                    </span>

                                </button>

                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td>Deyse Oliveira Santos</td>
                        <td>3º</td>
                        <td>Desenvolvimento de Sistemas</td>
                        <td>Venâncio</td>

                        <td>

                            <div class="lista-acoes">

                                <button class="lista-btn-acao lista-btn-azul">

                                    <span class="material-icons">
                                        visibility
                                    </span>

                                </button>

                                <button class="lista-btn-acao lista-btn-amarelo">

                                    <span class="material-icons">
                                        edit
                                    </span>

                                </button>

                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td>Thaís Lima Rodrigues</td>
                        <td>3º</td>
                        <td>Desenvolvimento de Sistemas</td>
                        <td>Cidade Nova</td>

                        <td>

                            <div class="lista-acoes">

                                <button class="lista-btn-acao lista-btn-azul">

                                    <span class="material-icons">
                                        visibility
                                    </span>

                                </button>

                                <button class="lista-btn-acao lista-btn-amarelo">

                                    <span class="material-icons">
                                        edit
                                    </span>

                                </button>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <!-- RODAPÉ -->
        <div class="lista-rodape">

            <p>
                Mostrando 1 a 5 de 25 alunos
            </p>

            <div class="lista-paginacao">

                <button>
                    1
                </button>

                <button class="ativo">
                    2
                </button>

                <button>
                    3
                </button>

                <button>
                    4
                </button>

                <button>
                    5
                </button>

            </div>

        </div>

    </div>

</div>

<script src="lista.js"></script>

</body>
</html>
