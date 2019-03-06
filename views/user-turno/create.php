<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserTurno */

$this->title = Yii::t('app', 'Create User Turno');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Turnos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-turno-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
