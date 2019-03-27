<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use Mpdf\Tag\H1;

/* @var $this yii\web\View */
/* @var $model app\models\Maquina */

$this->title = "Performance ".$model->nombre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row section-options">
        <p>
            <div class="col-md-2 col-md-offset-5" style="margin-bottom: 20px">
                <a class="option-reference"  href="<?= Url::toRoute(['/maquina/performance', 'id' => $model->maquina_id])?>"><img class="img-responsive" src="<?= Url::to('res/icon_charts.png')?>">
                    PERFORMANCE
                </a>
            </div>
        </p>
    </div>
<div class="maquina-view row">


   <div class="col-md-12" hidden>


        <div class="col-md-6 lined-top">
            <div class="row">
                <div class="col-md-3"><img class="img-responsive img-y185" src="<?= Url::to('res/o2.png') ?>"></div>
                <div class="col-md-3 section-info">
                    <p>O<sub>2</sub> CONSUMED</p>
                    <p>00000 L</p>
                    <p>O<sub>2</sub> TOTAL</p>
                    <p>00000 L</p>
                    <h3>100%</h3>
                </div>
                <div class="col-md-3"><img class="img-responsive img-y185" src="<?= Url::to('res/gas.png') ?>"></div>
                <div class="col-md-3 section-info">
                    <p>GAS CONSUMED</p>
                    <p>00000 L</p>
                    <p>GAS TOTAL</p>
                    <p>00000 L</p>
                    <h3>100%</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 lined-top">
            <img class="img-border-detail" src="<?= Url::to('res/detalles.png') ?>">
            <div class="row">
                <div class="col-md-3"><img class="img-responsive img-y185" src="<?= Url::to('res/tubos.png') ?>"></div>
                <div class="col-md-3 section-info">
                    <p>TUBES CONSUMED</p>
                    <p>00000</p>
                    <p>TUBES total</p>
                    <p>00000</p>
                    <h3>100%</h3>
                </div>
            </div>
        </div>
    </div>

        <?php $errores = $model->todayErrors()?>
        <?php if (count($errores) == 0) {
            // echo '<h1 style="text-align:center;margin-top:20px;color:red"> There are no error records today</h1>';
        }?>
        <?php foreach ($errores as $error) { ?>

            <div class="col-md-6" style="margin-top: 20px" hidden>
                 <img class="img-border-detail" src="<?= Url::to('res/detalles.png') ?>">
                <div class="row">
                    <div class="col-md-3">
                        <img class="img-responsive img-y185" src="<?= Url::to('res/ampolla.png') ?>">
                    </div>
                    <div class="col-md-9 lined-top">
                        <h3 style="margin-top: 10%"><?= strtoupper($error['nombre_ventana'])?> ERROR</h3>
                        <h3> <img class="machine-img" src="<?= Url::to('res/icon_no.png') ?>">
                            <?= $error['error']."<span class='text-primary'>/</span>".$error['terror']?>
                            <i class="fa fa-caret-right text-primary"></i>
                            <?= $model->percentage($error['error'], $error['terror'])."%"?>
                        </h3>
                    </div>
                </div>
            </div>

        <?php } ?>

   </div>






</div>
