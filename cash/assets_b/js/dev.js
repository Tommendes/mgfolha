/* global krajeeDialog */

/**
 * Adicionar dias ou anos a uma data
 * @param {type} txtData
 * @param {type} DiasAdd
 * @param {type} AnosAdd
 * @param {type} DifDias
 * @returns {String}
 */
function SomarData(txtData, DiasAdd, AnosAdd = 0, DifDias = false) {
    // Tratamento das Variaveis.
    // var txtData = "01/01/2007"; //poder ser qualquer outra
    // var DiasAdd = 10 // Aqui vem quantos dias você quer adicionar a data
    var d = new Date();
    // Aqui eu "mudo" a configuração de datas.
    // Crio um obj Date e pego o campo txtData e 
    // "recorto" ela com o split("/") e depois dou um
    // reverse() para deixar ela em padrão americanos YYYY/MM/DD
    // e logo em seguida eu coloco as barras "/" com o join("/")
    // depois, em milisegundos, eu multiplico um dia (86400000 milisegundos)
    // pelo número de dias que quero somar a txtData.

    if (DifDias) {
        d.setTime(Date.parse(txtData.split("/").reverse().join("/")) + (86400000 * (DiasAdd)));
    } else {
        d.setTime(Date.parse(txtData.split("/").reverse().join("/")));
    }

    // Crio a var da DataFinal            
    var DataFinal;
    var year;
    // Aqui comparo o dia no objeto d.getDate() e vejo se é menor que dia 10.            
    if (d.getDate() < 10)
    {
        // Se o dia for menor que 10 eu coloca o zero no inicio
        // e depois transformo em string com o toString()
        // para o zero ser reconhecido como uma string e não
        // como um número.
        DataFinal = "0" + d.getDate().toString();
    } else
    {
        // Aqui a mesma coisa, porém se a data for maior do que 10
        // não tenho necessidade de colocar um zero na frente.
        DataFinal = d.getDate().toString();
    }

    // Aqui, já com a soma do mês, vejo se é menor do que 10
    // se for coloco o zero ou não.
    year = d.getFullYear() + AnosAdd;
    if ((d.getMonth() + 1) < 10) {
        DataFinal += "/0" + (d.getMonth() + 1).toString() + "/" + year.toString();
    } else
    {
        DataFinal += "/" + ((d.getMonth() + 1).toString()) + "/" + year.toString();
    }
    return DataFinal;
}

/**
 * Retorna mensagem quando a função não existe
 * @param {type} fun
 * @returns {undefined}
 */
function naF(fun) {
    krajeeDialog.alert("Não há registro " + fun + " no período atual.<br>Tente em outro período");
}

/**
 * Retorna a data atual em pt_BR
 * @returns {String}
 */
function dataFormatada() {
  var data = new Date(),
    dia = data.getDate(),
    mes = data.getMonth() + 1,
    ano = data.getFullYear();
  return [dia, mes, ano].join('/');
}