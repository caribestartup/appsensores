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
use miloschuman\highcharts\Highcharts;

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
                <?php
                    echo Highcharts::widget([
                        'options' => [
                            'chart' => [
                                'borderColor'=>'#e5e5e5',
                                'type' => 'pie',
                            ],
                            'series' => [
                                // --------- inner layer of the pie
                                [
                                    'size' => '40%',
                                    'data' => [
                                        [
                                            'name' => "Chrome",
                                            'y' => 62.73,
                                        ],
                                        [
                                            'name' => "Firefox",
                                            'y' => 37.26
                                        ],
                                    ],
                                ],
                                // -------- second layer from the inside
                                [
                                    'size' => '100%',
                                    'innerSize' => '40%',
                                    'data' => [
                                        [
                                            'name' => 'Chrome v65.0',
                                            'y' => 0.1,
                                        ],
                                        [
                                            'name' => 'Chrome v64.0',
                                            'y' => 53.02,
                                        ],
                                        [
                                            'name' => 'Chrome v63.0',
                                            'y' => 1.4,
                                        ],
                                        [
                                            'name' => 'Chrome v62.0',
                                            'y' => 0.88,
                                        ],
                                        [
                                            'name' => 'Chrome v60.0',
                                            'y' => 0.56,
                                        ],
                                        [
                                            'name' => 'Chrome v59.0',
                                            'y' => 0.45,
                                        ],
                                        [
                                            'name' => 'Chrome v58.0',
                                            'y' => 0.49,
                                        ],
                                        [
                                            'name' => 'Chrome v57.0',
                                            'y' => 0.32,
                                        ],
                                        [
                                            'name' => 'Chrome v56.0',
                                            'y' => 0.29,
                                        ],
                                        [
                                            'name' => 'Chrome v55.0',
                                            'y' => 0.79,
                                        ],
                                        [
                                            'name' => 'Chrome v54.0',
                                            'y' => 0.18,
                                        ],
                                        [
                                            'name' => 'Chrome v51.0',
                                            'y' => 0.13,
                                        ],
                                        [
                                            'name' => 'Chrome v49.0',
                                            'y' => 2.16,
                                        ],
                                        [
                                            'name' => 'Chrome v48.0',
                                            'y' => 0.13,
                                        ],
                                        [
                                            'name' => 'Chrome v47.0',
                                            'y' => 0.11,
                                        ],
                                        [
                                            'name' => 'Chrome v43.0',
                                            'y' => 0.17,
                                        ],
                                        [
                                            'name' => 'Chrome v29.0',
                                            'y' => 0.26,
                                        ],
                                        [
                                            'name' => 'Firefox v58.0',
                                            'y' => 1.02
                                        ],
                                        [
                                            'name' => 'Firefox v57.0',
                                            'y' => 7.36
                                        ],
                                        [
                                            'name' => 'Firefox v56.0',
                                            'y' => 0.35
                                        ],
                                        [
                                            'name' => 'Firefox v56.0',
                                            'y' => 0.12
                                        ],
                                        [
                                            'name' => 'Firefox v54.0',
                                            'y' => 0.11
                                        ],
                                        [
                                            'name' => 'Firefox v52.0',
                                            'y' => 0.1
                                        ],
                                        [
                                            'name' => 'Firefox v51.0',
                                            'y' => 0.95
                                        ],
                                        [
                                            'name' => 'Firefox v50.0',
                                            'y' => 0.15
                                        ],
                                        [
                                            'name' => 'Firefox v48.0',
                                            'y' => 0.1
                                        ],
                                        [
                                            'name' => 'Firefox v47.0',
                                            'y' => 0.31
                                        ],
                                    ],
                                ],
                                // ----- add as manny layers as you need to

                            // ..
                            ],
                        ],
                    ]);
                ?>
                </div>
            </div>
            <!-- /.box-body -->
    </div>
</div>
