<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MaquinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Machines');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maquina-index">

    <?php if( Yii::$app->user->identity->getRole() != 'Operator' ) { ?>

    <p>
        <?= Html::a(Yii::t('app', 'New Machine'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php } ?>

    <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('app','Machines') ?></h3>
                </div>
                <div class="box-body">
                      <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            //'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                //'maquina_id',
                                'nombre',
                                'modelo',
                                'numero',
                                'localName',
                                [
                                    'class' => 'kartik\grid\ActionColumn',
                                    'dropdown' => false,
                                    'options'=> ['style'=>'width:8.92%;'],
                                    'vAlign'=>'middle',
                                    'template' => '{detail}{update}{performance}{unassign}',
                                    'urlCreator' => function($action, $model, $key, $index) {
                                    return Url::to([$action,'id'=>$key]);
                                    },
                                    'buttons'=>[
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-pencil"></span>', ['update', 'id'=>$model["maquina_id"]],['title'=> Yii::t('app','Update')]);
                                        },
                                        'detail' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-eye"></span>', ['view', 'id'=>$model["maquina_id"]],['title'=> Yii::t('app','Detail')]);
                                        },
                                        'unassign' => function ($url, $model, $key) {
                                            if($model["show"] == 0) {
                                                return Html::a('<span class="fa fa-close "></span>', ['unassigne', 'id'=>$model["tum"]],['data' => [
                                                    'confirm' => Yii::t('app','Do you want to unassign machine?'),
                                                    'method' => 'post',
                                                ],'title'=>Yii::t('app', Yii::t('app','Unassign Machine'))]);
                                            }
                                        },
                                        'performance' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-bar-chart "></span>', ['/maquina/performance', 'id'=>$model["maquina_id"]],['title'=> Yii::t('app','Performance')]);
                                        },
                                    ],
                                    'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
                                ],
                            ],
                        ]); ?>



                </div>
            </div>


</div>
