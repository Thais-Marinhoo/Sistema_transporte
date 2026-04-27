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
            
                <!-- ALERTA DE SUCESSO E ERRO -->
                <?php if(isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
                    <div class="alert alert-success text-center py-2" style="font-size: 14px;">
                        Senha atualizada com sucesso!
                    </div>
                <?php endif; ?>
                
                    <?php if(isset($_GET['status']) && $_GET['status'] == 'mistake'): ?>
                        <div class="alert alert-danger text-center py-2" style="font-size: 14px;">
                            Senha ou Email inválidos!
                        </div>
                    <?php endif; ?>
                    
                    <img class="logo" src="logo.png" alt="Logo Rota Certa">
                    
                    <form action="login.php" method="POST">
                        <label>E-mail</label>
                        <input type="email" name="email" required>
                        
                        <label>Senha</label>
                        <input type="password" name="senha" required>
                        
                        <!-- Link que antes era index.php?show_reset=true agora aponta para o ID -->
                        <div class="esqueceu">
                            Esqueceu sua senha? <a href="#modalReset">Clique aqui</a>
                        </div>

                        <button class="btn-login">Login</button>
                    </form>
                    
                    
                    <!-- ESTRUTURA DO MODAL SEM JS -->
                    <div id="modalReset" class="modal-fake">
                        <div class="card-login">
                            <a href="#" class="btn-fechar">&times;</a>
                            <h5 class="fw-bold text-center">Alterar Senha</h5>
                            <hr>
                            
                            <form action="alterar.php" method="POST">
                                <label>Confirme seu e-mail</label>
                                <input type="email" name="email_reset" required>
                                
                                <label>Nova senha</label>
                                <input type="password" name="nova_senha" required>
                                
                                <button class="btn-login">Salvar Nova Senha</button>
                    <a href="#" class="btn btn-sm btn-link d-block text-center mt-3 text-muted">Cancelar</a>
                </form>
            </div>
        </div>

    </div>
</div>

</body>
</html>