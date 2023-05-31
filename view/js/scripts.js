const meuFormulario = document.querySelector('form')
const botaoLogout = document.getElementById('logout')
if (botaoLogout) {
    botaoLogout.addEventListener('click', (e) => {
        e.preventDefault()
        const botaoID = botaoLogout.getAttribute('ID')
        enviarParaPHP(false, botaoID)
    })

}


if (meuFormulario) {
    meuFormulario.addEventListener('submit', (e) => {
        e.preventDefault()
        const campos = meuFormulario.querySelectorAll('input')
        const spanAlerta = meuFormulario.querySelector('span')

        // //se a função retornar TRUE, o programa nao irá continuar.
        if (VerificarCamposVazios(campos, spanAlerta)) return;
        deixandoLetraMaiuscula(campos)

        //Com base o ID do formulario, será decidido a função do formulario
        const formularioID = meuFormulario.getAttribute('ID')
        enviarParaPHP(meuFormulario, formularioID)
    })

}


/* Função para verificar se todos os campos foram preenchidos.
Deve ser passado todos os campos do formulario por um Array junto com o span
para falar se falta campo ou nao */
const VerificarCamposVazios = (todosCampos, spanAlerta) => {
    let campoVazio = false

    for (i = 0; i < todosCampos.length; i++) {
        //adicionando cor ou removendo caso o campo esteja preenchido ou nao.
        if (todosCampos[i].value.trim() === '') {
            campoVazio = true
            todosCampos[i].classList.add('is-invalid')
            break
        } else {
            todosCampos[i].classList.remove('is-invalid')
        }
    }
    if (campoVazio) {
        spanAlerta.innerText = "Preencha todos os campos!"
        return true
    } else {
        spanAlerta.innerText = ""
        return false
    }
}

const deixandoLetraMaiuscula = (todosCampos) => {
    for (const campoIndividual of todosCampos) {
        //deixando mais limpo se o campo é textou ou nao
        const campoTexto = (campoIndividual.getAttribute('type') === 'text')

        if (campoTexto) { // se for true, ele cairá no if
            campoIndividual.value = campoIndividual.value.toUpperCase()
        }
    }
}

const enviarParaPHP = (meuFormulario, props) => {
    //criando um objeto para o melhor envio dos dados
    const parametros = {
        'meuFormulario': meuFormulario,
        'funcao': props,
    }

    //definindo a url
    if (props == 'cadastroUsuario') {
        enviarDadosUsuario(parametros)
    }
    if (props == 'cadastroMedico') {
        enviarDadosUsuario(parametros)
    }
    if (props == 'login') {
        enviarDadosUsuario(parametros)
    }
    if (props == 'logout') {
        enviarDadosUsuario(parametros)
    }

}

const enviarDadosUsuario = async (parametros) => {
    let dadosUsuario
    if (parametros.meuFormulario) {
        //Todos os campos do Formulario
        dadosUsuario = new FormData(parametros.meuFormulario)

        //definindo oq será feito por parte do php (cadastro, login etc)
        dadosUsuario.append('funcao', parametros.funcao)
    } else {
        //Criando um FormData para enviar (vazio mesmo)
        dadosUsuario = new FormData()
        dadosUsuario.append('funcao', parametros.funcao)
    }

    //Enviando todos dados do formulario para GerenciadorUsuario.php
    const fetchDadosUsuario = await fetch("../controller/contatoController.php", {
        method: "POST",
        body: dadosUsuario,
    })

    //Aguardando a resposta do PHP
    const resposta = await fetchDadosUsuario.json()
    console.log(resposta)
    if (resposta.erro == false && resposta.encaminharPagina == true) {
        return window.location.href = resposta.urlPagina;
    }
    inserirMensagemStatus(resposta)

}

//Mensagem se a realização foi um sucesso ou nao
//Mensagem vem direto do PHP, já atribuido o erro e a cor
const inserirMensagemStatus = (resposta) => {
    //Selecionando o elemento no HTML
    const mensagemDiv = document.getElementById('mensagem')

    //Deixando ele Visivel
    mensagemDiv.style.display = 'block'

    //Efeitos visuais para a mensagem de status
    mensagemDiv.classList.add(resposta['cor'])
    mensagemDiv.innerText = resposta['msg']

    if (!resposta['erro']) limparFormulario(meuFormulario) //Caso NAO tenha erro

    //definindo tempo (3.5s) para desaparecer 
    setTimeout(function () {
        mensagemDiv.style.display = 'none'
        //resetando qualquer estilo de cor anterior
        mensagemDiv.classList.remove('alert-success')
        mensagemDiv.classList.remove('alert-danger')
    }, 3500)
}

const limparFormulario = (meuFormulario) => {
    for (const campoIndividual of meuFormulario) {
        campoIndividual.value = ""
    }
}


