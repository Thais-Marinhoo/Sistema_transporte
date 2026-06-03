<?php
include '../conexao.php';
include 'rotas_back.php'; 
// atualizarAlunos($conexao); // Removido para otimizar velocidade
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos - Rota Certa</title>
    
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        .material-icons {
            font-family: 'Material Icons' !important;
            font-size: 22px !important;
            display: inline-block;
            vertical-align: middle;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
            -webkit-font-smoothing: antialiased;
        }
        .lista-btn-acao, .btn-vermelho { text-decoration: none !important; }
    </style>

    <link rel="stylesheet" href="mstyle.css?v=4">
</head>
<body>

<?php include "menu.php"; ?>
<?php include 'alertas.php'; ?>

<div class="lista-alunos-page">
    <div class="lista-alunos-container">
        
        <h1 class="lista-alunos-titulo">Lista de Alunos</h1>
        <p class="lista-alunos-subtitulo">Visualize todos os alunos cadastrados no sistema.</p>
        
        <?php include 'alertas.php'; ?>
        
        <!-- FILTROS -->
        <div class="lista-topo-filtros">
            <div class="lista-campo-busca">
                <span class="material-icons">search</span>
                <input type="text" id="buscarAluno" placeholder="Buscar aluno por nome, bairro ou turma...">
            </div>

            <select id="filtroSerie">
                <option value="">Série - Todas</option>
                <option value="1">1º</option>
                <option value="2">2º</option>
                <option value="3">3º</option>
            </select>

            <select id="filtroCurso">
                <option value="">Curso - Todos</option>
                <option value="Informatica">Informática</option>
                <option value="DS">Desenvolvimento de Sistemas</option>
                <option value="Enfermagem">Enfermagem</option>
                <option value="Administracao">Administração</option>
            </select>

            <a href="subir_ano.php" class="link-pdf" onclick="return confirm('Deseja avançar todos os alunos de série? Os alunos do 3º ano serão removidos.')">
                <button class="lista-btn-pdf">
                    <span class="material-icons">school</span> Subir de Ano
                </button>
            </a>
        </div>

        <!-- TABELA -->
        <div class="table-responsive">
            <table class="lista-tabela" id="tabelaLista">
                <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Série</th>
                        <th>Curso</th>
                        <th>Endereço</th>
                        <th>Ponto Vinculado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Query unificada que busca as informações do ponto automaticamente
                $sql = "SELECT aluno.*, ponto.nome_ponto 
                        FROM aluno 
                        INNER JOIN ponto ON aluno.id_ponto = ponto.id_ponto 
                        ORDER BY aluno.id_aluno DESC";
                
                $resultado = mysqli_query($conexao, $sql);
                while($aluno = mysqli_fetch_assoc($resultado)){
                ?>
                    <tr>
                        <td><?php echo $aluno['nome']; ?></td>
                        <td><?php echo $aluno['serie']; ?>º</td>
                        <td>
                            <?php 
                                if($aluno['curso'] == 'Informatica') echo 'Informática';
                                elseif($aluno['curso'] == 'DS') echo 'Desenvolvimento de Sistemas';
                                elseif($aluno['curso'] == 'Enfermagem') echo 'Enfermagem';
                                elseif($aluno['curso'] == 'Administracao') echo 'Administração';
                                else echo $aluno['curso'];
                            ?>
                        </td>
                        <td><?php echo $aluno['endereco']; ?></td>
                        <td style="font-weight:600; color:#0b2c5f;">
                            <span class="material-icons" style="font-size:18px !important; color:#ffc107;">location_on</span>
                            <?php echo $aluno['nome_ponto']; ?>
                        </td>
                        <td>
                            <div class="lista-acoes">
                                <button class="lista-btn-acao lista-btn-azul btnVisualizar"
                                        data-id="<?php echo $aluno['id_aluno']; ?>"
                                        data-nome="<?php echo $aluno['nome']; ?>"
                                        data-serie="<?php echo $aluno['serie']; ?>º"
                                        data-curso="<?php echo $aluno['curso']; ?>"
                                        data-endereco="<?php echo $aluno['endereco']; ?>">
                                    <span class="material-icons">visibility</span>
                                </button>

                                <button class="lista-btn-acao lista-btn-amarelo btnEditar"
                                        data-id="<?php echo $aluno['id_aluno']; ?>"
                                        data-nome="<?php echo $aluno['nome']; ?>"
                                        data-serie="<?php echo $aluno['serie']; ?>"
                                        data-curso="<?php echo $aluno['curso']; ?>"
                                        data-endereco="<?php echo $aluno['endereco']; ?>">
                                    <span class="material-icons">edit</span>
                                </button>

                                <a href="excluir_aluno.php?id=<?php echo $aluno['id_aluno']; ?>" 
                                   class="lista-btn-acao btn-vermelho" 
                                   onclick="return confirm('Deseja excluir este aluno?')">
                                    <span class="material-icons">delete</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="lista-rodape">
            <p>Lista atualizada automaticamente</p>
            <div class="lista-paginacao">
                <button class="ativo">1</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VISUALIZAR -->
<div class="modal-custom" id="modalVisualizar">
    <div class="modal-box">
        <span class="fecharModal" id="fecharVisualizar">×</span>
        <h2>Visualizar Aluno</h2>
        <input type="text" id="viewNome" readonly>
        <input type="text" id="viewSerie" readonly>
        <input type="text" id="viewCurso" readonly>
        <input type="text" id="viewEndereco" readonly>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal-custom" id="modalEditar">
    <div class="modal-box">
        <span class="fecharModal" id="fecharEditar">×</span>
        <h2>Editar Aluno</h2>
        <form action="editar_aluno_back.php" method="POST">
            <input type="hidden" name="id" id="editId">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Nome:</label>
            <input type="text" name="nome" id="editNome">
            
            <label style="display:block; margin-bottom:5px; font-weight:600;">Série:</label>
            <select name="serie" id="editSerie" style="width:100%; height:52px; border:1px solid #ddd; border-radius:14px; padding-left:15px; margin-bottom:18px; background:#f9fafc;">
                <option value="1">1º</option>
                <option value="2">2º</option>
                <option value="3">3º</option>
            </select>

            <label style="display:block; margin-bottom:5px; font-weight:600;">Curso:</label>
            <select name="curso" id="editCurso" style="width:100%; height:52px; border:1px solid #ddd; border-radius:14px; padding-left:15px; margin-bottom:18px; background:#f9fafc;">
                <option value="Informatica">Informática</option>
                <option value="DS">Desenvolvimento de Sistemas</option>
                <option value="Enfermagem">Enfermagem</option>
                <option value="Administracao">Administração</option>
            </select>

            <label style="display:block; margin-bottom:5px; font-weight:600;">Endereço:</label>
            <input type="text" name="endereco" id="editEndereco">
            <button class="btn-painel">Salvar Alterações</button>
        </form>
    </div>
</div>

<script src="lista.js?v=4"></script>
</body>
</html>