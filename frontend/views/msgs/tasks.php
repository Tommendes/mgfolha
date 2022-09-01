<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<link href="/frontend/assets/8a3cf642/css/bootstrap.css" rel="stylesheet">
<link href="/frontend/frontend/assets/css/site.css" rel="stylesheet">
<link href="/frontend/assets/968df183/css/font-awesome.min.css" rel="stylesheet">
<link href="/frontend/assets/3eca9711/css/AdminLTE.min.css" rel="stylesheet">
<link href="/frontend/assets/3eca9711/css/skins/_all-skins.min.css" rel="stylesheet">     

<?=
yii::t('yii', '<div class="alert" style="background-color: '
        . '{caption_color_id};" role="alert">'
        . '<span style="color: #ffffff;">{caption}</span></div>'
        . $model->body, [
    'caption' => $model->caption,
    'caption_color_id' => $model->caption_color_id
]);
?>