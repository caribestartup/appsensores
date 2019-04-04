<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TurnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Clients');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-index">

    <p>
        <?php
            if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                echo Html::a(Yii::t('app', 'New Client'), ['pedido/create'], ['class' => 'btn btn-success']);
            }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'identificador',
           [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{view}{edit}{performance}',
            // 'template' => '{view} {edit}',
            'urlCreator' => function($action, $model, $key, $index) {
                    return Url::to([$action,'id'=>$key]);
            },
            'buttons'=>[

                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-eye "></span>', ['view', 'id'=>$model->id],['title'=> Yii::t('app','Detail')]);
                },
                'edit' => function ($url, $model, $key) {
                    if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                        return Html::a('<span class="fa fa-pencil "></span>', ['update', 'id'=>$model->id],['title'=> Yii::t('app','Edit')]);
                    }
                },
                'performance' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-bar-chart "></span>', ['performance', 'id'=>$model->id],['title'=> Yii::t('app','Performance')]);
                },
                // 'performance' => function ($url, $model, $key) {
                // return Html::a('<span class="fa fa-bar-chart "></span>', ['turno/performance', 'id'=>$model->id],['title'=> Yii::t('app','Performance')]);
                // },
            ],

        ]
        ],
    ]); ?>
</div>
