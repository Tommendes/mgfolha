<?php

use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\helpers\Html;
use yii\helpers\Url;

$url = Url::home(true) . 'user/z/';
?>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'base64')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'id_user')->hiddenInput()->label(false) ?>
<div class="row"> 
    <div class="offset-md-4 col-md-4" style=" padding-top: 5px;">
        <video id="video" class="rounded" style="display: block; width: 100%;" autoplay></video>
    </div>
    <div class="offset-md-4 col-md-4">
        <?=
        Html::tag('p', 'Procure centralizar e aproximar bem o seu rosto e cuidado com muita luz de fundo', [
            'class' => 'control-label text-center',
        ])
        ?>
    </div>
    <div class="offset-md-4 col-md-4">
        <div><button id="snap" hidden class="btn btn-secondary hide" style="display: block; width: 100%;"></button></div>
        <div><button id="save" class="btn btn-secondary" style="display: block; width: 100%;"><i class="fa fa-camera"></i>&nbsp;Tirar Foto</button></div>
    </div>
</div>

<div class="row"> 
    <div class="offset-md-4 col-md-4">
        <div><canvas id="canvas" width="640" height="480" hidden></canvas></video></div>
    </div>
</div>
<script>

    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video');
    var mediaConfig = {video: true};
    window.addEventListener("DOMContentLoaded", function () {

        var errBack = function (e) {
            console.log('An error has occurred!', e)
        };

        // Put video listeners into place
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia(mediaConfig).then(function (stream) {
                //video.src = window.URL.createObjectURL(stream);
                video.srcObject = stream;
                video.play();
            });
        }
        if (navigator.getUserMedia) {
            navigator.getUserMedia(videoObj, function (stream) {
                video.src = stream;
                video.play();
            }, errBack);
        } else if (navigator.webkitGetUserMedia) {
            navigator.webkitGetUserMedia(videoObj, function (stream) {
                video.src = window.webkitURL.createObjectURL(stream);
                video.play();
            }, errBack);
        } else if (navigator.mozGetUserMedia) {
            navigator.mozGetUserMedia(videoObj, function (stream) {
                video.src = window.URL.createObjectURL(stream);
                video.play();
            }, errBack);
        }
    }, false);
//    // on the submit event, generate a image from the canvas and save the data in the textarea
//    document.getElementById('form').addEventListener("submit", function () {
//        var image = canvas.toDataURL(); // data:image/png....
//        document.getElementById('base64').value = image;
//    }, false);
    document.getElementById("snap").addEventListener("click", function () {
        canvas.getContext("2d").drawImage(video, 0, 0, 640, 480);
    });
    document.getElementById("save").addEventListener("click", function () {
        canvas.getContext("2d").drawImage(video, 0, 0, 640, 480);
        $('#uploadform-base64').val(canvas.toDataURL());
        $.ajax({
            url: '<?= $url ?>',
            type: 'post',
            success: function (response) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Sucesso',
                    text: response,
                    type: 'success',
                    styling: 'fontawesome',
                    animate: {
                        animate: true,
                        in_class: 'rotateInDownLeft',
                        out_class: 'rotateOutUpRight'
                    }
                });
            },
        });
    });
</script>
<?php ActiveForm::end() ?>