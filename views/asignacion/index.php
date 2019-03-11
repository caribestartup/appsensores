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
            'name',
            'fecha',
            'state',
            'lot',

           [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{assign}{transfer}{unassign}{state}',
            'urlCreator' => function($action, $model, $key, $index) {
                    return Url::to([$action,'id'=>$key]);
            },
            'buttons'=>[
                'assign' => function ($url, $model, $key) {
                    if($model["lot"] == null){
                        return Html::a('<span class="fa fa-cubes"></span>', ['update', 'id'=>$model["id"]],['title'=> Yii::t('app','Assign Lot')]);
                    }
                },
                'transfer' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-sign-out"></span>', ['transfer', 'id'=>$model["id"]],['title'=> Yii::t('app','Transfer')]);
                },
                'unassign' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-close "></span>', ['asignacion/delete', 'id'=>$model["id"]],['data' => [
                        'confirm' => Yii::t('app','Do you want to unassign machine?'),
                        'method' => 'post',
                    ],'title'=>Yii::t('app', Yii::t('app','Unassign Machine'))]);
                },
                'state' => function ($url, $model, $key) {
                    if($model["state"] == "Activo"){
                        return Html::a('<span class="fa fa-pause "></span>', ['asignacion/states', 'id'=>$model["maquina_id"]],['data' => [
                            'confirm' => Yii::t('app','Do you want to PAUSE machine?'),
                            'method' => 'post',
                        ],'title'=>Yii::t('app', Yii::t('app','Pause Machine'))]);

                    }
                    else if($model["state"] == "Pausado") {
                        return Html::a('<span class="fa fa-play "></span>', ['asignacion/states', 'id'=>$model["maquina_id"]],['data' => [
                            'confirm' => Yii::t('app','Do you want to RESUME machine?'),
                            'method' => 'post',
                        ],'title'=>Yii::t('app', Yii::t('app','Resume Machine'))]);
                    }
                },
            ],

        ]
        ],
    ]); ?>
</div>
