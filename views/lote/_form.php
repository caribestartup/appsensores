<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Turno */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="turno-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pedido')->hiddenInput(['value' => $pedido[0]->id]) ?>
    <?= $pedido[0]->identificador ?>

    <?= $form->field($model, 'identificador')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'cantidad')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'velocidad')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'estado')->hiddenInput(['value' => "Activo"]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
