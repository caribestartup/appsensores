<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Change Password');
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="user-form">
	
	<span>&nbsp;</span>
	<div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-key"></i> <?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">

					
						<?php $form = ActiveForm::begin(); ?>
						
						<?= $form->field($model, 'old_pass')->passwordInput() ?>

						<?= $form->field($model, 'password')->passwordInput() ?>
						
						<?= $form->field($model, 'password_repeat')->passwordInput() ?>

						<div class="form-group">
							<?= Html::submitButton(Yii::t('app', 'Cambiar'), ['class' => 'btn btn-success']) ?>
						</div>

						<?php ActiveForm::end(); ?>

				</div>
			</div>
		</div>
    </div>

</div>
