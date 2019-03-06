<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Turno */

$this->title = Yii::t('app', 'New Lot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="turno-create">


	        <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">
                     <?= $this->render('_form', [
                        'model' => $model,
												'pedido' => $pedido
                    ]) ?>



                </div>
            </div>


</div>
