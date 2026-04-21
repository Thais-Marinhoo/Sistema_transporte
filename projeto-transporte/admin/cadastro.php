<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">

</head>

<body class="bg-site">

    
    <div class="container-login">
        
        <div class="card-admin">
            
            <div class="admin-header">
                <div>
                    <h1>Cadastrar nova conta</h1>
                </div>
            </div>
            
            <div class="table-box">

                <form action="cadastroback.php" method="POST" class="needs-validation">
                    
                    <label>E-mail</label>
                    <input type="email" id="login" name="login" required>
                    
                    <label>Senha</label>
                    <input type="password" id="senha" name="senha" required>
                    
                    <label>Confirme sua senha</label>
                    <input type="password" id="senha2" name="senha2">
                    
                    <button class="btn-login">Cadastrar</button>
                    
                    <br>
                    
                     <div class="admin-footer">
                        <a href="telaadmin.php" class="navbar-brand btn btn-logout btn-sm">← Voltar</a>
                    </div>
                    
                </form>
            </div>
        
        
    </div>
    </div>
    
</body>
</html>