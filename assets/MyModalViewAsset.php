<?php

namespace app\assets;

use yii\web\AssetBundle;

class MyModalViewAsset extends AssetBundle
{
    public $sourcePath = '@app/views/my-modal';
    public $js = [
        'my-modal.js',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}