<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TurnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Work Shifts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">
        <div class="col-md-2 col-md-offset-5 section-options">
            <a class="option-reference"  href="<?= Url::toRoute('/turno/create')?>"><img class="img-responsive" src="<?= Url::to('res/icon_gauge.png')?>"><?php echo Yii::t('app','NEW WORK SHIFT') ?></a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'identificador',
            'inicio',
            'fin',
           [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{view}{edit}{performance}',
            'urlCreator' => function($action, $model, $key, $index) { 
                    return Url::to([$action,'id'=>$key]);
            },
            'buttons'=>[
                
                'view' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-eye "></span>', ['view', 'id'=>$model->id],['title'=> Yii::t('app','Workers')]);
                },
                'edit' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-pencil "></span>', ['update', 'id'=>$model->id],['title'=> Yii::t('app','Edit')]);
                },
                'performance' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-bar-chart "></span>', ['turno/performance', 'id'=>$model->id],['title'=> Yii::t('app','Performance')]);
                },
            ],       
          
        ]
        ],
    ]); ?>
</div>
