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

<?php if(isset($_GET['status']) && $_GET['status'] == 'endereco'): ?>
        <div style="display: flex; align-items: center; gap: 12px; background-color: #fff5f5; border-left: 4px solid #e53e3e; border-radius: 6px; padding: 16px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: system-ui, -apple-system, sans-serif;">
        <!-- Ícone do Material Icons que você já tem importado no seu <head> -->
        <span class="material-icons" style="color: #e53e3e; font-size: 24px;">error_outline</span>
        
        <p style="margin: 0; color: #c53030; font-size: 0.95rem; line-height: 1.5; font-weight: 500;">
            Não foi possível encontrar as coordenadas para o endereço digitado. Verifique a Ortografia ou se escreveu o nome da forma indicada.
        </p>
    </div>
<?php endif; ?>

<?php if(isset($_GET['status']) && $_GET['status'] == 'sem_pontos'): ?>
        <div style="display: flex; align-items: center; gap: 12px; background-color: #fff5f5; border-left: 4px solid #e53e3e; border-radius: 6px; padding: 16px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); font-family: system-ui, -apple-system, sans-serif;">
        <!-- Ícone do Material Icons que você já tem importado no seu <head> -->
        <span class="material-icons" style="color: #e53e3e; font-size: 24px;">error_outline</span>
        
        <p style="margin: 0; color: #c53030; font-size: 0.95rem; line-height: 1.5; font-weight: 500;">
            Não foi possível encontrar pontos de coleta cadastrados. Por favor, cadastre pontos de coleta <strong>antes de cadastrar alunos</strong>.
        </p>
    </div>
<?php endif; ?>

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