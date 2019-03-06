<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Trabajo */

$this->title = Yii::t('app', 'Create Trabajo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trabajos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trabajo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
