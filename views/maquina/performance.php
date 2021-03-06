<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use dosamigos\chartjs\ChartJs;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if( Yii::$app->user->identity->getRole() != 'Operator' ) {
  $this->params['breadcrumbs'][] = ['label' => $maqref->nombre, 'url' => ['maquina/index']];
}
$this->title = Yii::t('app', 'Performance');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <!-------------------------- MODAL BEGIN--------------------------->

        <?php
          Modal::begin([
          'header' => '<h2 style="text-align:center">'.Yii::t('app','Select date to show').'</h2>',
          'toggleButton' => ['label' => '<i class="fa fa-calendar"></i>', 'class' => 'btn btn-primary', 'style' => 'margin-bottom: 10px'],
          'id' => 'modal-range-day'
          ]);
        ?>

      <div class="row" style="margin-bottom: 8px">
        <div class="col-sm-6 col-sm-offset-3">
          <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($drange, 'range', [
                'addon'=>['prepend'=>['content'=>'<i class="fas fa-calendar-alt"></i>']],
                'options'=>['class'=>'drp-container form-group']
            ])->widget(DateRangePicker::classname(), [
                'useWithAddon'=>false
            ]); ?>
      </div>
        <div class="col-sm-6 col-sm-offset-3 form-group btn-modal">
           <?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
      </div>

        <?php Modal::end();?>

<!-------------------------- MODAL END--------------------------->

<a href="<?php echo Url::toRoute(['maquina/performance','id' => $maqref->maquina_id]) ?>">
  <button type="button" class="btn btn-default" style="margin-bottom: 10px"><i class="fa fa-line-chart" title="Performance & Errors"></i></button>
</a>

<a href="<?php echo Url::toRoute(['maquina/performancebar','id' => $maqref->maquina_id]) ?>">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-bar-chart" title="Performance Chart Bar"></i></button>
</a>

<a href="<?php echo Url::toRoute(['maquina/performancetime','id' => $maqref->maquina_id]) ?>">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-pie-chart" title="Performance on Times"></i></button>
</a>

     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 id="weight-title2" class="box-title"><?php echo Yii::t('app','Performance')?></h3>

              <div class="box-tools pull-right">

                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body hidden-xs">
              <div id="weight-chart2" class="chart">
                <?= ChartJs::widget([
					'type' => 'bar',
					'options' => [
    					'height' => 200,
    					'width' => 400,
    					'datasetFill' => false,
					],
					'data' => [
    					'labels' => $labelLast30Graph,
    					'datasets' => $last30Graph,
					],
					'clientOptions' => [
				        'legend' => [
				            'display' => true,
				            'position' => 'bottom',
				            'labels' => [
				                'fontSize' => 14,
				                'fontColor' => "#425062",
				            ]
				        ],
				        'hover' => [
				            'mode' => 'y',
				            'intersect' => true,
				        ],
				        'maintainAspectRatio' => false,
				    ],
					]);
				?>
              </div>
            </div>

            <div class="box-body hidden-md hidden-lg">
              <div id="weight-chart2" class="chart">
                <?= ChartJs::widget([
					'type' => 'bar',
					'options' => [
					'height' => 500,
					'width' => 400,
					'datasetFill' => false,
					],
					'data' => [
					'labels' => $labelLast30Graph,
					'datasets' => $last30Graph,
					],
					'clientOptions' => [
				        'legend' => [
				            'display' => true,
				            'position' => 'bottom',
				            'labels' => [
				                'fontSize' => 14,
				                'fontColor' => "#425062",
				            ]
				        ],
				        'hover' => [
				            'mode' => 'y',
				            'intersect' => true,
				        ],
				        'maintainAspectRatio' => false,
				    ],
					]);
				?>
              </div>
            </div>
            <!-- /.box-body -->
    </div>
</div>
