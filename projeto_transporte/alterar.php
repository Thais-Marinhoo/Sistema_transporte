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

<!-- FORM DA NOVA SENHA -->
<form method="POST">
    <input type="password" name="nova_senha" placeholder="Nova senha" required>
    <button type="submit">Salvar senha</button>
</form>