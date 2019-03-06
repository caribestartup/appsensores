<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'd3d/style.css',
        'css/layout.css',
        'css/siteviews.css',
        'css/jquery-ui.css',
        'css/font-awesome.min.css',
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
        'css/jquery.freetrans.css',
    ];
    public $js = [
        'js/jquery-ui.js',
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'plugins/fastclick/fastclick.js',
        'js/app.min.js',
        'js/demo.js',
        'js/jquery.freetrans.js',
        'js/Matrix.js',
        'js/local.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
         'yii\bootstrap\BootstrapPluginAsset',
    ];
}
