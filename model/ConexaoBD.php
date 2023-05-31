<?php

class ConexaoBD
{
    public function abrirConexaoBD()
    {
        //Dados para Conectar
        $hostname = "localhost";
        $bancodedados = "projeto_clinica";
        $usuario = "root";
        $senha = "";

        //Conexao
        $conexao =  new mysqli($hostname, $usuario, $senha, $bancodedados);

        //Seleciona o BD
        mysqli_select_db($conexao, $bancodedados);

        return $conexao;
    }

    public function fecharConexaoBD($conexao)
    {
        mysqli_close($conexao);
    }
}
