<?php
/*
 * Controle de ociosidade da sessão
 */

use yii\helpers\Url;
use common\models\SisParams;

$idle = SisParams::findOne([
            'dominio' => 'mgfolha',
            'grupo' => 'idle'
        ])->parametro - 1;
$logout = Url::home() . 'logout?m=' . ($m = Yii::t('yii', 'You have been disconnected due to idle system time. Please sign in again.')) . '&p=' . Url::current();
?>

<script>
    var idleTime = userTime = 0;
    $(document).ready(function () {
        var idleInterval = setInterval(timerIncrement, 60000);
        var userInterval = setInterval(userTimeIncrement, 60000 / 60);
        $(this).mousemove(function (e) {
            idleTime = 0;
        });
        $(this).keypress(function (e) {
            idleTime = 0;
        });
    });
    function timerIncrement() {
        idleTime = idleTime + 1;
        $("#idlelbl").text("Inativo há " + idleTime + " minutos");
        /*Após <?= $idle + 1 ?> minuto(s) de ociosidade o sistema irá efetuar logout*/
        if (idleTime === <?= $idle ?>) {
            krajeeDialog.alert("Sistema ocioso há " + idleTime + " minutos. Você ainda está aí?<br>Daqui há um minuto sua sessão será desconectada caso não haja resposta.");
        }
<?php
if (Yii::$app->user->identity->administrador <= 1) {
    ?>
            if (idleTime > <?= $idle ?>) {
                $.ajax("<?= $logout ?>", {type: 'POST'});
            }
<?php } ?>
    }
    function userTimeIncrement() {
        userTime = userTime + 1;
        if (!(idleTime === <?= $idle ?>)) {
            $("#idlelbl").text("Tempo da página " + fancyTimeFormat(userTime));
        }
    }

    function fancyTimeFormat(time) {
        // Hours, minutes and seconds
        var hrs = ~~(time / 3600);
        var mins = ~~((time % 3600) / 60);
        var secs = ~~time % 60;

        // Output like "1:01" or "4:03:59" or "123:03:59"
        var ret = "";

        if (hrs > 0) {
            ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
        }

        ret += "" + mins + ":" + (secs < 10 ? "0" : "");
        ret += "" + secs;
        return ret;
    }
</script>