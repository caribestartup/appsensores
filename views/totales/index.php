<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TotalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Totales');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="totales-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Totales'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'identificador',
            'mac',
            'programa',
            'fecha',
            //'modelo',
            //'serie',
            //'camara',
            //'hora_inicio',
            //'hora_fin',
            //'total',
            //'total_error',
            //'cliente',
            //'operario',
            //'turno',
            //'total_tubos',
            //'ampollas_tubos',
            //'ampollas_previstas',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
