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
    <!-- CSS do FullCalendar -->
    <link href='../lib/FullCalendar v5/main.css' rel='stylesheet' />
    <!-- CSS do FullCalendar + Bootstrap -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <!-- Importando o CSS proprio -->
    <link rel="stylesheet" href="css/style.css">
    <title>Agendamento de Consulta</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="login-container">
                    <h2>Agendamento de Consulta</h2>
                    <form id="agendamentoConsulta">
                        <div class="form-group">
                            <label for="cpf">CPF do Paciente</label>
                            <input type="text" class="form-control" name="cpf" id="cpf" placeholder="Digite o CPF do Paciente">
                        </div>
                        <div class="form-group">
                            <label for="cpf">CRM do Médico</label>
                            <input type="text" class="form-control" name="CRM" id="CRM" placeholder="Digite o CRM do Médico">
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="diaConsulta">Dia da Consulta</label>
                                <input type="text" class="form-control" name="diaConsulta" id="diaConsulta" placeholder="Selecione o dia no Calendário" disabled>
                            </div>
                            <div class="col">
                                <label for="horarioConsulta">Horário da Consulta</label>
                                <input type="text" class="form-control" name="horarioConsulta" id="horarioConsulta" placeholder="Selecione o horário no Calendário" disabled>
                            </div>
                        </div>

                        <!-- FullCalendar -->
                        <div class="my-4">
                            <div id='calendar'></div>
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
    <!-- JS do Calendar -->
    <script src='../lib/FullCalendar v5/main.js'></script>
    <script src="js/configCalendar.js"></script>

</body>

</html>