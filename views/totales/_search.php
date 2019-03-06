<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TotalesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="totales-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'identificador') ?>

    <?= $form->field($model, 'mac') ?>

    <?= $form->field($model, 'programa') ?>

    <?= $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'modelo') ?>

    <?php // echo $form->field($model, 'serie') ?>

    <?php // echo $form->field($model, 'camara') ?>

    <?php // echo $form->field($model, 'hora_inicio') ?>

    <?php // echo $form->field($model, 'hora_fin') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'total_error') ?>

    <?php // echo $form->field($model, 'cliente') ?>

    <?php // echo $form->field($model, 'operario') ?>

    <?php // echo $form->field($model, 'turno') ?>

    <?php // echo $form->field($model, 'total_tubos') ?>

    <?php // echo $form->field($model, 'ampollas_tubos') ?>

    <?php // echo $form->field($model, 'ampollas_previstas') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
