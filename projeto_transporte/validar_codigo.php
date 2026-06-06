<?php
include('conexao.php');
session_start();

$email = $_SESSION['email_reset'];
$codigo = $_POST['codigo'];

$sql = mysqli_query($conexao,
    "SELECT * FROM users 
     WHERE login='$email' 
     AND codigo_recuperacao='$codigo'"
);

if(mysqli_num_rows($sql) == 1){

    // GUARDA O EMAIL PRA PRÓXIMA ETAPA
    $_SESSION['email_ok'] = $email;

    header("Location: alterar.php");
    exit();

} else {
    echo "Código inválido";
}
?>