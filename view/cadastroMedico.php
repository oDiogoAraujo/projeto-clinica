<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Importando o Bootstrap direto da internet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Importando o CSS proprio -->
    <link rel="stylesheet" href="css/style.css">
    <title>Cadastro de Medico</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-container">
                    <h2>Cadastro de Medico</h2>
                    <!-- <form id="cadastroPaciente"> -->
                    <form id="cadastroMedico">
                        <div class="form-group">
                            <label for="cpf">CPF:</label>
                            <input type="text" class="form-control" name="cpf" id="cpf" placeholder="Digite seu CPF" value="123">
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite seu Nome" value="123">
                        </div>
                        <div class="form-group">
                            <label for="crm">CRM:</label>
                            <input type="text" class="form-control" name="crm" id="crm" placeholder="Digite seu CRM" value="123">
                        </div>
                        <div class="form-group">
                            <label for="especialidade">Especialidade:</label>
                            <input type="number" class="form-control" name="especialidade" id="especialidade" placeholder="Digite sua Especialidade" value="123">
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone:</label>
                            <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Digite seu Telefone" value="123">
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail:</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Digite seu E-Mail" value="123@gmail.com">
                        </div>
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" class="form-control" name="senha" id="password" placeholder="Digite sua senha" value="123">
                        </div>
                        <button type="submit" class="btn btn-primary">Entrar</button>
                        <span class="text-danger" id="alertaErro"></span>
                        <div id="mensagem" class="alert" style="display: none;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Importando o proprio JS -->
    <script src="js/scripts.js"></script>

</body>

</html>