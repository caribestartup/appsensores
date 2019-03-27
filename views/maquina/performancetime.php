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

$this->title = Yii::t('app', $maquina->nombre);
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Performance Time for'.$maquina->nombre), 'url' => ['pedido/view', 'id'=>$pedido[0]->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

<a href="<?php echo Url::toRoute(['maquina/performance','id' => $maquina->maquina_id]) ?>">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-line-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['maquina/performancebar','id' => $maquina->maquina_id]) ?>">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-bar-chart"></i></button>
</a>

     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 id="weight-title2" class="box-title"><?php echo Yii::t('app', 'Performance') ?></h3>

              <div class="box-tools pull-right">

                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body hidden-xs">
              <div id="weight-chart2" class="chart">
                  <?= ChartJs::widget([
                      'type' => 'pie',
                      'options' => [
                          'height' => 200,
      					  'width' => 400,
                      ],
                      'data' => [
                          'labels' => ['Real Time', 'Rest Time', 'Other Time', 'Error Time'],
                          'datasets' => [
                              [
                                  // 'backgroundColor' => "rgba(92,184,92,0.75)"
                                  'backgroundColor'=> ['#ff6384', '#cc65fe', '#ffce56'],
                                  'data' => [$real, $rest, $other, $error],
                              ]
                          ]
                      ]
                  ]);
                  ?>

              </div>
            </div>

            <div class="box-body hidden-md hidden-lg">
              <div id="weight-chart2" class="chart">
                  <?= ChartJs::widget([
                      'type' => 'pie',
                      'options' => [
                          'height' => 500,
      					'width' => 400,
                      ],
                      'data' => [
                          'labels' => ['Real Time', 'Rest Time', 'Other Time', 'Error Time'],
                          'datasets' => [
                              [
                                  // 'backgroundColor' => "rgba(92,184,92,0.75)"
                                  'backgroundColor'=> ['#ff6384', '#cc65fe', '#ffce56'],
                                  'data' => [$real, $rest, $other, $error],
                              ]
                          ]
                      ]
                  ]);
                  ?>

              </div>
            </div>
            <!-- /.box-body -->
    </div>
</div>
