<?php
session_start();
include('menu.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Novo Aluno</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../style.css">
    </head>

    <body>
        <section class="h-100">
            <div class="container h-100">
                            <?php if (isset($_SESSION['erro'])): ?>
                                <div class="alert alert-danger alert-dismissable text-center">
                                    <?= $_SESSION['erro']; ?>
                                </div>
                                <?php unset($_SESSION['erro']); ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['sucesso'])): ?>
                                <div class="alert alert-success alert-dismissable text-center">
                                    <?= $_SESSION['sucesso']; ?>
                                </div>
                                <?php unset($_SESSION['sucesso']); ?>
                            <?php endif; ?>
                <div class="row justify-content-sm-center h-100">
                    
                    <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-9">
                        <div class="card">
                            <div class="card-body p-5">
                                <h1 class="fs-4 card-tittle fw-bold mb-4 text-center">Novo Aluno</h1> 
                                
                                <form action="formulario.php" method="POST" class="needs-validation" novalidate="" autocomplete="off">
                                <div class="row justify-content-sm-center h-100 g-1">
                                    <div class="row justify-content-sm-center h-100 g-1">
                                        <label for="" class="mb-3 text-center fs-3">Informações primárias:</label>

                                        <div class="col-6 mb-3">
                                            <label for="nome" class="mb-2 text-muted">Nome:</label>
                                            <input type="name" name="name" id="name" class="form-control" value="" required autofocus>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="row justify-content-sm-center h-100 g-1">
                                    <label for="" class="text-center fs-3 mb-3">Endereço:</label>

                                    <div class="row justify-content-sm-center h-100 g-1">
                                        <div class="col-6 mb-3">
                                            <label for="rua" class="mb-2 text-muted">Rua:</label>
                                            <input type="name" name="street" id="rua" class="form-control" value="" required autofocus>
                                        </div>
                                    </div>

                                    <div class="row justify-content-sm-center h-100 g-1">
                                        
                                        <div class="col-6 mb-3">
                                            <label for="bairro" class="mb-2 text-muted">Bairro:</label>
                                            <input type="name" name="bairro" id="bairro" class="form-control" value="" required autofocus>
                                        </div>
                                    </div>

                                </div>

                                <div class="row justify-content-sm-center h-100 g-1">
                                    <label class="mb-2 text-center fs-3"> Curso: </label>
                                    <div class="row justify-content-sm-center h-100 g-1">
                                        <div class="col-4 mb-3">
                                            <label for="" class="mb-2 text-muted">Tipo:</label>
                                            <select class="form-select" aria-label="Default select example" name="curso">
                                                <option value="1">Enfermagem</option>
                                                <option value="2">Informática</option>
                                                <option value="3">Administração</option>
                                                <option value="4">Desenvolvimento de Sistemas</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label for="" class="mb-2 text-muted">Serie:</label>
                                            <select class="form-select" aria-label="Default select example" name="curson">
                                                <option value="1">Primeiro Ano</option>
                                                <option value="2">Segundo Ano</option>
                                                <option value="3">Terceiro Ano</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="align-items-center d-flex justify-content-center">
									<button type="submit" class="btn btn-primary ms-auto">
									Enviar	
									</button>
								</div>
                                
                            </form>
                        </div>
                        </div>
                    </div>

                </div>    
            </div>
        </section>
    </body>
</html>