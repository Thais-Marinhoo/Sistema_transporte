<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Rota Certa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container-login">
    <div class="card-login">

        <!-- ALERTA SUCESSO -->
        <?php if(isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert alert-success text-center py-2" style="font-size: 14px;">
                Senha atualizada com sucesso!
            </div>
        <?php endif; ?>

        <!-- ALERTA ERRO -->
        <?php if(isset($_GET['status']) && $_GET['status'] == 'mistake'): ?>
            <div class="alert alert-danger text-center py-2" style="font-size: 14px;">
                Email ou senha inválidos!
            </div>
        <?php endif; ?>

        <!-- LOGO -->
        <img class="logo" src="logo.png" alt="Logo Rota Certa">

        <!-- LOGIN -->
        <form action="login.php" method="POST">

            <label>E-mail</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <button class="btn-login" type="submit">Login</button>

        </form>

        <!-- LINK CORRETO PARA RECUPERAÇÃO -->
        <div class="esqueceu mt-3 text-center">
            Esqueceu sua senha?
            <a href="esqueci_senha.php">Clique aqui</a>
        </div>

    </div>
</div>

</body>
</html>