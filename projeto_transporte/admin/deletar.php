<?php
include '../conexao.php';

remover_conta($conexao, $_GET['id_usuario']);

function remover_conta($conexao, $id) {
    $sql_deleta = "DELETE FROM users where id_usuario = {$id}";
    mysqli_query($conexao, $sql_deleta);
}
header('Location: telaadmin.php');
?>