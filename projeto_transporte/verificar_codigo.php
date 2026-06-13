<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verificar Código</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container-login">
    <div class="card-login">

        <h3 class="titulo-recuperacao">Verificar Código</h3>

        <hr>

        <p class="texto-info">
            Digite o código enviado para seu e-mail.
        </p>

        <form method="POST" action="validar_codigo.php">

            <label>Código</label>
            <input
                type="text"
                name="codigo"
                placeholder="Digite o código recebido"
                required
            >

            <button class="btn-login" type="submit">
                Validar Código
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="index.php" class="cancelar-link">
                Cancelar
            </a>
        </div>

    </div>
</div>

</body>
</html>