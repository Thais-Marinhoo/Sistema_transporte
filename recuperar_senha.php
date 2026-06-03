<?php
session_start();
include('conexao.php');

if (isset($_POST['enviar_codigo'])) {
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));

    // Verifica se o e-mail existe no sistema
    $sql = "SELECT * FROM users WHERE login = '$email'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) == 0) {
        header("Location: index.php?status=erro&msg=Email não encontrado no sistema");
        exit();
    }

    // Gera código de 6 dígitos
    $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiracao = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    // Remove códigos antigos desse e-mail
    mysqli_query($conexao, "DELETE FROM password_reset WHERE email = '$email'");

    // Insere o novo código
    $insert = "INSERT INTO password_reset (email, token, expiracao) 
               VALUES ('$email', '$codigo', '$expiracao')";
    mysqli_query($conexao, $insert);

    // Envia o e-mail
    $assunto = "Código de Recuperação - Rota Certa";
    $mensagem = "Olá,\n\nSeu código de verificação para recuperar a senha é:\n\n" . $codigo . "\n\nEste código expira em 15 minutos.\n\nAtenciosamente,\nEquipe Rota Certa";
    $headers = "From: noreply@rotacerta.com\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8";

    if (mail($email, $assunto, $mensagem, $headers)) {
        $_SESSION['email_recuperacao'] = $email;
        header("Location: index.php?status=codigo_enviado");
    } else {
        header("Location: index.php?status=erro&msg=Erro ao enviar e-mail");
    }
    exit();
}

// ====================== ALTERAR SENHA ======================
if (isset($_POST['alterar_senha'])) {
    $email = $_SESSION['email_recuperacao'] ?? '';
    $codigo = trim($_POST['codigo'] ?? '');
    $nova_senha = trim($_POST['nova_senha'] ?? '');

    if (empty($email) || empty($codigo) || empty($nova_senha)) {
        header("Location: index.php?status=erro&msg=Preencha todos os campos");
        exit();
    }

    $sql = "SELECT * FROM password_reset 
            WHERE email = '$email' 
            AND token = '$codigo' 
            AND usado = 0 
            AND expiracao > NOW()";

    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        $senha_hash = md5($nova_senha);
        
        mysqli_query($conexao, "UPDATE users SET senha = '$senha_hash' WHERE login = '$email'");
        mysqli_query($conexao, "UPDATE password_reset SET usado = 1 WHERE email = '$email' AND token = '$codigo'");
        
        unset($_SESSION['email_recuperacao']);
        header("Location: index.php?status=sucesso");
    } else {
        header("Location: index.php?status=erro&msg=Código inválido ou expirado");
    }
    exit();
}
?>