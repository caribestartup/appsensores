<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MaquinaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maquina-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'maquina_id') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'modelo') ?>

    <?= $form->field($model, 'numero') ?>

    <?= $form->field($model, 'local') ?>

    <?php // echo $form->field($model, 'imagen') ?>

    <?php // echo $form->field($model, 'posx') ?>

    <?php // echo $form->field($model, 'posy') ?>

    <?php // echo $form->field($model, 'ancho') ?>

    <?php // echo $form->field($model, 'largo') ?>

    <?php // echo $form->field($model, 'mac') ?>

    <?php // echo $form->field($model, 'intervalo') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
