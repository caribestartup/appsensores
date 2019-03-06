<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Parciales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parciales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_totales')->textInput() ?>

    <?= $form->field($model, 'ventana')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'total_error')->textInput() ?>

    <?= $form->field($model, 'nombre_ventana')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
