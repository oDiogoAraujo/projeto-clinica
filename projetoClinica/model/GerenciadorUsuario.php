<?php
class GerenciadorUsuario
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
    public function cadastrarUsuario($dadosUsuario)
    {
        //Verificando se o CPF já está cadastrado no BD
        $jaExiste = $this->verificarUsuario($dadosUsuario['cpf']);
        if ($jaExiste) {
            //Se verdadeiro, ele retorna com msg de erro
            return [
                'erro' => true,
                'msg' => 'CPF já cadastrado!',
                'cor' => 'alert-danger'
            ];
        }

        // Monta a query (Cadastro)
        $sql = "INSERT INTO usuarios (cpf, nome, permissao_id, telefone, email, senha)
                VALUES (?,?,?,?,?,?)";

        //Criando o Prepared Statement
        $query = $this->conexao->prepare($sql);

        $cpf = $dadosUsuario['cpf'];
        $nome = $dadosUsuario['nome'];
        $permissao = $this->verificarPermissao($dadosUsuario);
        $telefone = $dadosUsuario['telefone'];
        $email = $dadosUsuario['email'];
        $senha = $this->criptografarSenha($dadosUsuario['senha']);

        //Vinculando o paramentro 
        $query->bind_param("ssisss", $cpf, $nome, $permissao, $telefone, $email, $senha);

        //Executando o codigo SQL
        if ($query->execute()) {
            //Obtendo o id 
            $idUsuario = $query->insert_id;

            return [
                'erro' => false,
                'msg' => 'Usuario Adicionado com Sucesso!',
                'cor' => 'alert-success',
                'usuario_id' => $idUsuario
            ];
        } else {
            return [
                'erro' => True,
                'msg' => 'Erro ao conectar com o Banco deDados',
                'cor' => 'alert-danger'
            ];
        }
    }

    private function criptografarSenha($senhaInformada)
    {
        $senhaNova = hash('sha256', $senhaInformada);

        return $senhaNova;
    }
    private function verificarPermissao($dadosUsuario)
    {
        if (isset($dadosUsuario['permissao'])) {
            return 2;
        } else {
            return 3;
        }
    }

    public function verificarUsuario($cpfInformado)
    {
        //pesquisando se já existe o mesmo cpf no BD
        $sql = "SELECT * FROM usuarios WHERE cpf = ?";
        $query = $this->conexao->prepare($sql);
        $query->bind_param('s', $cpfInformado);
        $query->execute();

        $resultado = $query->get_result();

        //a variavel irá receber TRUE caso seja maior que 0 
        $existeRegistro = ($resultado->num_rows > 0);

        $query->close();

        return $existeRegistro;
    }

    public function loginUsuario($dadosUsuario)
    {
        //Pesquisando somente o CPF
        $sql = "SELECT * FROM usuarios WHERE cpf = ?";
        $query = $this->conexao->prepare($sql);

        $cpf = $dadosUsuario['cpf'];
        $senhaDigitada = $dadosUsuario['senha'];

        $query->bind_param('s', $cpf);
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
                    'msg' => 'CPF e Senha Corretos',
                    'cor' => 'alert-success',
                    'usuarioRegistrado' => $usuarioRegistrado,
                    'encaminharPagina' => true
                ];
            } else {
                //Autenticação Falhou (Senhas nao conferes)
                return [
                    'erro' => True,
                    'msg' => 'CPF e/ou Senha incorretos',
                    'cor' => 'alert-danger'
                ];
            }
        } else {
            //Autenticação Falhou (numero de linhas maior que 1 OU menor que 1)
            return [
                'erro' => True,
                'msg' => 'CPF e/ou Senha incorretos',
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
