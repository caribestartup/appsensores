<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MaquinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transfer Machine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Machines Assigned'), 'url' => ['asignacion/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="maquina-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a(Yii::t('app', 'Assign Machine'), ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <div class="maquina-form">

        <?php $form = ActiveForm::begin([
                  'method' => 'post',
                  'action' => ['asignacion/transfer', 'id' => $tum->id],
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
                        <h3 class="box-title"><?=Yii::t('app','Machines') ?></h3>
                    </div>
                    <div class="box-body">
                          <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                //'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    // 'maquina_id',
                                    //'maquina_id',
                                    'name',
                                    'surname',
                                    'email',
                                    'identificador',
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
                                        'radioOptions' => function ($users, $key, $index, $column) {
                                            return ['value' => $users['ut_id']];
                                        },
                                    ],
                                ],
                            ]); ?>



                    </div>
                </div>



        <div class="form-group">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Transfer Machine</button>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

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
            <label for="recipient-name" class="col-form-label">Password</label>
            <input type="password" class="form-control" id="password">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id='btn_submit'>Transfer</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#btn_submit').click(function (e) {
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl. '/index.php?r=asignacion/confirm' ?>',
                type: 'post',
                data: {
                    password: $('#password').val(),
                    user_turno: $("input[type='radio']:checked").val(),
                    _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                },
                success: function (data) {
                    if(data == 1) {
                        $('#form').submit();
                    }
                    else{
                        alert('Incorrect Password');
                    }
                }
            });
        });
    });
</script>
