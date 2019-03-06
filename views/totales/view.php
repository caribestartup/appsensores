<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Totales */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Totales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="totales-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'identificador',
            'mac',
            'programa',
            'fecha',
            'modelo',
            'serie',
            'camara',
            'hora_inicio',
            'hora_fin',
            'total',
            'total_error',
            'cliente',
            'operario',
            'turno',
            'total_tubos',
            'ampollas_tubos',
            'ampollas_previstas',
        ],
    ]) ?>

</div>
