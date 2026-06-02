<?php

$conexao = mysqli_connect(
    "127.0.0.1",
    "root",
    "",
    "login_transporte",
    3307
);

if ($conexao) {
    echo "Conectado!";
} else {
    echo mysqli_connect_error();
}