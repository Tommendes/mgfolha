<?php

/**
 * Restaura e armazena a localização dos usuários e visitantes do site
 */
use yii\helpers\Url;

$link_geo_loc = Url::home(true) . 'user-options/set-geo';
$link_evt = Url::home(true) . '../sis-events/c?evento=Acesso não registrado&classevento=Acesso&id_user=0&tabela_bd=acesso';
$uid = Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->id;
//$usuarioOpcoes = User::getUsuariosOpc(Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->id);
?>  

<script language="javascript">
    var LIP_LowPrecision = false; //false = ask permission to the browser, higher precision | true = don't ask permission, lower precision
    function LocalizaIP_done(ip_data) {
        if (!ip_data['error']) //this line is an exemple, you must change it by your Geolocation manipulation code
//            alert('IP Geolocation: ' + ip_data['city'] + '-' + ip_data['state'] + '-' + ip_data['country'] + ' (lat:' + ip_data['latitude'] + ',long:' + ip_data['longitude'] + ')');
            setPos();
    }

    function setPos() {
        $.get("<?= $link_geo_loc ?>?id=" + <?= $uid ?> + "&geo_lt=" + ip_data['latitude'] + "&geo_ln=" + ip_data['longitude'], function (data) {});
<?php
if (Yii::$app->user->isGuest) {
    ?>
            var _url = "<?= $link_evt ?>&glt=" + ip_data['latitude'] + "&gln=" + ip_data['longitude'];
            $.ajax({
                url: _url,
                success: function (result) {
                    //                    alert(result);
                }
            });
<?php } ?>
    }
</script>
<script src="https://www.localizaip.com/api/geolocation.js.php?domain=mgfolha.com.br&token=Y29udGF0b0B0b21tZW5kZXMuY29tLmJyfDExMDQzNDkx"></script>

