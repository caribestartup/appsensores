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

    <?php echo $form->field($model, 'inicio')->widget(TimePicker::classname(), ['pluginOptions' =>['showMeridian'=>false, 'minuteStep' => 5]]); ?>

    <?php echo $form->field($model, 'fin')->widget(TimePicker::classname(), ['pluginOptions' =>['showMeridian'=>false, 'minuteStep' => 5]]); ?>

    <?= $form->field($model, 'identificador')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>
