<?php
//conexao da pporta 3307
define('HOST', 'localhost');
define('USUARIO', 'root');
define('SENHA', '');
define('DB', 'login_transporte');

define('PORTA', 3307);
$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB, PORTA)
//    or die('Não foi possível conectar: ' . mysqli_connect_error());

//conexao da porta 3306
//$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die('Não foi possível conectar');

?>
