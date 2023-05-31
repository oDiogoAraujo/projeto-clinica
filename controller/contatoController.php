<?php

$funcao = $_POST['funcao'];

//Filtrando qual será a função desejada
if ($funcao == 'login') {
    efetuarLogin();
}
if ($funcao == 'cadastroUsuario') {
    efetuarCadastroUsuario();
}

if ($funcao == 'logout') {
    efetuarLogout();
}

if ($funcao == 'cadastroMedico') {
    efetuarCadastroMedico();
}

if ($funcao == 'verificarDisponibilidade') {
    vericiarDisponibilidade();
}

function efetuarCadastroUsuario()
{
    include_once "../model/GerenciadorUsuario.php";

    //Recebendo todos os dados
    $dadosUsuario = $_POST;

    $gerenciadorUsuario = new GerenciadorUsuario(); //Criando obj. da classe 
    $resposta = $gerenciadorUsuario->cadastrarUsuario($dadosUsuario); //Passando o Usuario e esperando Resposta
    echo json_encode($resposta);
    exit();
}

function efetuarLogin()
{
    include_once "../model/GerenciadorUsuario.php";

    //Recebendo todos os dados
    $dadosUsuario = $_POST;

    $gerenciadorUsuario = new GerenciadorUsuario(); //Criando obj. da classe 
    $resposta = $gerenciadorUsuario->loginUsuario($dadosUsuario); //Passando o Usuario e esperando Resposta

    //Se tiver algum erro, irá exibi-lo
    if ($resposta['erro']) {
        echo json_encode($resposta);
    } else {
        //Guardando dados na SESSION
        session_start();
        $_SESSION['id'] = $resposta['usuarioRegistrado']['id'];
        $_SESSION['nome'] = $resposta['usuarioRegistrado']['nome'];
        $_SESSION['cpf'] = $resposta['usuarioRegistrado']['cpf'];
        $_SESSION['permissao_id'] = $resposta['usuarioRegistrado']['permissao_id'];

        //passando o parametro se há erro e se é ou nao para encaminhar a uma pagina
        $respostaFinal = array(
            'erro' => $resposta['erro'],
            'encaminharPagina' => $resposta['encaminharPagina'],
            'urlPagina' => 'painel.php'
        );
        echo json_encode($respostaFinal);
    }
    exit();
}

function efetuarLogout()
{
    session_start();
    session_destroy();
    $respostaFinal = array(
        'erro' => false,
        'encaminharPagina' => true,
        'urlPagina' => 'login.php'
    );
    echo json_encode($respostaFinal);
    exit();
}

function efetuarCadastroMedico()
{
    include_once "../model/GerenciadorUsuario.php";
    include_once "../model/GerenciadorMedico.php";

    $todosDados = $_POST;



    $dadosUsuario = array(
        'cpf'        => $todosDados['cpf'],
        'nome'       => $todosDados['nome'],
        'permissao'  => 2,
        'telefone'   => $todosDados['telefone'],
        'email'      => $todosDados['email'],
        'senha'      => $todosDados['senha'],
    );

    $dadosMedico = array(
        'crm'            => $todosDados['crm'],
        'nome'           => $todosDados['nome'],
        'especialidade'  => $todosDados['especialidade'],
        'usuario_id'     => ''
    );


    $gerenciadorUsuario = new GerenciadorUsuario();
    $gerenciadorMedico = new GerenciadorMedico();
    $existeRegistroUsuario = $gerenciadorUsuario->verificarUsuario($dadosUsuario['cpf']);
    $existeRegistroMedico = $gerenciadorMedico->verificarMedico($dadosMedico['crm']);
    // $resposta = $gerenciadorUsuario->cadastrarUsuario($dadosUsuario);


    //Definindo a msg de erro com o operador SE "?" SENAO ":"
    if ($existeRegistroUsuario || $existeRegistroMedico) {
        if ($existeRegistroUsuario && $existeRegistroMedico) {
            $msgErro = ['erro' => true, 'msg' => 'CPF e CRM já cadastrados!', 'cor' => 'alert-danger'];
        } else if ($existeRegistroMedico) {
            $msgErro = ['erro' => true, 'msg' => 'CRM já cadastrado em MEDICOS!!', 'cor' => 'alert-danger'];
        } else {
            $msgErro = ['erro' => true, 'msg' => 'CPF já cadastrado em USUARIOS!!', 'cor' => 'alert-danger'];
            // $msgErro = ['erro' => true, 'msg' => 'CPF e CRM já cadastrados nas respectivas tabelas!!', 'cor' => 'alert-danger'];
        }

        echo json_encode($msgErro);
        exit();
    }

    //Criando um USUARIO para o MEDICO
    $resposta = $gerenciadorUsuario->cadastrarUsuario($dadosUsuario);

    if ($resposta) {  //NAO houve erros ao cadastrar o usuario
        $dadosMedico['usuario_id'] = $resposta['usuario_id'];
        //Crando um registro de Medico
        $resposta = $gerenciadorMedico->cadastrarMedico($dadosMedico);
        if (!$resposta['erro']) { //NAO houve erros ao cadastrar o medico

            echo json_encode($resposta);
            exit();
        }
    }

    $erroCadsatro = $resposta;
    echo json_encode($erroCadsatro);
    exit();
}

function vericiarDisponibilidade()
{

    include_once "../model/GerenciadorMedico.php";

    $dadosRecebidos = $_POST;

    //Construindo dados
    $agendamento = array(
        'crm' => 7070,
        'diaSemana' => $dadosRecebidos['diaSemana'],
        'horario' => $dadosRecebidos['horario'],
    );

    $verificaHorario = new GerenciadorMedico();

    //construindo ainda
    // $resposta = $verificaHorario->existeHorario($agendamento);



    echo json_encode($agendamento);
    exit();
}
