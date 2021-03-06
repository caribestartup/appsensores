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

    <?= $form->field($model, 'identificador')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'cantidad')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'velocidad')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'ampolla')->textInput(['maxlength' => true])->label('Ampoules per tube') ?>
    <input type="hidden" id="hidden_array" name="array" value="">

    <div class="box box-primary box-solid" id='box'>
        <div class="box-header with-border">
            <h3 class="box-title"><?=Yii::t('app','Generic') ?></h3>
            <input type="button" class="btn btn-success pull-right" id="add_question" onClick="addQuestion()" value="+" /></div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    a = 0;
    var arr = [];

    function addQuestion(){
        a++;
        arr.push('question_'+a);
        var div = document.createElement('div');
        // div.setAttribute('class', 'form-inline');
        div.innerHTML = '<div class="box-body" id="box_question_'+a+'"><div class="col-lg-12"><div class="bd panel-wrapper mT-10 mB-10"><div class="layers"><div class="layer w-100 bgc-grey-200 p-15"><strong><span class="question"></span></strong><button id="question_'+a+'" onClick="deleteQuestion(this.id)" type="button" class="close pull-right" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="layer w-100 p-20 panel-body bgc-white"><div class="row mB-20"><div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><label>Question</label><input class="form-control question-i" name="question_'+a+'" value=""></div></div></div></div></div></div></div>';
        document.getElementById('box').appendChild(div);
        $('#hidden_array').val(arr);
    }

    function deleteQuestion(id){
        var indice = arr.indexOf(id);
        arr.splice(indice, 1);
        $('#box_'+id).remove();
        $('#hidden_array').val(arr);
    }
</script>
