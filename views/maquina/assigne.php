<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MaquinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Assign Machines');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machines Assigned'), 'url' => ['asignacion/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="maquina-index">

    <div class="maquina-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <label class="control-label" for="turnousuariomaquina-turno_usuario_id">Turn User</label>
            <select name="turno_usuario_id" id="locales" class="form-control" required>
                <option></option>
                <?php foreach ($turnos as $turno) {
                    echo "<option value=".$turno['id'].">".$turno['identificador']."</option>";
                } ?>
            </select>
        </div>

        <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=Yii::t('app','Machines') ?></h3>
                    </div>
                    <div class="box-body">
                        <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                //'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'nombre',
                                    'modelo',
                                    'numero',
                                    'local',
                                    [
                                        'class' => 'yii\grid\CheckboxColumn',
                                        'checkboxOptions' => function ($maquinas, $key, $index, $column) {
                                            return ['value' => $maquinas['maquina_id']];
                                        }
                                    ],
                                ],
                            ]);
                        ?>
                    </div>
                </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Assign Machines'), ['class' => 'btn btn-info']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
