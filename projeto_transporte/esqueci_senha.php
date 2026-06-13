<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recuperar Senha</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container-login">
    <div class="card-login">

        <h3 class="text-center fw-bold">
            Recuperar Senha
        </h3>

        <hr>

        <p class="text-center mb-4">
            Informe seu e-mail para receber o código de recuperação.
        </p>

        <form method="POST" action="enviar_codigo.php">

            <label>E-mail</label>

            <input
                type="email"
                name="email"
                placeholder="Digite seu e-mail"
                required
            >

            <button class="btn-login" type="submit">
                Enviar Código
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="index.php">Cancelar</a>
        </div>

    </div>
</div>

</body>
</html>