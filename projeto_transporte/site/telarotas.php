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
    <title>Rotas - Rota Certa</title>

    <!-- CSS -->
    <link rel="stylesheet" href="mstyle.css">

    <!-- Ícones -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="conteudo">

    <h1 class="titulo">Cadastro de Rotas</h1>
    <p class="sub">Cadastre rotas e ônibus no sistema.</p>

    <!-- CONTAINER LADO A LADO -->
    <div class="cadastro-container">

        <!-- Cadastro Pontos -->
        <div class="rota-card">
            <div class="card-body">

                <h5 class="card-title">Cadastro de pontos</h5>

                <label>Nome do ponto</label>
                <input type="text" placeholder="Digite o nome do ponto">

                <button class="btn-salvar mt-4">
                    Salvar Rota
                </button>

            </div>
        </div>

        <!-- Cadastro Ônibus -->
        <div class="rota-card">
            <div class="card-body">

                <h5 class="card-title">Cadastro de Ônibus</h5>

                <label>Nome do motorista</label>
                <input type="text" placeholder="Digite o nome">

                <label class="mt-3">Selecionar rota</label>

                <select class="rota-select">
                    <option>Selecione uma rota</option>
                    <option>Rota Centro</option>
                    <option>Rota Norte</option>
                </select>

                <button class="btn-salvar mt-4">
                    Salvar Ônibus
                </button>

            </div>
        </div>

    </div>

    <!-- TABELA -->
    <div class="rota-card mt-4">

        <div class="card-body">

            <div class="topo-tabela">

                <h5 class="card-title">
                    Rotas Cadastradas
                </h5>

                <input
                    class="busca"
                    type="text"
                    placeholder="Buscar rota ou motorista"
                >

            </div>

            <table class="tabela-rotas">

                <tr>
                    <th>Rota</th>
                    <th>Descrição</th>
                    <th>Motorista</th>
                    <th>Ações</th>
                </tr>

                <tr>
                    <td>Rota Centro</td>
                    <td>Centro e bairros próximos</td>
                    <td>João Silva</td>
                    <td>
                        <div class="lista-acoes">

                            <button class="lista-btn-acao lista-btn-azul" title="Visualizar">
                                <span class="material-icons">visibility</span>
                            </button>

                            <button class="lista-btn-acao lista-btn-amarelo" title="Editar">
                                <span class="material-icons">edit</span>
                            </button>

                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Rota Norte</td>
                    <td>Região norte da cidade</td>
                    <td>Maria Souza</td>
                    <td>
                        <div class="lista-acoes">

                            <button class="lista-btn-acao lista-btn-azul" title="Visualizar">
                                <span class="material-icons">visibility</span>
                            </button>

                            <button class="lista-btn-acao lista-btn-amarelo" title="Editar">
                                <span class="material-icons">edit</span>
                            </button>

                        </div>
                    </td>
                </tr>

            </table>

            <!-- PAGINAÇÃO PADRÃO LISTA ALUNOS -->
            <div class="lista-paginacao">

                <button>‹</button>

                <button class="ativo">1</button>
                <button>2</button>
                <button>3</button>

                <button>›</button>

            </div>

        </div>

    </div>

</div>

</body>
</html>