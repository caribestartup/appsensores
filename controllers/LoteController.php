<?php

namespace app\controllers;

use Yii;
use app\models\Parciales;
use app\models\Pedido;
use app\models\Lote;
use app\models\LoteSearch;
use app\models\Insidencia;
use app\models\Maquina;
use app\models\MaquinaSearch;
use app\models\Error;
use app\models\Totales;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\UiHelper;
use yii\data\ArrayDataProvider;
use app\models\Drange;
use yii\filters\AccessControl;
use app\models\User;
use app\models\Genericos;
use \Datetime;

/**
 * TurnoController implements the CRUD actions for Turno model.
 */
class LoteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
             'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','asignar','charts','performance', 'performancetime', 'report'],
                'rules' => [
                    [
                        'actions' => ['index','view','asignar', 'performancetime', 'report', 'charts','performance'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create','update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                        $valid_roles = ['Production Manager', 'Shift Manager'];
                        return User::roleInArray($valid_roles) && User::isActive();
                        }
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Turno models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LoteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReport($id)
    {
      $insidencia = (new \yii\db\Query())
                  ->select('insidencia.*, user.name, user.surname, maquina.nombre , TIMESTAMPDIFF(MINUTE, `insidencia`.`inicio`, `insidencia`.`fin`) as time_min')
                  ->leftJoin('user', 'user.id = insidencia.usuario_id')
                  ->leftJoin('maquina', 'maquina.maquina_id = insidencia.maquina_id')
                  ->from('insidencia')
                  ->where([
                      'insidencia.lote_id' => $id
                  ])
                  ->all();


      $dataProvider = new ArrayDataProvider([
          'allModels' => $insidencia,
      ]);

      $lote = Lote::findOne($id);

      return $this->render('report', [
          'dataProvider' => $dataProvider,
          'lote' => $id,
          'model' => $lote,
      ]);
    }

    /**
     * Displays a single Turno model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $genericos = (new \yii\db\Query())
        ->select('genericos.*, user.name, user.surname, maquina.nombre')
        ->leftJoin('user', 'user.id = genericos.user_id')
        ->leftJoin('maquina', 'maquina.maquina_id = genericos.maquina_id')
        ->from('genericos')
        ->where([
            'genericos.lote_id' => $id,
        ])
        ->all();

        $lote = Lote::findOne($id);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $genericos,
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'lote' => $lote,
        ]);
    }

    /**
     * Creates a new Turno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $query = "SELECT pedido.*"
                  . "FROM pedido WHERE pedido.id=".$id."";
        $pedido = Pedido::findBySql($query)->all();
        $model = new Lote();

        if ($model->load(Yii::$app->request->post())) {
            $model->estado = 'Activo';
            $model->maquina_id = 0;
            $model->save();
            $loteID = Yii::$app->db->getLastInsertID();

            if(Yii::$app->request->post('array') != ''){
                $questions = mb_split(',', Yii::$app->request->post('array'));
                foreach ($questions as $question) {
                    $generic = new Genericos();
                    $generic->pregunta = Yii::$app->request->post($question);
                    $generic->lote_id = $loteID;
                    $generic->save();
                }
            }
            UiHelper::alert('<i class="icon fa fa-cubes"></i> Lot created successfully', UiHelper::SUCCESS);

            return $this->redirect(['pedido/view', 'id' => $pedido[0]->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'pedido' => $pedido
            ]);
        }
    }

    /**
     * Updates an existing Turno model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $lote = $this->findModel($id);

        $query = "SELECT pedido.*"
        . "FROM pedido WHERE pedido.id=".$lote->pedido."";
        $pedido = Pedido::findBySql($query)->all();


        // echo $lote->pedido;

        if ($lote->load(Yii::$app->request->post())) {
            $lote->save();
            UiHelper::alert('<i class="icon fa fa-cubes"></i> Lot updated successfully', UiHelper::SUCCESS);

            return $this->redirect(['pedido/view', 'id' => $pedido[0]->id]);
        } else {
            return $this->render('update', [
                'lote' => $lote,
                'pedido' => $pedido
            ]);
        }
    }

    /**
     * Deletes an existing Turno model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $loteOld = $this->findModel($id);
        $this->findModel($id)->delete();
        UiHelper::alert('<i class="icon fa fa-cubes"></i> Lot deleted successfully', UiHelper::SUCCESS);

        return $this->redirect(['pedido/view', 'id' => $loteOld->pedido]);
    }

    /**
     * Finds the Lote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lote::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAssign($id)
    {

        $lote = $this->findModel($id);

        $machines = Maquina::getMachineForLoteDiferent('Activo');

        $dataProvider = new ArrayDataProvider([
          'allModels' => $machines,
        ]);

        // print_r($dataProvider);


        if (Yii::$app->request->post()) {

            $questions = $this->existQuestions($id);

            if (sizeof($questions) > 0) {
                return $this->render('/asignacion/questions', [
                    'lote' => $id,
                    'maquina' => Yii::$app->request->post("radioButtonSelection"),
                    'questions' => $questions,
                ]);

            } else {
                $lote->maquina_id = Yii::$app->request->post("radioButtonSelection");
                $lote->estado = 'Activo';
                $lote->save();

                $machine = Maquina::findOne(Yii::$app->request->post("radioButtonSelection"));
                $machine->state = 'Activo';
                $machine->save();

                return $this->redirect(['pedido/view', 'id' => $lote->pedido]);
            }

        } else {
            return $this->render('assign', [
                'dataProvider' => $dataProvider,
                'machine' => $machines,
                'lote' => $lote
            ]);
        }
    }

    public function actionPerformance($id)
    {
        if (Yii::$app->request->post()) {
            $tday = date('Y-m-d', strtotime(substr(Yii::$app->request->post("Drange")["range"], -10)));
            $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;
            $last30 = strtotime (substr(Yii::$app->request->post("Drange")["range"], 0,10)) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );
        }else{
            $tday = date('Y-m-d');
            $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;
            $last30 = strtotime ( '-30 day' , strtotime ( $tday ) ) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );
        }

        $drange = new Drange();
        $lot = Lote::findOne($id);

        $query = "SELECT pedido.*"
        . "FROM pedido WHERE pedido.id=".$lot->pedido."";
        $pedido = Pedido::findBySql($query)->all();

        $last30GraphError = [];
        $last30GraphProd = [];
        $labelLast30Graph = [];

        $fechas = $this::fechas($last30, $tday);

        foreach($fechas as $date) {
            $labelLast30Graph[] = substr($date,5,5);
        }

        $lots = $lot->getProdNError($last30, $tday);
        foreach ($fechas as $key => $fecha) {
            $last30GraphError [$key] = 0;
            $last30GraphProd [$key] = 0;
        }

        foreach ($lots as $merr) {
           $hora = Totales::findOne($merr['id'])->hora_inicio;
           $index = array_search(substr($hora, 0,10), $fechas);
           // echo $index;
           array_splice($last30GraphError, $index, 0, $merr['error']);
           array_splice($last30GraphProd, $index, 0, $merr['total'] - $merr['error']);
        }

        return $this->render('performance',[
            'pedido' => $pedido,
            'order' => $lot,
            'labels' => $labelLast30Graph,
            'data_prod' => $last30GraphProd,
            'data_error' => $last30GraphError,
            'drange' => $drange
        ]);
    }

    public function actionPerformancetime($id)
    {
        if (Yii::$app->request->post()) {
            $tday = date('Y-m-d', strtotime(substr(Yii::$app->request->post("Drange")["range"], -10)));
            $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
            $last30 = strtotime (substr(Yii::$app->request->post("Drange")["range"], 0,10)) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );
        }else{
            $tday = date('Y-m-d');
            $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
            $last30 = strtotime ( '-30 day' , strtotime ( $tday ) ) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );
        }

        $drange = new Drange();
        $lot = Lote::findOne($id);

        $query = "SELECT pedido.*"
        . "FROM pedido WHERE pedido.id=".$lot->pedido."";
        $pedido = Pedido::findBySql($query)->all();

        $theoricTime = ($lot->cantidad / $lot->velocidad)/60;

        $incidensias = (new \yii\db\Query())
                    ->select('insidencia.*, SUM(TIMESTAMPDIFF(MINUTE, `insidencia`.`inicio`, `insidencia`.`fin`)) as minutes')
                    ->where([
                        'insidencia.lote_id' => $id,
                        'value' => [1, 2, 4]
                    ])
                    ->from('insidencia')
                    ->groupby('insidencia.value')
                    ->all();

        $otherTime = 0;
        $errorTime = 0;
        $restTime = 0;

        // print_r($incidensias);

        foreach ($incidensias as $incidencia) {
            if($incidencia['value'] == 1) {
                $otherTime=$incidencia['minutes'];
            }
            else if($incidencia['value'] == 4){
                $errorTime=$incidencia['minutes'];
            }
            else {
                $restTime=$incidencia['minutes'];
            }
        }

        $totales_start = (new \yii\db\Query())
                    ->select('totales.*, MIN(`totales`.`hora_inicio`) as start')
                    ->where([
                        'totales.lote_id' => $id
                    ])
                    ->from('totales')
                    ->all();

        $start_date = $totales_start[0]['start'];
        $end_date = 0;
        $real_time = '';

        // echo $lot->estado;

        if($lot->estado == 'Terminado'){
            $totales_end = (new \yii\db\Query())
                        ->select('totales.*, MAX(`totales`.`hora_fin`) as end')
                        ->where([
                            'totales.lote_id' => $id
                        ])
                        ->from('totales')
                        ->all();
            $end_date = $totales_end[0]['end'];
        }
        else {
            $end_date = date('Y-m-d H:i:s');
            $real_time = '(NOW)';
        }

        $diff = (strtotime($start_date)-strtotime($end_date))/60;
        $diff = abs($diff);
        $diff = floor($diff);

        $realTime = $diff/60;

        return $this->render('performancetime',[
            'pedido' => $pedido,
            'order' => $lot,
            'real' => $realTime,
            'theoric' => $theoricTime,
            'rest' => round($restTime/60, 2),
            'other' => round($otherTime/60, 2),
            'error' => round($errorTime/60, 2),
            'time' => $real_time
        ]);
    }

    public function fechas($start, $end) {
        $range = array();

        if (is_string($start) === true) $start = strtotime($start);
        if (is_string($end) === true ) $end = strtotime($end);

        if ($start > $end) return createDateRangeArray($end, $start);

        do {
           $range[] = date('Y-m-d', $start);
           $start = strtotime("+ 1 day", $start);
        } while($start <= $end);

        return $range;
    }

    public function existQuestions($lote_id)
    {
        $genericis = (new \yii\db\Query())
        ->from('genericos')
        ->where([
            'genericos.lote_id' => $lote_id,
        ])
        ->all();

        return $genericis;
    }

}
