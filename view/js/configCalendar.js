document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',    //Exibindo os horarios junto aos dias.
        themeSystem: 'bootstrap5',  //Visual usando Bootstrap
        locale: 'pt',   //Configuração Portugues
        allDaySlot: false, //Desabilitando a aba "all-day"
        nowIndicator: true, //Mostrando o horario atual 

        //Configurando os botoes e exibição do dia
        headerToolbar: {
            start: 'prev,next',
            center: '',
            end: 'title'
        },

        //Formatando a data que é exibida no title
        titleFormat: {
            month: 'long', day: 'numeric'
        },

        //Impedindo que datas anteriores sejam selecinadas
        validRange: function (nowDate) {
            return {
                start: nowDate,
            };
        },

        //OBTENDO O DIA CLICADO
        dateClick: function (info) {
            //obtendo o horario atual
            let horarioAtual = new Date();
            let dataClicada = info.date;

            if (dataClicada < horarioAtual) {
                //Adicionar uma função que aparece na tela "Esse horario já passou !"
                //Ou criar uma função de erro e enviar a msg de erro 
                console.log("Horário já passou");
            } else {

                //Construindo dados
                const dataClicada = {
                    //Itens para o BD
                    ano: info.date.getFullYear(),
                    mes: (info.date.getMonth() + 1), //FullCalendar usa Janeiro como mes 0, entao basta ajustar essa diferença
                    dia: info.date.getDate(),

                    //verificação do horario e do dia se o medico atende
                    diaSemana: info.date.getDay(),
                    horario: info.date.getHours(),
                }
                verificarDisponibilidade(dataClicada)
            }
        },

    });

    calendar.render();
});

const verificarDisponibilidade = async (dataClicada) => {
    // dataClicada[funcao] = 'verificarDisponibilidade'
    dataClicada.funcao = 'verificarDisponibilidade'

    //Preparando envio para o Controller
    const dadosData = new FormData()
    //Passando todos os dados do Objeto
    for (const propriedade in dataClicada) {
        dadosData.append(propriedade, dataClicada[propriedade])
    }

    //Enviando os dados
    const fetchDadosData = await fetch("../controller/contatoController.php", {
        method: "POST",
        body: dadosData,
    })

    const resposta = await fetchDadosData.json()
    console.log(resposta)

}