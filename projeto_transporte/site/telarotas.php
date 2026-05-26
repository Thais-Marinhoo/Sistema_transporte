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

    <h1 class="titulo">Cadastro de Rotas</h1>
    <p class="sub">Gerencie pontos, ônibus e rotas do sistema.</p>

    <!-- Mensagens -->
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <div class="cadastro-container">

        <!-- CADASTRO PONTO -->
        <div class="rota-card">
            <h5 class="card-title">Cadastro de Ponto</h5>
            <form method="POST" action="">
                <label>Número do ponto</label>
                <input type="number" name="numero_ponto" placeholder="Digite o número" required>

                <label>Nome do ponto</label>
                <input type="text" name="nome_ponto" placeholder="Digite o nome do ponto" required>

                <label>Endereço</label>
                <input type="text" name="endereco" placeholder="Digite o endereço" required>

                <button class="btn-salvar" type="submit" name="salvar_ponto">Salvar Ponto</button>
            </form>
        </div>

        <!-- CADASTRO ÔNIBUS -->
        <div class="rota-card">
            <h5 class="card-title">Cadastro de Ônibus</h5>
            <form method="POST" action="">

                <label>Nome do motorista</label>
                <input type="text" name="motorista_m" placeholder="Digite o nome" required>

                <label>Nome da rota</label>
                <input type="text" name="nome_rota" placeholder="Digite o nome da rota" required>

                <label>Pontos por onde passa <small>(Segure CTRL para associar mais de um ponto)</small></label>
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

    <!-- TABELA DE PONTOS -->
    <div class="rota-card">
        <h5 class="card-title">Pontos Cadastrados</h5>
        <table class="tabela-rotas">
            <tr>
                <th>Nº Ponto</th>
                <th>Nome do Ponto</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
            <?php 
            mysqli_data_seek($pontos, 0);
            while ($ponto = mysqli_fetch_assoc($pontos)): ?>
            <tr>
                <td><?= $ponto['numero_ponto'] ?></td>
                <td><?= $ponto['nome_ponto'] ?></td>
                <td><?= $ponto['endereco'] ?></td>
                <td>
                    <a href="?deletar_ponto=<?= $ponto['id_ponto'] ?>" 
                       class="btn-action delete"
                       onclick="return confirm('Tem certeza que deseja excluir este ponto?')">
                        🗑 Excluir
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- TABELA DE ROTAS -->
        <!-- ROTAS CADASTRADAS -->
    <div class="rota-card">
        <h5 class="card-title">Rotas Cadastradas</h5>
        <table class="tabela-rotas">
            <tr>
                <th>Rota</th>
                <th>Motorista Principal</th>
                <th>Motorista Secundário</th>
                <th>Status Principal</th>
                <th>Status Secundário</th>
                <th>Pontos</th>
                <th>Ações</th>
            </tr>
            <?php while ($rota = mysqli_fetch_assoc($rotas)): ?>
            <tr>
                <td><?= $rota['nome_rota'] ?></td>
                <td><?= $rota['motorista_m'] ?></td>
                <td><?= $rota['motorista_t'] ?: '—' ?></td>
                <td><?= $rota['status'] ?></td>
                <td>
                    <?= !empty($rota['status_secundario']) ? $rota['status_secundario'] : '<span style="color:#888;">Não há</span>' ?>
                </td>
                <td><?= $rota['pontos_nomes'] ?: 'Nenhum ponto' ?></td>
                <td>
                    <a href="?deletar_rota=<?= $rota['id_rota'] ?>" 
                       class="btn-action delete"
                       onclick="return confirm('Tem certeza que deseja excluir esta rota?')">
                        🗑 Excluir
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

<script>
const turnoManha = document.getElementById('turnoManha');
const motoristaSecundario = document.getElementById('motoristaSecundario');

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
