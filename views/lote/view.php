<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = $lote->identificador;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders: '.$lote->identificador), 'url' => ['pedido/view', 'id'=>$lote->pedido]];
$this->params['breadcrumbs'][] = $this->title;

// print_r($lotes);
?>
<div class="turno-view">

<p>
    <?= Html::a(Yii::t('app', 'Delete'), ['lote/delete', 'id'=>$lote->id],['class' => 'btn btn-danger','data' => [
        'confirm' => Yii::t('app','Do you want to delete lot?'),
        'method' => 'post',
    ],'title'=>Yii::t('app', Yii::t('app','Delete Lot'))]); ?>
</p>
    <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>

                <div class="box-body">
                       <?= DetailView::widget([
                            'model' => $lote,
                            'attributes' => [
                                'identificador',
                                'velocidad',
                                'cantidad',
                                'estado',
                            ],
                        ]) ?>
                </div>

                <div class="box-body">
                     <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            //'id',
                             'pregunta',
                             'respuesta',
                             'fecha',

                        ],
                    ]); ?>

                </div>
            </div>

</div>
