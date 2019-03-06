<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */




$this->title = Yii::t('app','User register');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'New User');
?>
<div class="site-signup">
   

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> <?=Yii::t('app','New User') ?></h3>
                </div>
                <div class="box-body">




       
            <?php $form = ActiveForm::begin(['id' => 'form-signup','options' => ['enctype' => 'multipart/form-data']]); ?>
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
                         <?= $form->field($model, 'role')->dropDownList($model->dropdownroles,['prompt'=> Yii::t('app', '--Select--')]) ?>
                     
                     </div>

                     <div class="col-lg-6">

                        <?= $form->field($model, 'email') ?>                        

                     </div>

                   
                    
                </div>
            </div>
			               
                
            </fieldset>
                <div class="form-group pull-right">
                    <?= Html::submitButton(Yii::t('app','Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
			<div class="col-lg-offset-1" style="color:#999;">
			Password for new users will be <strong><code>123456</code></strong><br>
			Remember to change it later
			</div>
   

                    
                    
                </div>
            </div>
        </div>
    </div>

   
</div>
