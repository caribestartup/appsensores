<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TurnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Report Order by Incidences');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-index">

<a href="<?php echo Url::toRoute(['lote/performance','id' => $lote]) ?>" title="Performance">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-line-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/production','id' => $lote]) ?>" title="Productions vs Spends">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-bar-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/performancetime','id' => $lote]) ?>" title="Performance/Hours">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-pie-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/charttotales','id' => $lote]) ?>" title="Timing & Error">
  <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-area-chart"></i></button>
</a>

<a href="<?php echo Url::toRoute(['lote/report','id' => $lote]) ?>" title="Report">
  <button type="button" class="btn btn-default" style="margin-bottom: 10px"><i class="fa fa-file-o"></i></button>
</a>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'nombre',
            'name',
            'surname',
            'inicio',
            'fin',
            'descripcion',
            'time_min',

        ],
    ]); ?>
</div>
