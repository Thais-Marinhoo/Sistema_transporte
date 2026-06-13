<?php
include 'rotas_back.php';


if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

$pontos = listarPontos($conexao);
$rotas  = listarRotas($conexao);
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

    <div class="topo-cadastro">
        <h1 class="titulo">Cadastro de Rotas</h1>
        <button type="button" id="btnAlerta" class="btn-alerta">
            Informação Importante
        </button>
    </div>
    
    <p class="sub">Gerencie pontos, ônibus e rotas do sistema.</p>
    <!-- Mensagens do Sistema -->
    <?php include 'alertas.php'; ?>
    

    <div class="cadastro-container">

        <!-- CADASTRO PONTO -->
        <div class="rota-card">
            <h5 class="card-title">Cadastro de Ponto</h5>
            <form method="POST" action="">
                <label>Número do ponto</label>
                <input type="number" name="numero_ponto" placeholder="Digite o número">

                <label>Nome do ponto</label>
                <input type="text" name="nome_ponto" placeholder="Digite o nome do ponto">

                <label>Endereço</label>
                <input type="text" name="endereco" placeholder="Digite o endereço">

                <button class="btn-salvar" type="submit" name="salvar_ponto">Salvar Ponto</button>
            </form>
        </div>

        <!-- CADASTRO ÔNIBUS -->
        <div class="rota-card">
            <h5 class="card-title">Cadastro de Ônibus</h5>
            <form method="POST" action="">

                <label>Nome do motorista</label>
                <input type="text" name="motorista_m" placeholder="Digite o nome">

                <label>Nome da rota</label>
                <input type="text" name="nome_rota" placeholder="Digite o nome da rota">

                <label>Pontos por onde passa <small>(Segure CTRL para vários)</small></label>
                <select name="pontos[]" class="rota-select" multiple size="6">
                    <?php 
                    mysqli_data_seek($pontos, 0);
                    while ($ponto = mysqli_fetch_assoc($pontos)): ?>
                        <option value="<?= $ponto['id_ponto'] ?>">
                            <?= $ponto['numero_ponto'] ?> - <?= $ponto['nome_ponto'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label>Status do ônibus</label>
                <input type="text" name="status" placeholder="Ex: Terceirizado ou Não" required>

                <label>Ônibus passa APENAS pela manhã?</label>
                <select class="rota-select" id="turnoManha">
                    <option value="nao">Não</option>
                    <option value="sim">Sim</option>
                </select>

                <div id="motoristaSecundario" style="display: none;">
                    <label>Nome do motorista secundário</label>
                    <input type="text" name="motorista_t" placeholder="Digite o nome">

                    <label>Status do ônibus secundário</label>
                    <input type="text" name="terceirizado_secundario" placeholder="Ex: Terceirizado ou Não">
                </div>

                <button class="btn-salvar" type="submit" name="salvar_rota">Salvar Ônibus</button>
            </form>
        </div>

    </div>

    <!-- ====================== TABELA DE PONTOS ====================== -->
    <div class="rota-card">
        <h5 class="card-title">Pontos Cadastrados</h5>
        
        <div class="lista-campo-busca">
            <span class="material-icons">search</span>
            <input type="text" id="buscaPonto" placeholder="Buscar ponto por nome ou endereço...">
        </div>

        <table class="tabela-rotas" id="tabelaPontos">
            <tr>
                <th>Nº Ponto</th>
                <th>Nome do Ponto</th>
                <th>Endereço</th>
                <th style="text-align: center;">Ações</th>
            </tr>
            <?php mysqli_data_seek($pontos, 0); while ($ponto = mysqli_fetch_assoc($pontos)): ?>
            <tr>
                <td><?= $ponto['numero_ponto'] ?></td>
                <td><?= $ponto['nome_ponto'] ?></td>
                <td><?= $ponto['endereco'] ?></td>
                <td>
                    <div class="lista-acoes">
                        <!-- Editar -->
                        <button
    type="button"
    class="lista-btn-acao lista-btn-amarelo btnEditarPonto"
    data-id="<?= htmlspecialchars($ponto['id_ponto']) ?>"
    data-numero="<?= htmlspecialchars($ponto['numero_ponto']) ?>"
    data-nome="<?= htmlspecialchars($ponto['nome_ponto']) ?>"
    data-endereco="<?= htmlspecialchars($ponto['endereco']) ?>"
>
    <span class="material-icons">edit</span>
</button>         <!-- Excluir -->
                        <a href="?deletar_ponto=<?= $ponto['id_ponto'] ?>" class="lista-btn-acao lista-btn-vermelho" 
                           onclick="return confirm('Tem certeza que deseja excluir este ponto?')" title="Excluir">
                            <span class="material-icons">delete</span>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>


    <!-- ====================== TABELA DE ROTAS ====================== -->
    <div class="rota-card">
        <h5 class="card-title">Rotas Cadastradas</h5>
        
        <div class="lista-campo-busca">
            <span class="material-icons">search</span>
            <input type="text" id="buscaRota" placeholder="Buscar por rota, motorista ou ponto...">
        </div>

        <table class="tabela-rotas" id="tabelaRotas">
            <tr>
                <th>Rota</th>
                <th>Motorista Principal</th>
                <th>Motorista Secundário</th>
                <th>Status Principal</th>
                <th>Status Secundário</th>
                <th>Pontos</th>
                <th style="text-align: center;">Ações</th>
            </tr>
            <?php while ($rota = mysqli_fetch_assoc($rotas)): ?>
            <tr>
                <td><?= $rota['nome_rota'] ?></td>
                <td><?= $rota['motorista_m'] ?></td>
                <td><?= $rota['motorista_t'] ?: '—' ?></td>
                <td><?= $rota['status'] ?></td>
                <td><?= !empty($rota['status_tarde']) ? $rota['status_tarde'] : '<span style="color:#888;">Não há</span>' ?></td>
                <td><?= $rota['pontos_nomes'] ?: 'Nenhum ponto' ?></td>
                <td>
                    <div class="lista-acoes">
                        <!-- Editar -->
                        <button
    type="button"
    class="lista-btn-acao lista-btn-amarelo btnEditarRota"
    data-id="<?= htmlspecialchars($rota['id_rota']) ?>"
    data-rota="<?= htmlspecialchars($rota['nome_rota']) ?>"
    data-motorista="<?= htmlspecialchars($rota['motorista_m']) ?>"
    data-motorista2="<?= htmlspecialchars($rota['motorista_t']) ?>"
    data-status="<?= htmlspecialchars($rota['status']) ?>"
    data-status2="<?= htmlspecialchars($rota['status_tarde']) ?>"
>
    <span class="material-icons">edit</span>
</button>
                        <!-- Excluir -->
                        <a href="?deletar_rota=<?= $rota['id_rota'] ?>" class="lista-btn-acao lista-btn-vermelho" 
                           onclick="return confirm('Tem certeza que deseja excluir esta rota?')" title="Excluir">
                            <span class="material-icons">delete</span>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

<!-- MODAL EDITAR PONTO -->
<div class="modal-custom" id="modalEditarPonto" style="display:none;">

    <div class="modal-box">

        <span id="fecharEditarPonto" class="fecharModal">×</span>

        <h2>Editar Ponto</h2>

        <form action="editar_ponto_back.php" method="POST">

            <input type="hidden" name="id" id="editPontoId">

            <label>Número</label>
            <input type="number" name="numero_ponto" id="editNumeroPonto">

            <label>Nome</label>
            <input type="text" name="nome_ponto" id="editNomePonto">

            <label>Endereço</label>
            <input type="text" name="endereco" id="editEnderecoPonto">

            <button type="submit" class="btn-painel">
                Salvar
            </button>

        </form>

    </div>

</div>
<div class="modal-custom" id="modalEditarRota" style="display:none;">

    <div class="modal-box">

        <span id="fecharEditarRota" class="fecharModal">×</span>

        <h2>Editar Rota</h2>

        <form action="editar_rota_back.php" method="POST">

            <input type="hidden" name="id" id="editRotaId">

            <label>Nome da Rota</label>
            <input type="text" name="nome_rota" id="editNomeRota">

            <label>Motorista Principal</label>
            <input type="text" name="motorista_m" id="editMotoristaRota">

            <label>Motorista Secundário</label>
            <input type="text" name="motorista_t" id="editMotorista2Rota">

            <label>Status Principal</label>
            <input type="text" name="status" id="editStatusRota">

            <label>Status Secundário</label>
            <input type="text" name="status_tarde" id="editStatus2Rota">

            <button type="submit" class="btn-painel">
                Salvar Alterações
            </button>

        </form>

    </div>

</div>

<!-- POPUP ALERTAS -->
<div id="modalAlerta" class="modal-custom">

    <div class="modal-box">

        <span class="fecharModal">&times;</span>

        <h2>Alertas</h2>

        <p style="line-height:1.8;color:#444;">
            O sistema não tem uma Geolocalização exata pra todos os lugares de Crateús.
            Logo nosso sistema pode nem sempre permitir o cadastro de certos endereços.
            <br><br>

            Exemplo: Dom Fragoso. As ruas da Dom Fragoso não são cadastradas em API de geolocalização ainda,
            portanto qualquer aluno que more na Residencial Dom Fragoso deverá ser
            cadastrado com o seguinte endereço:
            <strong>R. Homero Fontenele, 1350</strong>.
            <br><br>

            Por isso se recomenda usar um local aproximado para se referir ao ponto
            ou ao local dos alunos caso o endereço exato não seja aceito.
            <br><br>

            <strong>
                Recomenda-se sempre digitar os endereços dos alunos e pontos da seguinte forma:
                RUA, NÚMERO.
            </strong>
        </p>

        <button class="btn-painel" id="fecharAlerta">
            Entendi
        </button>

    </div>

</div>

<script src="rotas.js"></script>
<script src="alerta_botao.js"></script>

</body>
</html>