<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Maquina */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maquina-view">

   

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->maquina_id], ['class' => 'btn btn-primary']) ?>
        <? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->maquina_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-body">
                       <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                //'maquina_id',
                                'nombre',
                                'modelo',
                                'numero',
                                'localname',
                                //'imagen',
                                //'posx',
                                //'posy',
                                //'ancho',
                                //'largo',
                                //'mac',
                                //'intervalo',
                                //'fecha',
                                //'estado',
                            ],
                        ]) ?>
                   
                    
                </div>
   </div>

    

</div>
