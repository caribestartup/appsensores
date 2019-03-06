<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\web\JsExpression;

$this->title = 'Gráficas';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-------------------------- MODAL BEGIN--------------------------->             
		
				<?php
					Modal::begin([
					'header' => '<h2>Seleccione la fecha a mostrar</h2>',
					'toggleButton' => ['label' => '<i class="fa fa-calendar"></i>', 'class' => 'btn btn-primary'],
					'id' => 'modal-range-day'
					]);
				?>
		
			<div class="row" style="margin-bottom: 8px">
				<div class="col-sm-6 col-sm-offset-3">
					<?= DateRangePicker::widget([
						'name'=>'date_range_2',
						'id' => 'date-range-chart',
						'presetDropdown'=>true,
						'hideInput'=>true
					]); ?>
				</div>
				<div class="col-sm-6 col-sm-offset-3 form-group btn-modal">
					<button id="date-range-day-btn" type="button" class="btn btn-primary">Seleccionar</button>
				</div>
			</div>
		
				<?php Modal::end();?>
		
<!-------------------------- MODAL END---------------------------> 
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">Activity</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">Timeline</a></li>
              <li class=""><a href="#settings" data-toggle="tab" aria-expanded="false">Settings</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <!-- Post -->
                
                <!-- /.post -->

                <!-- Post -->
                
                <!-- /.post -->

                <!-- Post -->
               
                <!-- /.post -->
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
               
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
</div>
          <!-- /.nav-tabs-custom -->
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 id="weight-title2" class="box-title">Total de rechazos</h3>

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
    <?php $count = 0; ?>
    <?php foreach ($errorsGraph as $eg ) { ?>
    	<?php $count ++; ?>
    	<div class="box box-primary">
            <div class="box-header with-border">
              <h3 id="weight-title2" class="box-title">Rechazos <?=$maqref::errorName($count); ?></h3>
              <?php new JsExpression('function(){alert("asd")}')?>

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
    <?php } ?>


          
        