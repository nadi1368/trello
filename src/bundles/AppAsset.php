<?php

namespace hesabro\trello\bundles;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@hesabro/trello/assets';

    public $css = [
        'css/fonts.css',
        'css/style.css',
        //'css/bootstrap-combined.min.css',
        'css/bootstrap-editable.css',
        'css/bootstrap.min.css',
        'css/jquery-ui.css',
        'font-awesome/css/font-awesome.css',
        'css/animate.min.css',
        'css/sweetalert2.min.css',
    ];

    public $js = [
        //'js/socket.io.min.js',
//        'js/jquery-2.0.3.min.js',
        'js/bootstrap-editable.min.js',
        'js/bootstrap.new.min.js',
        'js/jquery-ui.js',
        'js/holder.min.js',
        'js/main.js',
        'js/project-status.js',// وضعیت های پروژه و لیست ها
        'js/project-task.js',// تسک های پروژه
        'js/label.js',// لیبل ها
        'js/comments.js', // کامنت
        'js/check-list.js', // چک لیست
        'js/due-date.js', // مهلت زمانی
        'js/attach.js', // آپلود فایل
        'js/trello-ajax-modal-popup.js',
        'js/sweetalert2.all.min.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}