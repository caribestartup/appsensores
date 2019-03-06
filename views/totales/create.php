<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Totales */

$this->title = Yii::t('app', 'Create Totales');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Totales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="totales-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
