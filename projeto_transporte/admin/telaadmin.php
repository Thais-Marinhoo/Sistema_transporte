<?php
include "telaadminback.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">

   
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<div class="container-admin">

    
    <img src="logo.png" class="logo-topo">

    <div class="card-admin">

       
        <div class="admin-header">
            <div>
                <h1>Contas Cadastradas</h1>
                <span class="sub">Gerencie os usuários do sistema</span>
            </div>

            <a href="cadastro.php" class="btn-primary">
                + Cadastrar Nova Conta
            </a>
        </div>

       
        <div class="table-box">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>E-mail</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($lista_contas as $conta): ?>
                    <tr>
                        <td><?= $conta['login']; ?></td>

                        <td class="actions">
                            <a href="editar.php?id_usuario=<?= $conta['id_usuario']; ?>" class="btn-action edit">
                                Editar
                            </a>

                            <a href="deletar.php?id_usuario=<?= $conta['id_usuario']; ?>" class="btn-action delete">
                                Deletar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    
        <div class="admin-footer">
            <a href="../index.php" class="btn-secondary">
                ← Voltar
            </a>
        </div>

    </div>
</div>

</body>
</html>