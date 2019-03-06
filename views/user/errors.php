<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use dosamigos\chartjs\ChartJs;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operators'), 'url' => ['user/operarios']];
$this->title = Yii::t('app', 'Errors ');
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
    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><?php echo Yii::t('app','Total Errors') ?></a></li>
              <?php $count = 0; ?>
    <?php foreach ($errorsGraph as $eg ) { ?>
      <?php $count ++; ?>
        <li class=""><a href="#timeline<?=$count; ?>" data-toggle="tab" aria-expanded="false"><?=$maqref::errorName($count); ?></a>
        </li>
  
         
    <?php } ?>
              
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <!-- Post -->
                
                <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 id="weight-title2" class="box-title"><?php echo Yii::t('app','Total Errors') ?></h3>

                  <div class="box-tools pull-right">

                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div id="weight-chart2" class="chart">
                    <?= ChartJs::widget([
              'type' => 'line',
              'options' => [
              'height' => 200,
              'width' => 400,
              'datasetFill' => false,
              ],
              'data' => [
              'labels' => $labelLast30Graph,
              'datasets' => $last30Graph,
              ]
              ]);
            ?>
                  </div>
                </div>
            <!-- /.box-body -->
           </div>
               
                <!-- /.post -->
              </div>

              <!-- /.tab-pane -->
              <?php $count = 0; ?>
          <?php foreach ($errorsGraph as $eg ) { ?>
            <?php $count ++; ?>
             <div class="tab-pane" id="timeline<?=$count; ?>">
                <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 id="weight-title2" class="box-title"><?=$maqref::errorName($count); ?> <?php echo Yii::t('app','Errors') ?></h3>

                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                      <div class="box-body">
                        <div id="weight-chart2" class="chart">
                          <?= ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                    'height' => 200,
                    'width' => 400,
                    'datasetFill' => false,
                    ],
                    'data' => [
                    'labels' => $labelLast30Graph,
                    'datasets' => $eg,
                    ]
                    ]);
                  ?>
                        </div>
                      </div>
                      <!-- /.box-body -->
              </div>
          </div>
          <?php } ?>
             
           
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
</div>



    

</div>

