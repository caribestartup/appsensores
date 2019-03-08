<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Turno',
]) . $lote->id;
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Shifts'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->identificador, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->title = Yii::t('app', 'Update Lot for '.$pedido[0]->identificador);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'order '.$pedido[0]->identificador), 'url' => ['pedido/view', 'id'=>$pedido[0]->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-update">

    <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-body">
                 <?= $this->render('_form', [
                    'model' => $lote,
                    'pedido' => $pedido
                ]) ?>
            </div>
        </div>
</div>
