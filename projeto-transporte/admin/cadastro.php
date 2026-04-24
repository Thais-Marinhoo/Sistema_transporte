<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">

</head>

<body>
<form action="cadastroback.php" method="POST" class="needs-validation">

    <div class="container-login">
        <div class="card-login">

            <h1>Cadastro</h1>
            
            <form>
                <label>E-mail</label>
                <input type="email" id="login" name="login" required>
                
                <label>Senha</label>
                <input type="password" id="senha" name="senha" required>
            
            <label>Confirme sua senha</label>
            <input type="password" id="senha2" name="senha2">
            
            <button class="btn-login">Cadastrar</button>
            
            <br>
            <div class="container-login">

                <a class="navbar-brand btn btn-logout btn-sm" href="telaadmin.php">Voltar</a>
            </div>
        </form>
        
        
    </div>
    </div>
</form>
    
</body>
</html>