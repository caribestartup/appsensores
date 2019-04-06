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
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-bar-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/performancetime','id' => $order->id]) ?>" title="Performance/Hours">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-pie-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/charttotales','id' => $order->id]) ?>" title="Timing & Error">
  <button type="button" class="btn btn-default" style="margin-bottom: 10px"><i class="fa fa-area-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/report','id' => $order->id]) ?>" title="Report">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-file-o"></i></button>
</a>
        <div class="box box-primary">
               <div class="box-header with-border">
                 <h3 id="weight-title2" class="box-title"><?php echo Yii::t('app', 'Time') ?></h3>

                 <div class="box-tools pull-right">

                   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                   </button>
                   <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                 </div>
               </div>
               <div class="box-body hidden-xs">
                 <div id="a" class="chart">
                   <?= ChartJs::widget([
                         'type' => 'bar',
                         'options' => [
                             'height' => 200,
                             'width' => 400,
                             'datasetFill' => false,
                         ],
                         'data' => [
                             'labels' => [''],
                             'datasets' => $data_time,
                         ],
                         'clientOptions' => [
                           'tooltips'=> [
                                'callbacks'=> [
                                    'label'=> new JsExpression("
                                        function(t, d) {
                                            var data = d.datasets[t.datasetIndex].data[t.index];
                                            var labels = d.datasets[t.datasetIndex].label
                                            var minutes = Math.floor(data);
                                               var seconds = parseInt((data-Math.floor(data))*60);
                                               if (seconds < 10) {
                                                   seconds = '0'+seconds;
                                               }
                                               if (minutes < 10) {
                                                   minutes = '0'+minutes;
                                               }
                                               return labels + ': ' + minutes +':'+ seconds;
                                    }")
                                ]
                            ],
                            'scales' => [
                                'yAxes' => [
                                    [
                                        'ticks' => [
                                            'beginAtZero' => true,
                                        ],
                                    ],
                                ],
                            ],
                            ]
                       ]);
                   ?>

                 </div>
               </div>

               <div class="box-body hidden-md hidden-lg">
                 <div id="weight-chart2" class="chart">
                     <?= ChartJs::widget([
                           'type' => 'bar',
                           'options' => [
                               'height' => 400,
                               'width' => 400,
                               'datasetFill' => false,
                           ],
                           'data' => [
                               'labels' => [''],
                               'datasets' => $data_time,
                           ],
                           'clientOptions' => [
                             'tooltips'=> [
                                  'callbacks'=> [
                                      'label'=> new JsExpression("
                                          function(t, d) {
                                              var data = d.datasets[t.datasetIndex].data[t.index];
                                              var labels = d.datasets[t.datasetIndex].label
                                              var minutes = Math.floor(data);
                                                 var seconds = parseInt((data-Math.floor(data))*60);
                                                 if (seconds < 10) {
                                                     seconds = '0'+seconds;
                                                 }
                                                 if (minutes < 10) {
                                                     minutes = '0'+minutes;
                                                 }
                                                 return labels + ': ' + minutes +':'+ seconds;
                                      }")
                                  ]
                              ],
                              'scales' => [
                                  'yAxes' => [
                                      [
                                          'ticks' => [
                                              'beginAtZero' => true,
                                          ],
                                      ],
                                  ],
                              ],
                              ]
                         ]);
                     ?>

                 </div>
               </div>


               <!-- /.box-body -->
       </div>

       <div class="box box-primary">
              <div class="box-header with-border">
                <h3 id="weight-title2" class="box-title"><?php echo Yii::t('app', 'Error') ?></h3>

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
                            'labels' => $label_error,
                            'datasets' => [
                                [
                                    'backgroundColor'=> $label_color,
                                    'data' => $data_error,
                                ]
                            ]
                        ],
                    ]);
                    ?>

                </div>
              </div>

              <div class="box-body hidden-md hidden-lg">
                <div id="weight-chart2" class="chart">
                    <?= ChartJs::widget([
                        'type' => 'pie',
                        'options' => [
                            'height' => 400,
                            'width' => 400,
                        ],
                        'data' => [
                            'labels' => $label_error,
                            'datasets' => [
                                [
                                    'backgroundColor'=> $label_color,
                                    'data' => $data_error,
                                ]
                            ]
                        ],
                    ]);
                    ?>

                </div>
              </div>
              <!-- /.box-body -->
      </div>
    </div>
</div>
