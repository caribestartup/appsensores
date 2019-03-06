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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'New Machine'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
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
                                array(
                                'attribute' => 'local',
                                'value'=> 'localname',
                                ),
                                //'imagen',
                                //'posx',
                                //'posy',
                                //'ancho',
                                //'largo',
                                //'mac',
                                //'intervalo',
                                //'fecha',
                                //'estado',
                    
                                [
                                    'class' => 'kartik\grid\ActionColumn',
                                    'dropdown' => false,
                                    'vAlign'=>'middle',
                                    'template' => '{update}',
                                    'urlCreator' => function($action, $model, $key, $index) {
                                    return Url::to([$action,'id'=>$key]);
                                    },
                                    'buttons'=>[
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-pencil"></span>', ['update', 'id'=>$model->maquina_id],['title'=> Yii::t('app','Update')]);
                                        },
                                        
                       
                                        ],
                                        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
                                        
                                        ],
                            ],
                        ]); ?>

                   
                    
                </div>
            </div>

   
</div>
