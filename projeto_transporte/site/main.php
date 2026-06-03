<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
include '../conexao.php';

// Contagens reais do banco
$totalAlunos = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) as total FROM aluno"))['total'];
$totalRotas  = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) as total FROM rota"))['total'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Rota Certa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="mstyle.css">
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

    <!-- BOTÃO PDF -->
    <div style="margin-top: 30px;">
        <a href="gerar_pdf.php" target="_blank">
            <button class="btn-salvar" style="display:inline-flex; align-items:center; gap:10px; width:auto; padding: 0 30px;">
                <span class="material-icons" style="font-size:22px;">picture_as_pdf</span>
                Gerar PDF — Alunos por Ponto
            </button>
        </a>
    </div>

    <div class="mapa"></div>

</div>

</body>
</html>