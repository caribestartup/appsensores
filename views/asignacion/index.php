<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TurnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Machines Assigned');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-index">

    <p>
        <?= Html::a(Yii::t('app', 'Assign Machine'), ['maquina/assigne'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
          'id',
            'nombre',
            'fecha',

           [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{unassign}{edit}',
            'urlCreator' => function($action, $model, $key, $index) {
                    return Url::to([$action,'id'=>$key]);
            },
            'buttons'=>[

                'unassign' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-sign-out"></span>', ['view', 'id'=>$model["id"]],['title'=> Yii::t('app','Transfer')]);
                },
                'edit' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-close "></span>', ['update', 'id'=>$model["id"]],['title'=> Yii::t('app','Unassign')]);
                },
                // 'performance' => function ($url, $model, $key) {
                // return Html::a('<span class="fa fa-bar-chart "></span>', ['turno/performance', 'id'=>$model->id],['title'=> Yii::t('app','Performance')]);
                // },
            ],

        ]
        ],
    ]); ?>
</div>
