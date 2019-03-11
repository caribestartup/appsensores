<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MaquinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Assign Machine to lot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machines Assigned'), 'url' => ['asignacion/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="maquina-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="maquina-form">

        <?php $form = ActiveForm::begin([
                  'method' => 'post',
                  'action' => ['asignacion/update', 'id' => $tum->id],
                  'id' => 'form',
              ]); ?>

        <?php
            // $var=array();
            // foreach ($turnos as $key => $value) {
            //     // code...
            //     $tur = [ $value['id'] => $value['identificador']];
            //
            //     array_push($var, $tur);
            // }
        ?>


        <?php // $form->field($lote, 'turno_usuario_id')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]); ?>

        <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=Yii::t('app','Lots') ?></h3>
                    </div>
                    <div class="box-body">
                          <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                // 'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    // 'maquina_id',
                                    //'maquina_id',
                                    'identificador',
                                    'cantidad',
                                    'velocidad',
                                    'order',
                                    // array(
                                    // 'attribute' => 'local',
                                    // 'value'=> 'localname',
                                    // ),
                                    //'imagen',
                                    //'posx',
                                    //'posy',
                                    //'ancho',
                                    //'largo',
                                    //'mac',
                                    //'intervalo',
                                    //'fecha',
                                    //'estado',
                                    [
                                        'class' => 'yii\grid\RadioButtonColumn',
                                        'radioOptions' => function ($lotes, $key, $index, $column) {
                                            return ['value' => $lotes['id']];
                                        }
                                    ],
                                    // [
                                    //
                                    //     'class' => 'yii\grid\RadioButtonColumn',
                                    // ],
                                ],
                            ]); ?>



                    </div>
                </div>



        <div class="form-group">
            <?= Html::Button(Yii::t('app', 'Assign Machines'), ['class' => 'btn btn-info', 'onClick' => "addQuestion()"]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    function addQuestion(){
        var selected = $('input[name=radioButtonSelection]:checked', '#form').val();
        if (typeof(selected) !== "string"){
            alert('Seleccione un Lote');
        }
        else{
            $('#form').submit();
        }
    }
</script>
