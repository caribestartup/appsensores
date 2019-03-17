<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;

?>

<div class="turno-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'maquina')->hiddenInput(['value' => $maquina[0]->maquina_id]) ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
