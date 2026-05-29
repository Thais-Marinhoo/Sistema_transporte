
<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Lista de Alunos - Rota Certa
    </title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Ícones -->
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet"
    >

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="mstyle.css?v=999"
    >

</head>

<body>

<?php include "menu.php"; ?>

<div class="lista-alunos-page">

    <div class="lista-alunos-container">

        <!-- TÍTULO -->
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

                <option value="1">
                    1º
                </option>

                <option value="2">
                    2º
                </option>

                <option value="3">
                    3º
                </option>

            </select>

            <!-- CURSO -->
            <select id="filtroCurso">

                <option value="">
                    Curso - Todos
                </option>

                <option value="informatica">
                    Informática
                </option>

                <option value="ds">
                    Desenvolvimento de Sistemas
                </option>

            </select>

            <!-- PDF -->
            <a
                href="gerar_pdf.php"
                target="_blank"
                class="link-pdf"
            >

                <button class="lista-btn-pdf">

                    <span class="material-icons">
                        picture_as_pdf
                    </span>

                    Gerar PDF

                </button>

            </a>

        </div>

        <!-- TABELA -->
        <div class="table-responsive">

            <table
                class="lista-tabela"
                id="tabelaLista"
            >

                <thead>

                    <tr>

                        <th>
                            Nome do Aluno
                        </th>

                        <th>
                            Série
                        </th>

                        <th>
                            Curso
                        </th>

                        <th>
                            Onde Mora
                        </th>

                        <th>
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $sql = "
                    SELECT *
                    FROM aluno
                    ORDER BY id_aluno DESC
                ";

                $resultado =
                mysqli_query($conexao, $sql);

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

                        <td>

                            <div class="lista-acoes">

                                <!-- VISUALIZAR -->
                                <button

                                    class="
                                        lista-btn-acao
                                        lista-btn-azul
                                        btnVisualizar
                                    "

                                    data-id="<?php echo $aluno['id_aluno']; ?>"

                                    data-nome="<?php echo $aluno['nome']; ?>"

                                    data-serie="<?php echo $aluno['serie']; ?>"

                                    data-curso="<?php echo $aluno['curso']; ?>"

                                    data-endereco="<?php echo $aluno['endereco']; ?>"
                                >

                                    <span class="material-icons">
                                        visibility
                                    </span>

                                </button>

                                <!-- EDITAR -->
                                <button

                                    class="
                                        lista-btn-acao
                                        lista-btn-amarelo
                                        btnEditar
                                    "

                                    data-id="<?php echo $aluno['id_aluno']; ?>"

                                    data-nome="<?php echo $aluno['nome']; ?>"

                                    data-serie="<?php echo $aluno['serie']; ?>"

                                    data-curso="<?php echo $aluno['curso']; ?>"

                                    data-endereco="<?php echo $aluno['endereco']; ?>"
                                >

                                    <span class="material-icons">
                                        edit
                                    </span>

                                </button>

                                <!-- EXCLUIR -->
                                <a

                                    href="
                                        excluir_aluno.php?id=
                                        <?php echo $aluno['id_aluno']; ?>
                                    "

                                    class="
                                        lista-btn-acao
                                        btn-vermelho
                                    "

                                    onclick="
                                        return confirm(
                                            'Deseja excluir este aluno?'
                                        )
                                    "
                                >

                                    <span class="material-icons">
                                        delete
                                    </span>

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

        <!-- RODAPÉ -->
        <div class="lista-rodape">

            <p>
                Lista atualizada automaticamente
            </p>

            <div class="lista-paginacao">

                <button class="ativo">
                    1
                </button>

            </div>

        </div>

    </div>

</div>

<!-- MODAL VISUALIZAR -->

<div
    class="modal-custom"
    id="modalVisualizar"
>

    <div class="modal-box">

        <span
            class="fecharModal"
            id="fecharVisualizar"
        >
            ×
        </span>

        <h2>
            Visualizar Aluno
        </h2>

        <input
            type="text"
            id="viewNome"
            readonly
        >

        <input
            type="text"
            id="viewSerie"
            readonly
        >

        <input
            type="text"
            id="viewCurso"
            readonly
        >

        <input
            type="text"
            id="viewEndereco"
            readonly
        >

    </div>

</div>

<!-- MODAL EDITAR -->

<div
    class="modal-custom"
    id="modalEditar"
>

    <div class="modal-box">

        <span
            class="fecharModal"
            id="fecharEditar"
        >
            ×
        </span>

        <h2>
            Editar Aluno
        </h2>

        <form
            action="editar_aluno_back.php"
            method="POST"
        >

            <input
                type="hidden"
                name="id"
                id="editId"
            >

            <input
                type="text"
                name="nome"
                id="editNome"
            >

            <input
                type="text"
                name="serie"
                id="editSerie"
            >

            <input
                type="text"
                name="curso"
                id="editCurso"
            >

            <input
                type="text"
                name="endereco"
                id="editEndereco"
            >

            <button class="btn-painel">

                Salvar Alterações

            </button>

        </form>

    </div>

</div>

<script src="lista.js?v=999"></script>

</body>
</html>

