<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TurnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Machines Assigned');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="turno-index">

    <p>
        <?= Html::a(Yii::t('app', 'Assign Machine'), ['maquina/assigne'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'fecha',
            'state',
            'lot',

           [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{assign}{transfer}{unassign}{state}{stop}',
            'urlCreator' => function($action, $model, $key, $index) {
                    return Url::to([$action,'id'=>$key]);
            },
            'buttons'=>[
                'assign' => function ($url, $model, $key) {
                    if($model["lot"] == null){
                        return Html::a('<span class="fa fa-cubes"></span>', ['update', 'id'=>$model["id"]],['title'=> Yii::t('app','Assign Lot')]);
                    }
                },
                'transfer' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-sign-out"></span>', ['transfer', 'id'=>$model["id"]],['title'=> Yii::t('app','Transfer')]);
                },
                'unassign' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-close "></span>', ['asignacion/delete', 'id'=>$model["id"]],['data' => [
                        'confirm' => Yii::t('app','Do you want to unassign machine?'),
                        'method' => 'post',
                    ],'title'=>Yii::t('app', Yii::t('app','Unassign Machine'))]);
                },
                'state' => function ($url, $model, $key) {
                    if($model["state"] == "Activo"){
                        return Html::a('<span class="fa fa-pause "></span>', ['asignacion/states', 'id'=>$model["maquina_id"]],['data' => [
                            'confirm' => Yii::t('app','Do you want to PAUSE machine?'),
                            'method' => 'post',
                        ],'title'=>Yii::t('app', Yii::t('app','Pause Machine'))]);

                    }
                    else if($model["state"] == "Pausado" || $model["state"] == "Detenido") {
                        return Html::a('<span class="fa fa-play "></span>', ['asignacion/states', 'id'=>$model["maquina_id"]],['data' => [
                            'confirm' => Yii::t('app','Do you want to RESUME machine?'),
                            'method' => 'post',
                        ],'title'=>Yii::t('app', Yii::t('app','Resume Machine'))]);
                    }
                },
                'stop' => function ($url, $model, $key) {
                    if($model["state"] !== "Terminado"){
                        return '<a id="'.$model["maquina_id"].'" href="" data-toggle="modal" data-target="#exampleModal" class="stop"><span class="fa fa-ban"></span></a>';
                    }
                },
            ],

        ]
        ],
    ]); ?>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Insert Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Options</label>
                    <select class="custom-select form-control" id='option'>
                        <option value="1">Otro</option>
                        <option value="2">Descanso</option>
                        <option value="3" selected>Fin de lote</option>
                        <option value="4">Máquina en error</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Description</label>
                    <input type="text" class="form-control" id="description">
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id='btn_submit'>STOP</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var machine_id = -1;
        $('.stop').click(function (e) {
            machine_id = e.currentTarget.id;
        });

        $('#btn_submit').click(function (e) {
            var select = document.getElementById("option");
            var value = select.options[select.selectedIndex].value;
            var description = document.getElementById("description").value;
            if(machine_id > 0) {
                $.ajax({
                    url: '<?php echo Yii::$app->request->baseUrl. '/index.php?r=asignacion/stop' ?>',
                    type: 'post',
                    data: {
                        id: machine_id,
                        opcion: value,
                        descripcion: description,
                        _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                    },
                    success: function (data) {

                    }
                });
                machine_id = -1;
            }
        });
    });
</script>
