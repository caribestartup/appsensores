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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order '.$pedido[0]->identificador), 'url' => ['pedido/view', 'id'=>$pedido[0]->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <!-------------------------- MODAL BEGIN--------------------------->

        <?php
          Modal::begin([
              'header' => '<h2>'.Yii::t('app','Select date to show').'</h2>',
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
                <?= ChartJs::widget([
                        'type' => 'bar',
                        'options' => [

                        ],
                        'data' => [
                            // 'labels' => ['2016', '2017', '2018', '2019'],
                            'labels' => $labels,
                            'datasets' => [[
                                    'type'=>'bar',
                                    'label'=> 'Error',
                                    'yAxisID'=>"y-axis-0",
                                    'backgroundColor' => "rgba(217,83,79,0.75)",
                                    'data' => $data_error
                                ],
                                [
                                    'type'=>'bar',
                                    'label'=> 'Production',
                                    'yAxisID'=>"y-axis-0",
                                    'backgroundColor' => "rgba(92,184,92,0.75)",
                                    'data' => $data_prod
                                ],
                                // [
                                //     'type'=>'line',
                                //     'fill'=>false,
                                //     'label'=> 'c',
                                //     'yAxisID'=>"y-axis-0",
                                //     'backgroundColor' =>"rgba(51,51,51,0.5)",
                                //
                                //     'data' => [150, 200, 400, 500]
                                // ],
                                // [
                                //     'type'=>'line',
                                //     'fill'=>false,
                                //     'label'=> 'd',
                                //     'yAxisID'=>"y-axis-1",
                                //     'backgroundColor' => "rgba(151,187,205,0.5)",
                                //     'data' => [500, 300, 100, 0]
                                // ]
                            ]
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
                            'scales'=> [
                                'xAxes'=> [
                                    [
                                        'stacked'=>true,
                                    ],
                                ],
                                'yAxes'=>[
                                    [
                                        'stacked'=>true,
                                        'position'=>'left',
                                        'id'=> "y-axis-0",
                                    ],
                                ]
                            ],
                        ],
                    ]);
                ?>

              </div>
            </div>
            <!-- /.box-body -->
    </div>
</div>
