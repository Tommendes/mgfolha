<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web/frontend/assets';
    public $css = [
        'css/animate.min.css',
        'css/custom.css',
//        'css/fa.min.css',
//        'css/fa.solid.min.css',
        'css/loader.min.css',
        'css/pnotify.custom.min.css',
        'css/site.css',
        'css/dev.css',
    ];
    public $js = [
        'js/ajax-modal-popup.js',
        'js/custom.js',
        'js/pnotify.custom.min.js',
        'js/dev.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];

}
