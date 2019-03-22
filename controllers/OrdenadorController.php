<?php

namespace app\controllers;

use Yii;
use app\models\Ordenador;
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
class OrdenadorController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
             'access' => [
                'class' => AccessControl::className(),
                'only' => ['view','create','update','delete','charts','performance', 'report'],
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['charts','performance','create','update','delete', 'report'],
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
     * Displays a single Turno model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = "SELECT ordenador.*"
                  . "FROM ordenador WHERE ordenador.id=".$id."";

        $order = Ordenador::findBySql($query)->all();

        $queryOrder = "SELECT maquina.*"
                  . "FROM maquina WHERE maquina.maquina_id=".$order[0]->maquina_id."";
        $maquina = Maquina::findBySql($queryOrder)->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $order,
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $order,
            'maquina' => $maquina,
        ]);
    }

    /**
     * Creates a new Turno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $query = "SELECT maquina.*"
                  . "FROM maquina WHERE maquina.maquina_id=".$id."";
        $maquina = Maquina::findBySql($query)->all();
        $model = new Ordenador();

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            UiHelper::alert('<i class="icon fa fa-laptop"></i> Computer created successfully', UiHelper::SUCCESS);

            return $this->redirect(['maquina/view', 'id' => $maquina[0]->maquina_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'maquina' => $maquina
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
        $ordenador = Ordenador::findOne($id);

        $query = "SELECT maquina.*"
        . "FROM maquina WHERE maquina.maquina_id=".$ordenador->maquina."";
        $maquina = Maquina::findBySql($query)->all();


        if ($ordenador->load(Yii::$app->request->post())) {
            $ordenador->save();
            UiHelper::alert('<i class="icon fa fa-laptop"></i> Computer updated successfully', UiHelper::SUCCESS);

            return $this->redirect(['maquina/view', 'id' => $maquina[0]->maquina_id]);
        } else {
            return $this->render('update', [
                'ordenador' => $ordenador,
                'maquina' => $maquina
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
        $ordenadorOld = $this->findModel($id);
        $this->findModel($id)->delete();
        UiHelper::alert('<i class="icon fa fa-laptop"></i> Computer deleted successfully', UiHelper::SUCCESS);

        return $this->redirect(['maquina/view', 'id' => $ordenadorOld->maquina]);
    }

    /**
     * Finds the Ordenador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ordenador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ordenador::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function existQuestions($ordenador_id)
    {
        $genericis = (new \yii\db\Query())
        ->from('genericos')
        ->where([
            'genericos.ordenador_id' => $ordenador_id,
        ])
        ->all();

        return $genericis;
    }


}
