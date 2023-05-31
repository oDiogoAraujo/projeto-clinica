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

    public function existeHorario($agendamento)
    {
        $sql = "SELECT * FROM horarios_medico WHERE horarioInicio <= ? AND dataFim >= ?";

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


    //Fechando a conexão com o BD
    public function __destruct()
    {
        require_once "ConexaoBD.php";
        $bd = new ConexaoBD();
        $bd->fecharConexaoBD($this->conexao);
    }
}
