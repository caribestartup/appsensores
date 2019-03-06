<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Error */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="error-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'error')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
