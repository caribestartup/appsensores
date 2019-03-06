<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Maquina */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="maquina-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modelo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'local')->dropDownList($model->dropdownlocales,['prompt'=> Yii::t('app', '--Seleccione--')]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'OK'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
