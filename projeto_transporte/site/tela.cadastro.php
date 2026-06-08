<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit();
}

include '../conexao.php';

// Busca todos os nomes de alunos já cadastrados para validação no JS
$nomesCadastrados = [];
$resNomes = mysqli_query($conexao, "SELECT nome FROM aluno");
while ($row = mysqli_fetch_assoc($resNomes)) {
    $nomesCadastrados[] = mb_strtolower(trim($row['nome']), 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Alunos</title>

    <link rel="stylesheet" href="mstyle.css?v=1000">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="conteudo">

    <div class="topo-cadastro">
        <h1 class="titulo">Cadastro de Alunos</h1>

        <button type="button" id="btnAlerta" class="btn-alerta">
            Informação Importante
        </button>
    </div>

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

<script>
// Nomes já cadastrados no banco — usados pelo cadastro.js para bloquear duplicatas
const NOMES_CADASTRADOS = <?php echo json_encode($nomesCadastrados); ?>;
</script>
<script src="cadastro.js?v=1"></script>

<script src="alerta_botao.js"></script>

</body>
</html>