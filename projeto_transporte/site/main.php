<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
include '../conexao.php';

// Contagens reais do banco (já existentes)
$totalAlunos = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) as total FROM aluno"))['total'];
$totalRotas  = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) as total FROM rota"))['total'];

// ========== NOVAS CONSULTAS PARA OS GRÁFICOS ==========
// 1. Alunos por curso
$sqlCurso = "SELECT curso, COUNT(*) as total FROM aluno GROUP BY curso";
$resCurso = mysqli_query($conexao, $sqlCurso);
$cursos = [];
$totaisCurso = [];
while ($row = mysqli_fetch_assoc($resCurso)) {
    $cursos[] = $row['curso'];
    $totaisCurso[] = (int)$row['total'];
}

// 2. Alunos por série
$sqlSerie = "SELECT serie, COUNT(*) as total FROM aluno GROUP BY serie ORDER BY serie";
$resSerie = mysqli_query($conexao, $sqlSerie);
$series = [];
$totaisSerie = [];
while ($row = mysqli_fetch_assoc($resSerie)) {
    $series[] = $row['serie'] . "º Ano";
    $totaisSerie[] = (int)$row['total'];
}

// 3. Top 10 pontos com mais alunos
$sqlPonto = "SELECT p.nome_ponto, COUNT(a.id_aluno) as total_alunos
             FROM ponto p
             LEFT JOIN aluno a ON a.id_ponto = p.id_ponto
             GROUP BY p.id_ponto
             ORDER BY total_alunos DESC
             LIMIT 10";
$resPonto = mysqli_query($conexao, $sqlPonto);
$pontos = [];
$alunosPorPonto = [];
while ($row = mysqli_fetch_assoc($resPonto)) {
    $pontos[] = $row['nome_ponto'];
    $alunosPorPonto[] = (int)$row['total_alunos'];
}

// 4. Quantidade de pontos por rota
$sqlRotaPonto = "SELECT r.nome_rota, COUNT(rp.id_ponto) as total_pontos
                 FROM rota r
                 LEFT JOIN rota_ponto rp ON r.id_rota = rp.id_rota
                 GROUP BY r.id_rota
                 ORDER BY total_pontos DESC";
$resRotaPonto = mysqli_query($conexao, $sqlRotaPonto);
$rotas = [];
$pontosPorRota = [];
while ($row = mysqli_fetch_assoc($resRotaPonto)) {
    $rotas[] = $row['nome_rota'];
    $pontosPorRota[] = (int)$row['total_pontos'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Rota Certa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="mstyle.css">
    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carrega a biblioteca corechart
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawAllCharts);

        function drawAllCharts() {
            drawChartCursos();
            drawChartSeries();
            drawChartPontos();
            drawChartRotas();
        }

        // Gráfico 1: Alunos por Curso (Pizza)
        function drawChartCursos() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Curso');
            data.addColumn('number', 'Alunos');
            data.addRows([
                <?php
                for ($i = 0; $i < count($cursos); $i++) {
                    echo "['{$cursos[$i]}', {$totaisCurso[$i]}]";
                    if ($i < count($cursos)-1) echo ",";
                }
                ?>
            ]);

            var options = {
                title: 'Alunos por Curso',
                is3D: true,
                width: '100%',
                height: 400,
                legend: { position: 'right' }
            };
            var chart = new google.visualization.PieChart(document.getElementById('chart_cursos'));
            chart.draw(data, options);
        }

        // Gráfico 2: Alunos por Série (Barras)
        function drawChartSeries() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Série');
            data.addColumn('number', 'Quantidade');
            data.addRows([
                <?php
                for ($i = 0; $i < count($series); $i++) {
                    echo "['{$series[$i]}', {$totaisSerie[$i]}]";
                    if ($i < count($series)-1) echo ",";
                }
                ?>
            ]);

            var options = {
                title: 'Alunos por Série',
                width: '100%',
                height: 400,
                bars: 'vertical',
                colors: ['#1e88e5']
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_series'));
            chart.draw(data, options);
        }

        // Gráfico 3: Top 10 Pontos com mais alunos (Barras horizontais)
        function drawChartPontos() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Ponto');
            data.addColumn('number', 'Alunos');
            data.addRows([
                <?php
                for ($i = 0; $i < count($pontos); $i++) {
                    $nome = addslashes($pontos[$i]); // evitar problemas com aspas
                    echo "['{$nome}', {$alunosPorPonto[$i]}]";
                    if ($i < count($pontos)-1) echo ",";
                }
                ?>
            ]);

            var options = {
                title: 'Top 10 Pontos com mais Alunos',
                width: '100%',
                height: 500,
                bars: 'horizontal',
                colors: ['#ff9800']
            };
            var chart = new google.visualization.BarChart(document.getElementById('chart_pontos'));
            chart.draw(data, options);
        }

        // Gráfico 4: Quantidade de pontos por rota (Colunas)
        function drawChartRotas() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Rota');
            data.addColumn('number', 'Pontos na Rota');
            data.addRows([
                <?php
                for ($i = 0; $i < count($rotas); $i++) {
                    $nomeRota = addslashes($rotas[$i]);
                    echo "['{$nomeRota}', {$pontosPorRota[$i]}]";
                    if ($i < count($rotas)-1) echo ",";
                }
                ?>
            ]);

            var options = {
                title: 'Quantidade de Pontos por Rota',
                width: '100%',
                height: 400,
                legend: { position: 'none' },
                colors: ['#4caf50']
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_rotas'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="conteudo">

    <h1 class="titulo">Dashboard</h1>

    <div class="cards">
        <div class="card-info">
            <span class="material-icons icon">groups</span>
            <div>
                <p>Total de alunos</p>
                <h2><?= $totalAlunos ?></h2>
            </div>
        </div>

        <div class="card-info">
            <span class="material-icons icon">route</span>
            <div>
                <p>Total de rotas</p>
                <h2><?= $totalRotas ?></h2>
            </div>
        </div>

        <div class="card-info">
            <span class="material-icons icon">directions_bus</span>
            <div>
                <p>Total de ônibus</p>
                <h2><?= $totalRotas ?></h2>
            </div>
        </div>
    </div>

    <!-- BOTÃO PDF (original, sem alteração) -->
    <div style="margin-top: 30px;">
        <a href="gerar_pdf.php" target="_blank">
            <button class="btn-salvar" style="display:inline-flex; align-items:center; gap:10px; width:auto; padding: 0 30px;">
                <span class="material-icons" style="font-size:22px;">picture_as_pdf</span>
                Gerar PDF — Alunos por Ponto
            </button>
        </a>
    </div>

    <!-- ========== SEÇÃO DOS GRÁFICOS (NOVO) ========== -->
    <div style="margin-top: 50px;">
        <h2 class="titulo" style="font-size: 1.8rem; margin-bottom: 20px;">📈 Estatísticas do Sistema</h2>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div id="chart_cursos" style="border: 1px solid #ddd; border-radius: 8px; background: #fff; padding: 10px;"></div>
            </div>
            <div class="col-md-6 mb-4">
                <div id="chart_series" style="border: 1px solid #ddd; border-radius: 8px; background: #fff; padding: 10px;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div id="chart_pontos" style="border: 1px solid #ddd; border-radius: 8px; background: #fff; padding: 10px;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div id="chart_rotas" style="border: 1px solid #ddd; border-radius: 8px; background: #fff; padding: 10px;"></div>
            </div>
        </div>
    </div>
    <!-- ========== FIM DOS GRÁFICOS ========== -->

    <div class="mapa"></div>

</div>

</body>
</html>