<?php

namespace app\controllers;

use Yii;
use app\models\Parciales;
use app\models\Pedido;
use app\models\PedidoSearch;
use app\models\Lote;
use app\models\Maquina;
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

/**
 * TurnoController implements the CRUD actions for Turno model.
 */
class PedidoController extends Controller
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
                        'actions' => ['charts','performance','index','view','asignar'],
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
        $searchModel = new PedidoSearch();
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
        $query = "SELECT pedido.*"
                  . "FROM pedido WHERE pedido.id=".$id."";

        $order = Pedido::findBySql($query)->all();

        $lotes = (new \yii\db\Query())
        ->select('lote.id, lote.pedido, lote.identificador, lote.velocidad, lote.cantidad, lote.estado, lote.maquina_id, maquina.nombre as Maquina')
        ->leftJoin('maquina', 'maquina.maquina_id = lote.maquina_id')
        ->from('lote')
        ->where([
          'lote.pedido' => $id
        ])
        ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $lotes,
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $order,
            'lotes' => $lotes,
        ]);
    }

    /**
     * Creates a new Turno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pedido();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            UiHelper::alert('<i class="icon fa fa-headphones"></i> Order created successfully', UiHelper::SUCCESS);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            UiHelper::alert('<i class="icon fa fa-headphones"></i> Order updated successfully', UiHelper::SUCCESS);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
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
        $this->findModel($id)->delete();
        UiHelper::alert('<i class="icon fa fa-headphones"></i> Order deleted successfully', UiHelper::SUCCESS);

        return $this->redirect(['index']);
    }

    public function actionPerformance($id)
    {
        $order = Pedido::findOne($id);
        $lots = (new \yii\db\Query())
                    ->select('pedido.*, lote.*, SUM(totales.total) as total, SUM(totales.total_error) as error')
                    ->leftJoin('lote', 'lote.pedido = pedido.id')
                    ->leftJoin('totales', 'totales.lote_id = lote.id')
                    ->where([
                        'pedido.id' => $id
                    ])
                    ->from('pedido')
                    ->all();

        $labels = [];
        $data_prod = [];
        $data_error = [];

        foreach ($lots as $lot) {
            array_push($labels, $lot['identificador']);
            array_push($data_error, $lot['error']);
            array_push($data_prod, $lot['total'] - $lot['error']);
        }

        // print_r($order->identificador);
        return $this->render('performance',[
            'order' => $order,
            'labels' => $labels,
            'data_prod' => $data_prod,
            'data_error' => $data_error
            // 'last30Graph' => $last30Graph,
            // 'labelLast30Graph' => $labelLast30Graph,
            // 'errorsGraph' => $errorsGraph,
            // 'maqref' => $maqref,
            // 'drange' => $drange,
            // 'userref' => $userref
        ]);

    }

    /**
     * Finds the Pedido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pedido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pedido::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
