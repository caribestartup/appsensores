<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Turno',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identificador, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="turno-update">

    <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-body">
                 <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
</div>
