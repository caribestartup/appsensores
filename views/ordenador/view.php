<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = $model[0]->identificador;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders: '.$pedido[0]->identificador), 'url' => ['pedido/view', 'id'=>$pedido[0]->id]];
$this->params['breadcrumbs'][] = $this->title;

// print_r($lotes);
?>
<div class="turno-view">

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
                             // 'surname',
                            //  array(
                            // 'attribute' => 'role',
                            // 'value'=> 'r',
                            // ),
                            //  ['class' => '\kartik\grid\BooleanColumn',
                            //  'attribute' => 'status',
                            // 'trueLabel' => Yii::t('app','Yes'),
                            // 'falseLabel' => Yii::t('app','No')
                            // ],
                            //'username',
                            //'auth_key',
                            //'password_hash',
                            //'password_reset_token',
                            // 'email:email',
                            // 'status',
                            // 'created_at',
                            // 'updated_at',
                            // 'avatar',
                            [
                            'class' => 'kartik\grid\ActionColumn',
                            'dropdown' => false,
                            'vAlign'=>'middle',
                            'template' => '{performance} {error}',
                            'urlCreator' => function($action, $model, $key, $index) {
                                    return Url::to([$action,'id'=>$key]);
                            },
                            'buttons'=>[

                                'performance' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-bar-chart "></span>', ['user/performance', 'id'=>$model->id],['title'=> Yii::t('app','Performance')]);
                                },
                                'error' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-line-chart "></span>', ['user/errors', 'id'=>$model->id],['title'=> Yii::t('app','Errors')]);
                                },
                            ],

                        ]


                        ],
                    ]); ?>



                </div>
            </div>



</div>
