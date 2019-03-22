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

// $this->title = Yii::t('app', $order->identificador);
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order '.$pedido[0]->identificador), 'url' => ['pedido/view', 'id'=>$pedido[0]->id]];
// $this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 id="weight-title2" class="box-title"><?php echo Yii::t('app', 'Performance') ?></h3>

              <div class="box-tools pull-right">

                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div id="weight-chart2" class="chart">
                <?php echo ChartJs::widget([
                    'type' => 'doughnut',
                    'data' => [
                        // 'labels' => ["test","test1","test2","Test3"],
                        'categories' => [
                            'Firefox',
                            'Chrome',
                        ],
                        'datasets' => [
                          [
                              'label' => 'Firefox',
                              // 'data' => [10,20,30,40],
                              // 'backgroundColor' => [],
                              'backgroundColor' => 'red',
                              'categories' => [
                                    'Firefox v58.0',
                                    'Firefox v57.0',
                                    'Firefox v56.0',
                                    'Firefox v55.0',
                                    'Firefox v54.0',
                                    'Firefox v52.0',
                                    'Firefox v51.0',
                                    'Firefox v50.0',
                                    'Firefox v48.0',
                                    'Firefox v47.0'
                                ],
                                'data' => [
                                    1.02,
                                    7.36,
                                    0.35,
                                    0.11,
                                    0.1,
                                    0.95,
                                    0.15,
                                    0.1,
                                    0.31,
                                    0.12
                                ]
                          ],
                          [
                              'label' => 'Chrome',
                              // 'data' => [10,20,30,40],
                              // 'backgroundColor' => [],
                              'backgroundColor' => 'blue',
                              'categories' => [
                                    'Chrome v65.0',
                                    'Chrome v64.0',
                                    'Chrome v63.0',
                                    'Chrome v62.0',
                                    'Chrome v61.0',
                                    'Chrome v60.0',
                                    'Chrome v59.0',
                                    'Chrome v58.0',
                                    'Chrome v57.0',
                                    'Chrome v56.0',
                                    'Chrome v55.0',
                                    'Chrome v54.0',
                                    'Chrome v51.0',
                                    'Chrome v49.0',
                                    'Chrome v48.0',
                                    'Chrome v47.0',
                                    'Chrome v43.0',
                                    'Chrome v29.0'
                                ],
                                'data' => [
                                    0.1,
                                    1.3,
                                    53.02,
                                    1.4,
                                    0.88,
                                    0.56,
                                    0.45,
                                    0.49,
                                    0.32,
                                    0.29,
                                    0.79,
                                    0.18,
                                    0.13,
                                    2.16,
                                    0.13,
                                    0.11,
                                    0.17,
                                    0.26
                                ]
                          ]
                        ],
                    ],
                    'options' => [
                        'height' => 200,
                        'width' => 500,
                    ],

                ]);?>


              </div>
            </div>
            <!-- /.box-body -->
    </div>
</div>
