<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = $usuario->name.' '.$usuario->surname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-view">

   <div class="row">
   		<div class="col-md-6" style="text-align: center;">

   			<div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-table"></i> <?=Yii::t('app','Work Shifts').', '.$usuario->name.' '.$usuario->surname ?></h3>
                </div>
                <div class="box-body">
   			
		   			 <?= GridView::widget([
				        'dataProvider' => $usuario->turnos,
				        'columns' => [
				           	array(
				           	    'label'=> Yii::t('app','Work Shifts'),
					            'attribute' => 'turno',
					            'value'=> 'turnoname',
					        ),
				           [
				    	'class' => 'kartik\grid\ActionColumn',
				   		'dropdown' => false,
				    	'vAlign'=>'middle',
				    	'template' => '{delete}',
				    	'urlCreator' => function($action, $model, $key, $index) { 
				            return Url::to([$action,'id'=>$key]);
				    	},
				    	'buttons'=>[
				        'delete' => function ($url, $model, $key) {
				        return Html::a('<span class="fa fa-trash"></span>', ['user-turno/delete', 'id'=>$model->id],['title'=>Yii::t('app','Remove Work Shifts'),
				        					'data' => [
				        					    'confirm' => Yii::t('app','Do you want remove this work shift'),
				        						'method' => 'post',
				        					],
				            			]);
				        },
				    	],      
				    	'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
				   
				     	],
				        ],
				    	]); ?>
		    	</div>
		    </div>

   		</div>
   		

   		<div class="col-md-6">

   			<div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-clock-o"></i> <?php echo Yii::t('app','Assign new work shift')?></h3>
                </div>
                <div class="box-body">
                	<?php $form = ActiveForm::begin(); ?>

   					<?= $form->field($user_turno, 'user')->hiddenInput()->label(false)?>

		   			<?= $form->field($user_turno, 'turno')->dropDownList($user_turno->dropdownturnos,['prompt'=> Yii::t('app', '--Select--')]) ?>

				    <div class="form-group">
				        <?= Html::submitButton($user_turno->isNewRecord ? Yii::t('app', 'Assign') : Yii::t('app', 'Update'), ['class' => $user_turno->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				    </div>

		    <?php ActiveForm::end(); ?>
                </div>
            </div>

   			
   		</div>
   </div>

</div>
