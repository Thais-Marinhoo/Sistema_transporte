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
    <title>Dashboard - Rota Certa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- CSS da Home -->
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
                <h2>350</h2>
            </div>
        </div>

        <div class="card-info">
            <span class="material-icons icon">route</span>
            <div>
                <p>Total de rotas</p>
                <h2>9</h2>
            </div>
        </div>

        <div class="card-info">
            <span class="material-icons icon">directions_bus</span>
            <div>
                <p>Ônibus ativos</p>
                <h2>15</h2>
            </div>
        </div>
    </div>

    <div class="mapa">
       
    </div>

</div>

</body>
</html>