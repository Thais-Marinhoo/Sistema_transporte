<?php
session_start();
include '../conexao.php';


$conta = busca_conta($conexao, $_GET['id_usuario']);

function busca_conta($conexao, $id) {
    $sql = "SELECT * FROM users WHERE id_usuario = $id";
    $result = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Conta</title>

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">

   
    <link rel="stylesheet" href="../style.css?v=2">
</head>

<body class="bg-site">

<div class="container-admin">

    <img src="../logo.png" class="logo-topo">

    <div class="card-admin">

        <div class="admin-header">
            <div>
                <h1>Editar Conta</h1>
                <span class="sub">Altere os dados da conta</span>
            </div>
        </div>

        
        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['erro']; ?>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success text-center">
                <?= $_SESSION['sucesso']; ?>
            </div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>

       
        <form action="editarback.php?id_usuario=<?= $conta['id_usuario'] ?>" method="POST">

            <div class="form-box">

                <label>Email</label>
                <input 
                    type="email" 
                    name="login" 
                    value="<?= $conta['login'] ?>" 
                    required
                >

            </div>

            <div class="admin-footer d-flex justify-content-between align-items-center">

                <a href="telaadmin.php" class="btn-secondary">
                    ← Voltar
                </a>

                <button type="submit" class="btn-primary">
                    Atualizar
                </button>

            </div>

        </form>

    </div>
</div>

</body>
</html>