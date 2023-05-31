<?php
class GerenciadorMedico
{
    private $conexao;
    //Abrindo conexão com o BD
    public function __construct()
    {
        require_once "ConexaoBD.php";
        $bd = new ConexaoBD();
        $this->conexao = $bd->abrirConexaoBD();
    }

    //Cadastrando um novo Usuario
    public function cadastrarMedico($dadosMedico)
    {
        // //Verificando se o crm já está cadastrado no BD
        // $jaExiste = $this->verificarMedico($dadosMedico['crm']);
        // if ($jaExiste) {
        //     //Se verdadeiro, ele retorna com msg de erro
        //     return [
        //         'erro' => true,
        //         'msg' => 'crm já cadastrado!',
        //         'cor' => 'alert-danger'
        //     ];
        // }

        // Monta a query (Cadastro)
        $sql = "INSERT INTO medicos (crm, nome, especialidade_id, usuario_id)
                VALUES (?,?,?,?)";

        //Criando o Prepared Statement
        $query = $this->conexao->prepare($sql);

        $crm = $dadosMedico['crm'];
        $nome = $dadosMedico['nome'];
        $especialidade = $dadosMedico['especialidade'];;
        $usuario_id = $dadosMedico['usuario_id'];

        //Vinculando o paramentro 
        $query->bind_param("ssii", $crm, $nome, $especialidade, $usuario_id);

        //Executando o codigo SQL
        if ($query->execute()) {
            return [
                'erro' => false,
                'msg' => 'Medico Adicionado com Sucesso!',
                'cor' => 'alert-success'
            ];
        } else {
            return [
                'erro' => True,
                'msg' => 'Erro ao conectar com o Banco deDados',
                'cor' => 'alert-danger'
            ];
        }
    }

    public function verificarMedico($crmInformado)
    {
        //pesquisando se já existe o mesmo crm no BD
        $sql = "SELECT * FROM medicos WHERE crm = ?";
        $query = $this->conexao->prepare($sql);
        $query->bind_param('s', $crmInformado);
        $query->execute();

        $resultado = $query->get_result();

        //a variavel irá receber TRUE caso seja maior que 0 
        $existeRegistro = ($resultado->num_rows > 0);

        $query->close();

        return $existeRegistro;
    }

    public function loginUsuario($dadosMedico)
    {
        //Pesquisando somente o crm
        $sql = "SELECT * FROM usuarios WHERE crm = ?";
        $query = $this->conexao->prepare($sql);

        $crm = $dadosMedico['crm'];
        $senhaDigitada = $dadosMedico['senha'];

        $query->bind_param('s', $crm);
        $query->execute();

        //Obtendo e armazenando o resultado
        $resultado = $query->get_result();

        //Vendo se o resultado só corresponde a uma linha
        if ($resultado->num_rows == 1) {
            //Obtendo todas as informações da consulta em um array
            $usuarioRegistrado = $resultado->fetch_assoc();
            //Armazenando a Senha que possui no BD
            $senhaRegistrada = $usuarioRegistrado['senha'];

            //Verificando se a senha inserida é igual a senha registrada usando hash_equals
            if (hash_equals($senhaRegistrada, hash('sha256', $senhaDigitada))) {
                //Autenticação Bem-sucedida

                return [
                    'erro' => False,
                    'msg' => 'crm e Senha Corretos',
                    'cor' => 'alert-success',
                    'usuarioRegistrado' => $usuarioRegistrado,
                    'encaminharPagina' => true
                ];
            } else {
                //Autenticação Falhou (Senhas nao conferes)
                return [
                    'erro' => True,
                    'msg' => 'crm e/ou Senha incorretos',
                    'cor' => 'alert-danger'
                ];
            }
        } else {
            //Autenticação Falhou (numero de linhas maior que 1 OU menor que 1)
            return [
                'erro' => True,
                'msg' => 'crm e/ou Senha incorretos',
                'cor' => 'alert-danger'
            ];
        }
    }
    //Fechando a conexão com o BD
    public function __destruct()
    {
        require_once "ConexaoBD.php";
        $bd = new ConexaoBD();
        $bd->fecharConexaoBD($this->conexao);
    }
}
