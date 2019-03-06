<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Maquina */

$this->title = Yii::t('app', 'New Machine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maquina-create">

	<div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">
                      <?= $this->render('_form', [
                         'model' => $model,
                    ]) ?>
                   
                    
                </div>
   </div>



   

</div>
