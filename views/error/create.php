<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Error */

$this->title = Yii::t('app', 'Create Error');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Errors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
