<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = "Process";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

  <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              	<li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><?php echo Yii::t('app','Totals')?></a></li>
    			<li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false"><?php echo Yii::t('app','Partials')?></a>
    		</li>            
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <!-- Post -->
                	 <?= GridView::widget([
				        'dataProvider' => $dataProviderTotales,
				        //'filterModel' => $searchModelTotales,
				        'columns' => [
				           array(
								'label'=>Yii::t('app','Machine'),
								'attribute' => 'mac',
								'value'=> 'machine',
								),
				           'programa',
				           'hora_inicio',
				           'hora_fin',
				           array(
								'label'=>Yii::t('app','Operator'),
								'attribute' => 'operario',
								'value'=> 'user',
								),
				           array(
								'label'=>Yii::t('app','Work Shift'),
								'attribute' => 'turno',
								'value'=> 'turnon',
								),
				          
				           'total',
				            'total_error',
				          /* [
					            'attribute' => 'produccion',
					            'format' => 'raw',
					            'value' => function ($model) {
					                return $model->programa - $model->total;
					            },
					        ],*/
				           
				          
				        ],
				    'autoXlFormat'=>true,
				    'responsive'=>true,
				    'hover'=>true,
				    'condensed'=>true,
				    'floatHeader'=>true,
				    'bordered'=>true,
				   'toolbar'=>['{export}',
				              '{toggleData}',
				    ],
				   'showPageSummary'=>true,
				   'panel'=>[
				       'heading'=>'<h3 class="panel-title"><i class="fa fa-th-list"></i> '. Yii::t('app','Totals').' </h3>',
				    'type'=>'primary',

				    ],
				    'pjax'=>true,
				    'export'=>[
				        'fontAwesome'=>true,
				        'showConfirmAlert'=>false,
				        'target'=>GridView::TARGET_BLANK,

				    ],
				    ]); ?>
                <!-- /.post -->
              </div>

              <!-- /.tab-pane -->
          
			    <div class="tab-pane" id="timeline">
					  <?= GridView::widget([
				        'dataProvider' => $dataProviderParciales,
				        //'filterModel' => $searchModelParciales,
				        'columns' => [
				            'turno',
				            'operario',
				            'maquina',
				            'fecha',
				            array(
								'label'=>Yii::t('app','Error'),
								'attribute' => 'nombre_ventana',
								'value'=> 'nombre_ventana',
								),
				            'total_error',
				           
				          
				        ],
				    'autoXlFormat'=>true,
				    'responsive'=>true,
				    'hover'=>true,
				    'condensed'=>true,
				    'floatHeader'=>true,
				    'bordered'=>true,
				   'toolbar'=>['{export}',
				              '{toggleData}',
				    ],
				   'showPageSummary'=>true,
				   'panel'=>[
				       'heading'=>'<h3 class="panel-title"><i class="fa fa-th-list"></i> '. Yii::t('app','Partials').'</h3>',
				    'type'=>'primary',

				    ],
				    'pjax'=>true,
				    'export'=>[
				        'fontAwesome'=>true,
				        'showConfirmAlert'=>false,
				        'target'=>GridView::TARGET_BLANK,

				    ],
				    ]); ?>  	
				</div>

             
           
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
</div>
          <!-- /.nav-tabs-custom -->
   

</div>
