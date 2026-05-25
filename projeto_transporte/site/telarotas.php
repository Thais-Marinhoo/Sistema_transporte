<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

/* PONTOS */
$sql_pontos = "SELECT * FROM ponto ORDER BY id_ponto DESC";
$result_pontos = mysqli_query($conexao, $sql_pontos);

/* ÔNIBUS */
$sql_onibus = "SELECT * FROM rota ORDER BY id_rota DESC";
$result_onibus = mysqli_query($conexao, $sql_onibus);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Rotas - Rota Certa</title>

    <link rel="stylesheet" href="mstyle.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="conteudo">

    <h1 class="titulo">Cadastro de Rotas</h1>
    <p class="sub">Gerencie pontos, ônibus e rotas do sistema.</p>

    <!-- TOPO -->
    <div class="cadastro-container">
  
        <!-- CADASTRO PONTO -->
        <div class="rota-card">

            <h5 class="card-title">Cadastro de Ponto</h5>

            <form method="POST" action="salvar_ponto.php">

                <label>Número do ponto</label>
                <input type="number" name="numero_ponto" placeholder="Digite o número">

                <label>Nome do ponto</label>
                <input type="text" name="nome_ponto" placeholder="Digite o nome do ponto">

                <label>Endereço</label>
                <input type="text" name="endereco" placeholder="Digite o endereço">

                <button class="btn-salvar" type="submit">
                    Salvar Ponto
                </button>

            </form>

        </div>

        <!-- CADASTRO ÔNIBUS -->
        <div class="rota-card">

            <h5 class="card-title">Cadastro de Ônibus</h5>

            <form method="POST" action="salvar_onibus.php">

                <label>Nome do motorista</label>
                <input type="text" name="motorista" placeholder="Digite o nome">

                <label>Nome da rota</label>
                <input type="text" name="rota" placeholder="Digite o nome da rota">

              <label>Pontos por onde passa</label>

<select class="rota-select" name="pontos">

    <option selected disabled>
        Selecione um ponto
    </option>

    <option>1 - Praça da Matriz</option>
    <option>2 - Centro</option>
    <option>3 - Rodoviária</option>
    <option>4 - Escola</option>

</select>

                <label>Status do ônibus</label>
                <input
                    type="text"
                    name="terceirizado"
                    placeholder="Ex: Terceirizado ou Não"
                >

                <label>Turno</label>
                <select class="rota-select" name="turno">
                    <option>Manhã</option>
                    <option>Tarde</option>
                    <option>Integral</option>
                </select>

                <label>Ônibus passa APENAS pela manhã?</label>

                <select class="rota-select" id="turnoManha">
                    <option value="nao">Não</option>
                    <option value="sim">Sim</option>
                </select>

                <!-- MOTORISTA SECUNDÁRIO -->

                <div id="motoristaSecundario">

                    <label>Nome do motorista secundário</label>

                    <input
                        type="text"
                        name="motorista_secundario"
                        placeholder="Digite o nome"
                    >

                    <label>Status do ônibus secundário</label>

                    <input
                        type="text"
                        name="terceirizado_secundario"
                        placeholder="Ex: Terceirizado ou Não"
                    >

                </div>

                <button class="btn-salvar" type="submit">
                    Salvar Ônibus
                </button>

            </form>

        </div>

    </div>

    <!-- BUSCAR PONTO -->
    <div class="rota-card">

        <div class="topo-tabela">

            <h5 class="card-title">Buscar Ponto</h5>

            <input
                class="busca"
                type="text"
                placeholder="Buscar ponto"
            >

        </div>

        <table class="tabela-rotas">

            <tr>
                <th>Nº Ponto</th>
                <th>Nome do Ponto</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>

            <?php while($ponto = mysqli_fetch_assoc($result_pontos)) { ?>

            <tr>

                <td><?= $ponto['id_ponto'] ?></td>

                <td><?= $ponto['nome_ponto'] ?></td>

                <td>
                    <?= isset($ponto['endereco']) ? $ponto['endereco'] : '--' ?>
                </td>

                <td>

                    <div class="lista-acoes">

                        <button class="lista-btn-acao lista-btn-azul">
                           <span class="material-icons">delete</span>
                        </button>

                        <button class="lista-btn-acao lista-btn-amarelo">
                            <span class="material-icons">edit</span>
                        </button>

                    </div>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

    <!-- ROTAS CADASTRADAS -->
    <div class="rota-card">

        <div class="topo-tabela">

            <h5 class="card-title">Rotas Cadastradas</h5>

        </div>

        <table class="tabela-rotas">

            <tr>
                <th>Rota</th>
                <th>Descrição</th>
                <th>Motorista</th>
                <th>Status</th>
                <th>Turno</th>
                <th>Ações</th>
            </tr>

            <?php while($bus = mysqli_fetch_assoc($result_onibus)) { ?>

            <tr>

                <td>
                    <?= isset($bus['rota']) ? $bus['rota'] : '--' ?>
                </td>

                <td>
                    <?= isset($bus['pontos']) ? $bus['pontos'] : '--' ?>
                </td>

                <td>
                    <?= $bus['motoristas'] ?>
                </td>

                <td>
                    <?= isset($bus['terceirizado']) ? $bus['terceirizado'] : '--' ?>
                </td>

                <td>
                    <?= isset($bus['turno']) ? $bus['turno'] : '--' ?>
                </td>

                <td>

                    <div class="lista-acoes">

                        <button class="lista-btn-acao lista-btn-azul">
                            <span class="material-icons">delete</span>
                        </button>

                        <button class="lista-btn-acao lista-btn-amarelo">
                            <span class="material-icons">edit</span>
                        </button>

                    </div>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

<script>

const turnoManha = document.getElementById('turnoManha');
const motoristaSecundario = document.getElementById('motoristaSecundario');

motoristaSecundario.style.display = 'none';

turnoManha.addEventListener('change', function(){

    if(this.value === 'sim'){
        motoristaSecundario.style.display = 'block';
    } else {
        motoristaSecundario.style.display = 'none';
    }

});

</script>

</body>
</html>