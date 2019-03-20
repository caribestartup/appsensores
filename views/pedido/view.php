<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = $model[0]->identificador;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="turno-view">

    <p>
        <?php
            if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                echo Html::a(Yii::t('app', 'New Lot'), ['lote/create', 'id' => $model[0]->id], ['class' => 'btn btn-success']);
            }
        ?>
    </p>

    <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">
                     <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            //'id',
                             'identificador',
                             'velocidad',
                             'estado',
                             'cantidad',
                             'Maquina',

                            [
                            'class' => 'kartik\grid\ActionColumn',
                            'dropdown' => false,
                            'vAlign'=>'middle',
                            // 'template' => '{detail} {update}',
                            'template' => '{update}{assign}{delete}',
                            'urlCreator' => function($action, $lotes, $key, $index) {
                                    return Url::to([$action,'id'=>$key]);
                            },
                            'buttons'=>[
                                'assign' => function ($url, $lotes, $key) {
                                    if( Yii::$app->user->identity->getRole() == 'Operator' && $lotes["maquina_id"] == 0) {
                                        return Html::a('<span class="fa fa-desktop"></span>', ['lote/assign', 'id'=>$lotes["id"]],['title'=> Yii::t('app','Assign Machine')]);
                                    }
                                },
                                'update' => function ($url, $lotes, $key) {
                                    if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                                        return Html::a('<span class="fa fa-pencil"></span>', ['lote/update', 'id'=>$lotes["id"]],['title'=> Yii::t('app','Update')]);
                                    }
                                },
                                'delete' => function ($url, $lotes, $key) {
                                    if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                                        return Html::a('<span class="fa fa-trash "></span>', ['lote/delete', 'id'=>$lotes["id"]],['data' => [
                                            'confirm' => Yii::t('app','Do you want to delete lot?'),
                                            'method' => 'post',
                                        ],'title'=>Yii::t('app', Yii::t('app','Delete Lot'))]);
                                    }
                                },
                            ],

                        ]


                        ],
                    ]); ?>

                </div>
            </div>



</div>
