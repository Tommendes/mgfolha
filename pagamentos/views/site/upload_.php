<?php

use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\helpers\Html;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?=
$form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
    'options' => [
        'accept' => 'image/*',
        'multiple' => true,
    ],
    'pluginOptions' => [
        'initialPreview' => [
            file_exists(Yii::getAlias('@uploads_root') . DIRECTORY_SEPARATOR . Yii::$app->user->identity->dominio . '/imagens/logo.jpg') ?
                    Html::img(Url::home(true) . Yii::getAlias('@uploads_url') . DIRECTORY_SEPARATOR . Yii::$app->user->identity->dominio . '/imagens/logo.jpg') :
                    null
        ],
        'uploadUrl' => Url::to(['/site/upload']),
        'uploadExtraData' => [
            'album_id' => 20,
            'cat_id' => 'Nature'
        ],
        'maxFileCount' => 10
    ]
])
?>

<button>Submit</button>

<?php ActiveForm::end() ?>