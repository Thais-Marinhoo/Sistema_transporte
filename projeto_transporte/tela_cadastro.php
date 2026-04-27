<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro</title>

<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    background:#0b2a4a;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.container{
    background:#102f52;
    padding:30px;
    border-radius:10px;
    width:300px;
    text-align:center;
}

h1{
    color:#fff;
    margin-bottom:15px;
}

label{
    display:block;
    text-align:left;
    color:#fff;
    margin-top:10px;
    font-size:14px;
}

input{
    width:100%;
    padding:8px;
    border-radius:6px;
    border:none;
    margin-top:5px;
}

.btn{
    width:100%;
    padding:10px;
    margin-top:15px;
    background:#f4c430;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.links{
    margin-top:10px;
    font-size:14px;
    color:#fff;
}

.links a{
    color:#f4c430;
    text-decoration:none;
    display:inline;
    margin-top:5px;
}
</style>
</head>

<body>

<div class="container">

<h1>Cadastro</h1>

<form>
<label>E-mail</label>
<input type="email" required>

<label>Senha</label>
<input type="password" required>

<label>Confirme sua senha</label>
<input type="password" required>

<button class="btn">Cadastrar</button>
</form>

<div class="links">
Já tem uma conta?
<a href="index.php">Entrar</a>
</div>

</div>

</body>
</html>