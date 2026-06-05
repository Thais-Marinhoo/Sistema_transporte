<?php
include('conexao.php');
session_start();

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);
$email = $_POST['email'];

// 1. gerar código
$codigo = rand(100000, 999999);

// 2. salvar no banco (precisa ter campo codigo_recuperacao)
mysqli_query($conexao, "UPDATE users SET codigo_recuperacao='$codigo' WHERE login='$email'");

// 3. guardar email na sessão
$_SESSION['email_reset'] = $email;

// 4. enviar email
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'nayra.sousaa16@gmail.com';
$mail->Password = 'qeui wuww eukf jyfz';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('nayra.sousaa16@gmail.com', 'Sistema');
$mail->addAddress($email);

$mail->isHTML(true);
$mail->Subject = "Código de recuperação";
$mail->Body = "Seu código é: <b>$codigo</b>";

$mail->send();

// 5. redireciona
header("Location: verificar_codigo.php");
exit;
?>