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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <a href="<?php echo Url::toRoute(['lote/performance','id' => $lote]) ?>">
      <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-line-chart"></i></button>
    </a>

    <a href="<?php echo Url::toRoute(['lote/performancetime','id' => $lote]) ?>">
      <button type="button" class="btn btn-primary" style="margin-bottom: 10px"><i class="fa fa-pie-chart"></i></button>
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
