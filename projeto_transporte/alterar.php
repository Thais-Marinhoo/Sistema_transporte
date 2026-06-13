<?php
session_start();
include('conexao.php');

if(!isset($_SESSION['email_ok'])){
    die("Acesso inválido");
}

$email = $_SESSION['email_ok'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nova_senha = $_POST['nova_senha'];

    mysqli_query($conexao,
        "UPDATE users 
         SET senha = SHA2('$nova_senha',256),
             codigo_recuperacao = NULL
         WHERE login='$email'"
    );

    unset($_SESSION['email_ok']);

    header("Location: index.php?status=sucesso");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nova Senha</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container-login">
    <div class="card-login">

        <h3 class="text-center fw-bold">
            Nova Senha
        </h3>

        <hr>

        <p class="text-center mb-4">
            Digite sua nova senha para concluir a recuperação.
        </p>

        <form method="POST">

            <label>Nova senha</label>

            <input
                type="password"
                name="nova_senha"
                placeholder="Digite sua nova senha"
                required
            >

            <button class="btn-login" type="submit">
                Salvar Nova Senha
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="index.php">Cancelar</a>
        </div>

    </div>
</div>

</body>
</html>