<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Totales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="totales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'identificador')->textInput() ?>

    <?= $form->field($model, 'mac')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'programa')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'modelo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serie')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'camara')->textInput() ?>

    <?= $form->field($model, 'hora_inicio')->textInput() ?>

    <?= $form->field($model, 'hora_fin')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'total_error')->textInput() ?>

    <?= $form->field($model, 'cliente')->textInput() ?>

    <?= $form->field($model, 'operario')->textInput() ?>

    <?= $form->field($model, 'turno')->textInput() ?>

    <?= $form->field($model, 'total_tubos')->textInput() ?>

    <?= $form->field($model, 'ampollas_tubos')->textInput() ?>

    <?= $form->field($model, 'ampollas_previstas')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
