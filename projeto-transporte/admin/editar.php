<?php
session_start();
include '../conexao.php';

// Busca dados da conta CORRIGIR POIS SALVA COM ELA COMO MD5 (pegar de outro cód exemplo de decodificar)
$conta = busca_conta($conexao, $_GET['id_usuario']);

function busca_conta($conexao, $id) {
    $sql = "SELECT * FROM users WHERE id_usuario = $id";
    $result = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($result);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body class="bg-site">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-sm-center h-100">

                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-9">
                    <div class="card">
                        <div class="card-body p-5">

                            <h1 class="fs-4 card-title fw-bold mb-4 text-center">Editar conta</h1>

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


                                <div class="row">

                                    <div class="col-6 mb-3">
                                        <label class="text-muted">Email:</label>
                                        <input type="email" name="login" class="form-control" value="<?= $conta['login'] ?>" required>
                                    </div>


                                </div>

                                

                                <button class="btn btn-primary w-30">Atualizar</button>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</body>
</html>