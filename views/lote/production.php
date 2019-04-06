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

$this->title = Yii::t('app', $order->identificador);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Client '.$pedido[0]->identificador), 'url' => ['pedido/view', 'id'=>$pedido[0]->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

<a href="<?php echo Url::toRoute(['lote/performance','id' => $order->id]) ?>" title="Performance">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-line-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/production','id' => $order->id]) ?>" title="Productions vs Spends">
  <button type="button" class="btn btn-default" style="margin-bottom: 10px"><i class="fa fa-bar-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/performancetime','id' => $order->id]) ?>" title="Performance/Hours">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-pie-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/charttotales','id' => $order->id]) ?>" title="Timing & Error">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-area-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/report','id' => $order->id]) ?>" title="Report">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-file-o"></i></button>
</a>

     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 id="weight-title2" class="box-title"><?php echo Yii::t('app', 'Productions vs Spends') ?></h3>

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
                        'labels' => ['Ampullas', 'Tube'],
                        'datasets' => [
                            [
                                'type'=>'bar',
                                'label'=> 'Production Theoric',
                                'backgroundColor' => "rgba(92,184,92,0.75)",
                                'data' => [$prod_theoric, 0, 0]
                            ],
                            [
                                'type'=>'bar',
                                'label'=> 'Tube Theoric',
                                'backgroundColor' => "rgba(0,14,92,0.75)",
                                'data' => [0, $tube_theoric, 0]
                            ],
                            [
                                'type'=>'bar',
                                'label'=> 'Production Real'.$realTime,
                                'backgroundColor' => "rgba(92,184,255,0.75)",
                                'data' => [$prod_real, 0, 0]
                            ],
                            [
                                'type'=>'bar',
                                'label'=> 'Tube Real'.$realTime,
                                'backgroundColor' => "rgba(0,125,232,0.75)",
                                'data' => [0, $tube_real, 0]
                            ],
                            [
                                'type'=>'bar',
                                'label'=> 'Excess Production',
                                'backgroundColor' => "rgba(217,83,79,0.75)",
                                'data' => [$excess_production, 0, 0]
                            ],
                            [
                                'type'=>'bar',
                                'label'=> 'Excess Tube',
                                'backgroundColor' => "rgba(217,83,79,0.75)",
                                'data' => [0, $excess_production, 0]
                            ]
                        ],
                    ],
                    'clientOptions' => [
                        'options' => [
                            'title'=>[
                                'display' => true,
                            ],
                            'tooltips'=>[
                                'mode'=> 'label'
                            ],
                            'responsive'=> true,
                        ],
                        'legend' => [
                            'display' => true,
                            'position' => 'bottom',
                            'labels' => [
                                'fontSize' => 14,
                                'fontColor' => "#425062",
                            ]
                        ],
                        'maintainAspectRatio' => true,
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
                          'height' => 200,
                          'width' => 400,
                          'datasetFill' => false,
                      ],
                      'data' => [
                          'labels' => ['Ampullas', 'Tube'],
                          'datasets' => [
                              [
                                  'type'=>'bar',
                                  'label'=> 'Production Theoric',
                                  'backgroundColor' => "rgba(92,184,92,0.75)",
                                  'data' => [$prod_theoric, 0, 0]
                              ],
                              [
                                  'type'=>'bar',
                                  'label'=> 'Tube Theoric',
                                  'backgroundColor' => "rgba(0,14,92,0.75)",
                                  'data' => [0, $tube_theoric, 0]
                              ],
                              [
                                  'type'=>'bar',
                                  'label'=> 'Production Real'.$realTime,
                                  'backgroundColor' => "rgba(92,184,255,0.75)",
                                  'data' => [$prod_real, 0, 0]
                              ],
                              [
                                  'type'=>'bar',
                                  'label'=> 'Tube Real'.$realTime,
                                  'backgroundColor' => "rgba(0,125,232,0.75)",
                                  'data' => [0, $tube_real, 0]
                              ],
                              [
                                  'type'=>'bar',
                                  'label'=> 'Excess Production',
                                  'backgroundColor' => "rgba(217,83,79,0.75)",
                                  'data' => [$excess_production, 0, 0]
                              ],
                              [
                                  'type'=>'bar',
                                  'label'=> 'Excess Tube',
                                  'backgroundColor' => "rgba(217,83,79,0.75)",
                                  'data' => [0, $excess_production, 0]
                              ]
                          ],
                      ],
                      'clientOptions' => [
                          'options' => [
                              'title'=>[
                                  'display' => true,
                              ],
                              'tooltips'=>[
                                  'mode'=> 'label'
                              ],
                              'responsive'=> true,
                          ],
                          'legend' => [
                              'display' => true,
                              'position' => 'bottom',
                              'labels' => [
                                  'fontSize' => 14,
                                  'fontColor' => "#425062",
                              ]
                          ],
                          'maintainAspectRatio' => true,
                      ],
                      ]);
                  ?>

              </div>
            </div>

            <!-- /.box-body -->
    </div>
</div>
