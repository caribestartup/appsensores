<?php

namespace app\controllers;

use Yii;
use app\models\TurnoUsuarioMaquina;
use app\models\Pedido;
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
use app\models\UserSearch;
use app\models\Turno;
use app\models\Lote;
use app\models\Genericos;
use app\models\Insidencia;
use app\models\LoteSearch;

/**
 * TurnoController implements the CRUD actions for Turno model.
 */
class AsignacionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
             'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','asignar','charts','performance', 'generico', 'states', 'stop', 'unassignlot'],
                'rules' => [
                    [
                        'actions' => ['index','view','asignar','confirm', 'generico', 'states', 'stop', 'unassignlot'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['charts','performance','create','update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                        $valid_roles = ['Operator'];
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
        $query = "SELECT turno_usuario_maquina.*"
                  . "FROM turno_usuario_maquina Where turno_usuario_maquina.borrar = 0";
        $assign = TurnoUsuarioMaquina::findBySql($query)->all();

        $order = TurnoUsuarioMaquina::getMaquinaAssign();


        $dataProvider = new ArrayDataProvider([
            'allModels' => $order,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $order,
        ]);
    }

    public function actionStates($id)
    {
        $machine = Maquina::findOne($id);

        $machine->state = 'Activo';
        $machine->save();

        $insidencia = (new \yii\db\Query())
        ->from('insidencia')
        ->where([
            'insidencia.maquina_id' => $id,
            'fin' => 0
        ])
        ->all();

        $insi = Insidencia::findOne($insidencia[0]['id']);
        $insi->fin = date('Y-m-d H:i:s');
        $insi->save();

        UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine resume successfully', UiHelper::SUCCESS);

        return $this->redirect(['index']);
    }

    public function actionStop()
    {


        if (Yii::$app->request->post()) {

            $machine_id = Yii::$app->request->post('id');
            $opcion = Yii::$app->request->post('opcion');

            // print_r(Yii::$app->request->post);

            switch ($opcion) {
                case '1':
                    $descripcion = Yii::$app->request->post('descripcion');
                    break;
                case '2':
                    $descripcion = 'Descanso';
                    break;
                case '3':
                    $descripcion = 'Fin de lote';
                    break;
                default:
                    $descripcion = 'Error de maquina';
                    break;
            }

            $incidencia = new Insidencia();
            $machine = Maquina::findOne($machine_id);

            $lote = (new \yii\db\Query())
                    ->from('lote')
                    ->where([
                        'lote.maquina_id' => $machine_id,
                        'lote.estado' => 'Activo',
                    ])
                    ->all();

            $incidencia->maquina_id = $machine_id;
            $incidencia->inicio = date('Y-m-d H:i:s');
            $incidencia->descripcion = $descripcion;
            $incidencia->value = $opcion;
            $incidencia->usuario_id = Yii::$app->user->identity->getId();
            $incidencia->lote_id = $lote[0]['id'];
            $incidencia->save();

            $machine->state = $opcion == 4? 'Error' : 'Detenido';
            $machine->save();

            UiHelper::alert('<i class="icon fa fa-desktop"></i> Incident registered successfully', UiHelper::SUCCESS);

            return $this->redirect(['index']);
        }
    }

    public function actionGenerico()
    {
        if (Yii::$app->request->post()) {

            $lote_id = Yii::$app->request->post('lote_id');
            $lote = Lote::findOne($lote_id);
            $lote->maquina_id = Yii::$app->request->post('maquina_id');
            $lote->estado = 'Activo';

            $maquina = Maquina::findOne(Yii::$app->request->post('maquina_id'));
            $maquina->state = "Activo";

            $lote->save();
            $maquina->save();

            $questions = $this->existQuestions($lote_id);
            foreach ($questions as $question) {
                $anwser = Yii::$app->request->post($question['id']);

                $generic = Genericos::findOne($question['id']);
                $generic->maquina_id = Yii::$app->request->post('maquina_id');
                $generic->user_id = Yii::$app->user->identity->getId();
                $generic->respuesta = $anwser;
                $generic->fecha = date('Y-m-d');

                $generic->save();
            }

            UiHelper::alert('<i class="icon fa fa-cubes"></i> Lot assigned successfully', UiHelper::SUCCESS);
            return $this->redirect(['index']);
        }
    }

    public function actionTransfer($id)
    {
        $tum = TurnoUsuarioMaquina::findOne($id);

        $turno = Turno::find()->asArray()->all();

        $users = (new \yii\db\Query())
                    ->select('user.*, turno.identificador, user_turno.id as ut_id')
                    ->leftJoin('turno', 'turno.id = user_turno.turno')
                    ->leftJoin('user', 'user.id = user_turno.user')
                    ->from('user_turno')
                    // ->where([
                    //     '!=','user_turno.user',Yii::$app->user->identity->getId()
                    // ])
                    ->orderBy('user.name')
                    ->all();


        $dataProvider = new ArrayDataProvider([
          'allModels' => $users,
        ]);

        if (Yii::$app->request->post()) {

            $ut_id = Yii::$app->request->post('radioButtonSelection');

            $newTUM = new TurnoUsuarioMaquina();
            $newTUM->turno_usuario_id = $ut_id;
            $newTUM->maquina_id = $tum->maquina_id;
            $newTUM->fecha = date('Y-m-d');
            $newTUM->borrar = 0;
            $newTUM->save();

            $tum->borrar = 1;
            $tum->save();

            UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine transfered successfully', UiHelper::SUCCESS);
            return $this->redirect(['asignacion/index']);

        } else {
            return $this->render('transfer', [
                //'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'users' => $users,
                'tum' => $tum,
                'turno' => $turno
            ]);
        }
    }

    public function actionConfirm()
    {
        if (Yii::$app->request->post()) {
            return User::confirmPass(Yii::$app->request->post('user_turno'), Yii::$app->request->post('password'));
        }
        else{
            return false;
        }
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

    /**
     * Displays a single Turno model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = "SELECT asignacion.*"
                  . "FROM asignacion WHERE asignacion.id=".$id."";

        $order = Asignacion::findBySql($query)->all();

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
        $model = new Asignacion();

        if ($model->load(Yii::$app->request->post())) {
            $model->estado = 'Activo';
            $model->save();

            // UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine transfered successfully', UiHelper::SUCCESS);
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
        $tum = TurnoUsuarioMaquina::findOne($id);

        $lotes = Lote::LotAvailable();


        $dataProvider = new ArrayDataProvider([
            'allModels' => $lotes,
        ]);

        if (Yii::$app->request->post()) {
            $lote_id = Yii::$app->request->post('radioButtonSelection');
            $questions = $this->existQuestions($lote_id);

            if (sizeof($questions) > 0) {
                return $this->render('questions', [
                    'lote' => $lote_id,
                    'maquina' => $tum->maquina_id,
                    'questions' => $questions,
                ]);
            }
            else {
                $lote = Lote::findOne($lote_id);
                $lote->maquina_id = $tum->maquina_id;
                $lote->estado = 'Activo';

                $maquina = Maquina::findOne($tum->maquina_id);
                $maquina->state = "Activo";



                $lote->save();
                $maquina->save();

                UiHelper::alert('<i class="icon fa fa-cubes"></i> Lot assigned successfully', UiHelper::SUCCESS);

                return $this->redirect(['index']);
            }

        } else {
            return $this->render('update', [
                'dataProvider' => $dataProvider,
                'lotes' => $lotes,
                'tum' => $tum,
            ]);
        }
    }

    public function actionUnassignlot($id)
    {
        $machine = (new \yii\db\Query())
                    ->from('maquina')
                    ->where([
                        'maquina.maquina_id' => $id,
                        // 'lote.estado' => 'Activo',
                    ])
                    ->all();

        $lote = (new \yii\db\Query())
                    ->from('lote')
                    ->where([
                        'lote.maquina_id' => $id,
                        'lote.estado' => 'Activo',
                    ])
                    ->all();

        $machine = Maquina::findOne($id);

        $queryLote = "SELECT lote.*"
                  . "FROM lote WHERE lote.maquina_id=".$id." AND lote.estado = 'Activo'";
        $lote = Lote::findBySql($queryLote)->all();

        $machine->state = 'Terminado';
        $machine->save();
        $lote[0]->maquina_id = 0;
        $lote[0]->save();

        UiHelper::alert('<i class="icon fa fa-cubes"></i> Lot unassigned successfully', UiHelper::SUCCESS);

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Turno model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $tum = $this->findModel($id);
        $tum->borrar = 1;
        $tum->save();
        UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine unassigned successfully', UiHelper::SUCCESS);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Asignacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Asignacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TurnoUsuarioMaquina::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
