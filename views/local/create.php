<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Local */

$this->title = Yii::t('app', 'New Shed');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sheds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="local-create">

   

    <div class="row">
    	<div class="col-md-6 col-md-offset-3">
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
    </div>

</div>
