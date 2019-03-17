<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'PC',
]) . $ordenador->ordenador_id;
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Shifts'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->identificador, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->title = Yii::t('app', 'Update PC for '.$maquina[0]->nombre);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machine '.$maquina[0]->maquina_id), 'url' => ['maquina/view', 'id'=>$maquina[0]->maquina_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-update">

    <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-body">
                 <?= $this->render('_form', [
                    'model' => $ordenador,
                    'maquina' => $maquina
                ]) ?>
            </div>
        </div>
</div>
