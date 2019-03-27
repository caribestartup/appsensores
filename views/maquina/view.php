<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Maquina */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maquina-view">

  <?php if( Yii::$app->user->identity->getRole() != 'Operator' ) { ?>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->maquina_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'New PC'), ['ordenador/create', 'id' => $model->maquina_id], ['class' => 'btn btn-info']) ?>
    </p>
  <?php } ?>
    <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">
                       <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'nombre',
                                'modelo',
                                'numero',
                                'localname',
                            ],
                        ]) ?>
                </div>

                <div class="box-body">
                     <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            //'id',
                             'uuid',

                            [
                            'class' => 'kartik\grid\ActionColumn',
                            'dropdown' => false,
                            'vAlign'=>'middle',
                            // 'template' => '{detail} {update}',
                            'template' => '{update}{delete}',
                            'urlCreator' => function($action, $ordenador, $key, $index) {
                                    return Url::to([$action,'id'=>$key]);
                            },
                            'buttons'=>[
                                'update' => function ($url, $ordenador, $key) {
                                    if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                                        return Html::a('<span class="fa fa-pencil"></span>', ['ordenador/update', 'id'=>$ordenador["ordenador_id"]],['title'=> Yii::t('app','Update')]);
                                    }
                                },
                                'delete' => function ($url, $ordenador, $key) {
                                    if( Yii::$app->user->identity->getRole() != 'Operator' ) {
                                        return Html::a('<span class="fa fa-trash "></span>', ['ordenador/delete', 'id'=>$ordenador["ordenador_id"]],['data' => [
                                            'confirm' => Yii::t('app','Do you want to delete PC?'),
                                            'method' => 'post',
                                        ],'title'=>Yii::t('app', Yii::t('app','Delete PC'))]);
                                    }
                                },
                            ],

                        ]


                        ],
                    ]); ?>

                </div>
   </div>



</div>
