<?php

namespace app\controllers;

use Yii;
use app\models\Parciales;
use app\models\Pedido;
use app\models\Lote;
use app\models\LoteSearch;
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
                'only' => ['index','view','create','update','delete','asignar','charts','performance'],
                'rules' => [
                    [
                        'actions' => ['index','view','asignar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['charts','performance','create','update','delete'],
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

    /**
     * Displays a single Turno model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = "SELECT lote.*"
                  . "FROM lote WHERE lote.id=".$id."";

        $order = Lote::findBySql($query)->all();

        $queryOrder = "SELECT pedido.*"
                  . "FROM pedido WHERE pedido.id=".$order[0]->pedido."";
        $pedido = Pedido::findBySql($queryOrder)->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $order,
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $order,
            'pedido' => $pedido,
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
                $questions = split(',', Yii::$app->request->post('array'));
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
           array_splice($last30GraphProd, $index, 0, $merr['total']);
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
