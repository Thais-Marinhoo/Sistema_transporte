<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conexao, $_POST['email_reset']);
    $nova_senha = $_POST['nova_senha']; // Senha pura vinda do formulário


    $senha_md5 = md5($nova_senha);

    // Query para atualizar (pesquisa no banco)
    $query = "UPDATE users SET senha = '$senha_md5' WHERE login = '$email'";
    
    if (mysqli_query($conexao, $query)) {
        // Redireciona de volta com uma mensagem de sucesso
        header('Location: index.php?status=sucesso');
    } else {
        header('Location: index.php?status=erro');
    }
    exit();
}
?>