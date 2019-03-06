<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ParcialesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parciales-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_totales') ?>

    <?= $form->field($model, 'ventana') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'total_error') ?>

    <?php // echo $form->field($model, 'nombre_ventana') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
