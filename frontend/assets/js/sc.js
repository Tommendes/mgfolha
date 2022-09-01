/* global PNotify */

/**
 * Executa a geração da folha
 * @param {type} _url
 * @returns {undefined}
 */
var tgf = 0, _text = '', opersI, opersF;
var notice, done = false;
var options;
function sc(_url, _urlTgf) {
    $.post(_url + opersI, function () {
    }).done(function (data) {
        _text += '<br><i class="fas fa-check"></i> ' + data['mess'];
        opersI++;
        if (opersI <= opersF) {
            sc(_url, _urlTgf);
        }
        if (opersI > opersF) {
            setTgf(_urlTgf + tgf.toString());
            if ($('#loader').is(':visible')) {
                $('#loader').hide();
            } else {
                $('#loader').show();
            }
            $('#closeAutoModalHeader').click();
            done = true;
        }
    }).fail(function (data) {
        new PNotify({
            title: 'Erro',
            text: data['mess'],
            type: 'error',
            styling: 'fontawesome',
            animate: {
                animate: true,
                in_class: 'rotateInDownLeft',
                out_class: 'rotateOutUpRight'
            }
        });
    });

//    $.ajax({
//        url: _url + opersI,
//        type: "GET",
//        success: function (response) {
//            _text += '<br><i class="fas fa-check"></i> ' + response['mess'];
//            opersI++;
//            if (opersI <= opersF) {
//                sc(_url, _urlTgf);
//            }
//            if (opersI > opersF) {
//                setTgf(_urlTgf + tgf.toString());
//                if ($('#loader').is(':visible')) {
//                    $('#loader').hide();
//                } else {
//                    $('#loader').show();
//                }
//                $('#closeAutoModalHeader').click();
//                done = true;
//            }
//        }
////       , error: function (response, status, error) {
////            new PNotify({
////                title: 'Erro',
////                text: response['mess'],
////                type: response['class'],
////                styling: 'fontawesome',
////                animate: {
////                    animate: true,
////                    in_class: 'rotateInDownLeft',
////                    out_class: 'rotateOutUpRight'
////                }
////            });
////        }
//    });
    return false;
}

/**
 * Informa o andamento da operação
 * @param {type} _interv
 * @returns {undefined}
 */
function dyn_notice(_interv) {
    var percent = 0;
    _interv = Number(_interv * 1.3).toFixed(0); // Acrescenta 30% ao valor para melhorar a resposta em fazê-la menos evasiva
    notice = new PNotify({
        text: _text,
        type: 'info',
        icon: 'fa fa-spinner fa-spin',
        hide: false,
        styling: 'fontawesome',
        animate: {
            animate: true,
            in_class: 'rotateInDownLeft',
            out_class: 'rotateOutUpRight'
        },
        buttons: {
            closer: false,
            sticker: false
        },
        shadow: false
    });
    setTimeout(function () {
        notice.update({
            title: "Por favor aguarde!"
        });
        var interval = setInterval(function () {
            if (percent < 100)
                percent++;
            options = {
                text: "<p>Aproximadamente " + percent
                        + "% concluído. <i class='fa fa-question-circle' title='O percentual informado basea-se na média de tempo desta operação'></i><p>" + _text
            };
            if (percent >= 30 && percent < 50) {
                options.title = "Quase lá...";
            } else if (percent >= 50 && percent < 80) {
                options.title = "Só mais um pouco...";
            } else if (percent >= 80 && percent < 100) {
                options.title = "Finalizando...";
            } else if (done || percent >= 100) {
                window.clearInterval(interval);
                options.title = "Pronto!";
                options.type = "success";
                options.text = _text;
                options.hide = false;
                options.styling = 'fontawesome';
                options.buttons = {
                    closer: true,
                    sticker: true
                };
                options.icon = 'fa fa-check';
                options.shadow = true;
                options.width = PNotify.prototype.options.width;
                mesFinal(tgf);
            }
            notice.update(options);
        }, (_interv * 10));
    }, 2000);
}
/**
 * Altera a mensagem final
 * @param {type} segundos
 * @returns {undefined}
 */
function mesFinal(segundos) {
    td = new Date(segundos * 1000).toISOString().substr(14, 5);
    tempoMesFinal = 5;
    setInterval(function () {
        tempoMesFinal--;
        if (td.substr(0, 2) > 0) {
            tempo = " minuto" + (td.substr(0, 2) === '01' ? '' : '(s)');
        } else {
            tempo = " segundo" + (td.substr(2, 2) === '01' ? '' : '(s)');
        }
        textoFinal = "<br><i class='fas fa-check'></i> Registros gerados com sucesso em "
                + td + tempo
                + '<br>A tela atual será recarregada em instantes';// + tempoMesFinal + (tempoMesFinal === 1 ? ' segundo' : ' segundos');
        options.text = _text + textoFinal;
        notice.update(options);
        if (tempoMesFinal === 1) {
            location.reload();
        }
    }, 1000);
}
/**
 * Seta o Tgf no BD
 * @param {type} _url
 * @returns {undefined}
 */
function setTgf(_url) {
    $.ajax({
        url: _url,
        type: 'post',
//        success: function (response) {
//            new PNotify({
//                title: 'Sucesso',
//                text: response,
//                type: 'success',
//                styling: 'fontawesome',
//                animate: {
//                    animate: true,
//                    in_class: 'rotateInDownLeft',
//                    out_class: 'rotateOutUpRight'
//                }
//            });
//        },
        error: function (response) {
            new PNotify({
                title: 'Erro',
                text: 'Erro ao registrar TGF',
                type: 'warning',
                styling: 'fontawesome',
                animate: {
                    animate: true,
                    in_class: 'rotateInDownLeft',
                    out_class: 'rotateOutUpRight'
                }
            });
        }
    });
} 