<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = $model[0]->identificador;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="turno-view">

    <p>
        <?php
            if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                echo Html::a(Yii::t('app', 'New Order'), ['lote/create', 'id' => $model[0]->id], ['class' => 'btn btn-success']);
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
                             'name',
                             'speed',
                             'state',
                             'amount',
                             'machine',

                            [
                            'class' => 'kartik\grid\ActionColumn',
                            'dropdown' => false,
                            'vAlign'=>'middle',
                            // 'template' => '{detail} {update}',
                            'template' => '{detail}{update}{assign}{performance}',
                            'urlCreator' => function($action, $lotes, $key, $index) {
                                    return Url::to([$action,'id'=>$key]);
                            },
                            'buttons'=>[
                                'assign' => function ($url, $lotes, $key) {
                                    if( Yii::$app->user->identity->getRole() == 'Operator' && $lotes["maquina_id"] == 0) {
                                        return Html::a('<span class="fa fa-desktop"></span>', ['lote/assign', 'id'=>$lotes["id"]],['title'=> Yii::t('app','Assign Machine')]);
                                    }
                                },
                                'detail' => function ($url, $lotes, $key) {
                                    if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                                        return Html::a('<span class="fa fa-eye"></span>', ['lote/view', 'id'=>$lotes["id"]],['title'=> Yii::t('app','Detail')]);
                                    }
                                },
                                'update' => function ($url, $lotes, $key) {
                                    if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                                        return Html::a('<span class="fa fa-pencil"></span>', ['lote/update', 'id'=>$lotes["id"]],['title'=> Yii::t('app','Update')]);
                                    }
                                },
                                'performance' => function ($url, $lotes, $key) {
                                    return Html::a('<span class="fa fa-bar-chart "></span>', ['lote/performance', 'id'=>$lotes["id"]],['title'=> Yii::t('app','Performance')]);
                                },
                            ],

                        ]


                        ],
                    ]); ?>

                </div>
            </div>



</div>
