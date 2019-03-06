<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

     <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> <?=Yii::t('app','User Data') ?></h3>
                </div>
                <div class="box-body">

       
            <?php $form = ActiveForm::begin(['id' => 'form-update','options' => ['enctype' => 'multipart/form-data']]); ?>
            <fieldset><legend><? Yii::t('app','Users') ?></legend>
                <div class="col-lg-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('app','Personal Info') ?></h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'name') ?>
					</div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'surname') ?>
                    </div>        
                </div>
            </div>
        

  
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('app','Account Details') ?></h3>
                </div>
                <div class="box-body">
                     <div class="col-lg-6">
                         <?= $form->field($model, 'username') ?>

                     </div>

                     <div class="col-lg-6">
                        
                          <?= $form->field($model, 'email') ?>
                     </div>

                   
                    
                </div>
            </div>
			
               
                
            </fieldset>
                <div class="form-group pull-right">
                    <?= Html::submitButton(Yii::t('app','Update'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
			                    
                </div>
            </div>
        </div>
    </div>
</div>
