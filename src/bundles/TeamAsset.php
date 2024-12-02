<?php

namespace hesabro\trello\bundles;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class TeamAsset extends AssetBundle
{
    public $sourcePath = '@hesabro/trello/assets';

    public $css = [
        'iranSans/css/fontiran.css',
        'team/css/style.css',
        'team/css/bootstrap-rtl.css',
        'team/css/theme.css',
        'font-awesome/css/font-awesome.css',
    ];

    public $js = [
        'team/js/easing.js',
        'team/js/bootstrap.js',
        'team/js/jquery.flexisel.js',
        'team/js/moment-2.2.1.js',
        'team/js/responsiveslides.min.js',
        'team/js/site.js',
        'team/js/underscore-min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}