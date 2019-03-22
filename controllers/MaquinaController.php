<?php

namespace app\controllers;

use Yii;
use app\models\Maquina;
use app\models\UserTurno;
use app\models\Turno;
use app\models\TurnoUsuarioMaquina;
use app\models\MaquinaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Totales;
use app\models\Error;
use app\models\Drange;
use app\models\User;
use yii\filters\AccessControl;
use app\models\Parciales;
use yii\data\ArrayDataProvider;
use app\helpers\UiHelper;

/**
 * MaquinaController implements the CRUD actions for Maquina model.
 */
class MaquinaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','production','charts','performance', 'assigne', 'unassigne'],
                'rules' => [
                    [
                        'actions' => ['index','production','performance', 'assigne'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['charts','create','update','delete','view', 'unassigne'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                        $valid_roles = ['Production Manager'];
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
     * Lists all Maquina models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$searchModel = new MaquinaSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $maquinas = (new \yii\db\Query())
                    ->select('maquina.*, turno_usuario_maquina.id as tum, turno_usuario_maquina.borrar as show, local.nombre as localName')
                    ->leftJoin('turno_usuario_maquina', 'turno_usuario_maquina.maquina_id = maquina.maquina_id')
                    ->leftJoin('local', 'local.local_id = maquina.local')
                    ->where([
                        'turno_usuario_maquina.fecha' => date('Y-m-d')
                    ])
                    ->from('maquina')
                    ->all();


        $dataProvider = new ArrayDataProvider([
            'allModels' => $maquinas,
        ]);

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Maquina model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $ordenador = (new \yii\db\Query())
        ->select('ordenador.*')
        ->from('ordenador')
        ->where([
          'ordenador.maquina' => $id
        ])
        ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $ordenador,
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $this->findModel($id),
            'ordenador' => $ordenador,
        ]);
    }

    public function actionAssigne()
    {
        $maquinas = Maquina::getAviableMachines();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $maquinas,
        ]);

        $turno_user = (new \yii\db\Query())
                    ->select('user_turno.id, user_turno.turno, turno.identificador')
                    ->leftJoin('turno', 'turno.id = user_turno.turno')
                    ->from('user_turno')
                    ->where([
                      'user_turno.user' => Yii::$app->user->identity->getId(),
                    ])
                    ->all();

        $turnoUsuarioMaquina = new TurnoUsuarioMaquina();

        if (Yii::$app->request->post()) {

            $selection = Yii::$app->request->post()['selection'];
            $tur = Yii::$app->request->post('turno_usuario_id');

            if ($selection == null){
                UiHelper::alert('<i class="icon fa fa-desktop"></i> Select a machine', UiHelper::DANGER);
                return $this->render('assigne', [
                    'dataProvider' => $dataProvider,
                    'turnos' => $turno_user,
                    'maquinas' => $maquinas,
                    'turnoUsuarioMaquina' => $turnoUsuarioMaquina
                ]);
            }

            foreach ($selection as $maq) {
                $turnoUsuarioMaquina->turno_usuario_id = $tur;
                $turnoUsuarioMaquina->maquina_id = $maq;
                $turnoUsuarioMaquina->borrar = 0;
                $turnoUsuarioMaquina->fecha = date("Y-m-d");

                if(!$turnoUsuarioMaquina->exits($tur, $maq, date("Y-m-d"))) {
                    $turnoUsuarioMaquina->save();
                    $turnoUsuarioMaquina = new TurnoUsuarioMaquina();
                }
            }
            UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine(s) assigned', UiHelper::SUCCESS);
            return $this->redirect(['asignacion/index']);

        } else {
            return $this->render('assigne', [
                'dataProvider' => $dataProvider,
                'turnos' => $turno_user,
                'maquinas' => $maquinas,
                'turnoUsuarioMaquina' => $turnoUsuarioMaquina
            ]);
        }
    }

     public function actionProduction($id)
    {
        return $this->render('production', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Maquina model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Maquina();

        if ($model->load(Yii::$app->request->post())) {
            $model->state = 'Creado';
            $model->save();
            UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine created successfully', UiHelper::SUCCESS);
            return $this->redirect(['view', 'id' => $model->maquina_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Maquina model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine updated successfully', UiHelper::SUCCESS);
            return $this->redirect(['view', 'id' => $model->maquina_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Maquina model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine deleted successfully', UiHelper::SUCCESS);
        return $this->redirect(['index']);
    }

    //  funcion a completar para el calculo de las horas encendidas, apagadas y error por maquina
    public function actionDistribution($id)
    {
        $distribution = Maquina::getDistribution($id);

        if (Yii::$app->request->post()) {
            $distribution = Maquina::getDistribution($id, $begin, $end);
        }

        return $distribution;
    }

    public function actionUnassigne($id)
    {
        $tum = TurnoUsuarioMaquina::findOne($id);
        $tum->borrar = 1;
        $tum->save();
        UiHelper::alert('<i class="icon fa fa-desktop"></i> Machine unassigned successfully', UiHelper::SUCCESS);

        return $this->redirect(['index']);
    }

    public function actionCharts()
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

        $maqref = new Maquina();
        $drange = new Drange();


        $last30Graph = [];
        $labelLast30Graph = [];


        $errorsGraph = [];
        $tempErrors = [];
        $values = [];
        $toload = [];
        $errorsName = [];


        $fechas = $this::fechas($last30, $tday);
        $errors = Parciales::find()->groupBy('nombre_ventana')->all();

        $count = 0;
        foreach ($errors as $error) {
            $count ++;
            $rec = "".$count."";
            $errorsGraph[$rec] = [];
            $tempErrors[strtolower($error->nombre_ventana)] = [];
            $errorsName[] = strtolower($error->nombre_ventana);
        }

        foreach($fechas as $date)
            {

                $labelLast30Graph[] = substr($date,5,5);

            }


         $maquina = Maquina::find()->all();

         if (count($maquina) > 0) {
             foreach ($maquina as $model) {

                unset($tempErrors);
                unset($values);
                unset($toload);
                $toload = [];

                 foreach ($errors as $error) {
                     $tempErrors[strtolower($error->nombre_ventana)] = [];
                }

                foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                }

                foreach ($model->getPartialerrors($last30,$today) as $merr) {
                    array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
                }

                foreach ($tempErrors as $temp ) {
                    unset($values);
                    foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                    }
                    foreach ($temp as $te) {
                        $hora = Totales::findOne($te['id_totales'])->hora_inicio;
                        $index = array_search(substr($hora, 0,10), $fechas);
                        $values[$index] = $te['error'];
                    }
                    array_push($toload, $values);

                }
                $color = ''.rand(0,255).','.rand(0,255).','.rand(0,255).'';
                    $count = 0;
                    foreach ($toload as $tl) {
                        $count ++;
                        $rec = "".$count."";
                        array_push($errorsGraph[$rec], ['label' => ''.$model->nombre.'','data' => $tl,'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
                    }
                    array_push($last30Graph,['label' => ''.$model->nombre.'','data' => $model->getTotalerrors($last30,$today),'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);

             }

         }


         return $this->render('charts',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange, 'errorsName' => $errorsName]);
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
            $last30 = strtotime ( '-10 day' , strtotime ( $tday ) ) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );

        }
        $maqref = Maquina::findOne($id);
        $drange = new Drange();


        $last30Graph = [];
        $labelLast30Graph = [];


        $errorsGraph = [];
        $tempErrors = [];
        $values = [];
        $toload = [];
        $errorsName = [];


        $fechas = $this::fechas($last30, $tday);
        $errors = Parciales::find()->groupBy('nombre_ventana')->all();


        foreach ($errors as $error) {
            $tempErrors[strtolower($error->nombre_ventana)] = [];
            $errorsName[] = strtolower($error->nombre_ventana);
        }

        foreach($fechas as $date)
            {

                $labelLast30Graph[] = substr($date,5,5);

            }


         $maquina = Maquina::find()->where(['maquina_id' => $id])->all();

         if (count($maquina) > 0) {
             foreach ($maquina as $model) {
                unset($tempErrors);
                unset($values);
                unset($toload);
                $toload = [];

                 foreach ($errors as $error) {
                     $tempErrors[strtolower($error->nombre_ventana)] = [];
                }

                foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                }

                foreach ($model->getPartialerrors($last30,$today) as $merr) {
                    array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
                }

                foreach ($tempErrors as $temp ) {
                    unset($values);
                    foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                    }
                    foreach ($temp as $te) {
                        $hora = Totales::findOne($te['id_totales'])->hora_inicio;
                        $index = array_search(substr($hora, 0,10), $fechas);
                        $values[$index] = $te['error'];
                    }
                    array_push($toload, $values);

                }

                    $count = 0;
                    foreach ($toload as $tl) {
                        $color = ''.rand(0,255).','.rand(0,255).','.rand(0,255).'';
                        $count ++;
                        $rec = "".$count."";
                        array_push($last30Graph, ['label' => ''.$errorsName[$rec-1].'','data' => $tl,'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
                    }
                    array_push($last30Graph,['type' => 'line','label' => Yii::t('app','Estimated Production'),'data' => $model->getTotalprodest($last30,$today),'fill' => 'false', 'borderColor' => 'rgb(40,250,40)', 'backgroundColor' => 'rgb(40,250,40)']);
                    array_push($last30Graph,['type' => 'line','label' => Yii::t('app','Real Production'),'data' => $model->getTotalprod($last30,$today),'fill' => 'false', 'borderColor' => 'rgb(40,45,250)', 'backgroundColor' => 'rgb(40,45,250)']);

             }

         }


         return $this->render('performance',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange]);

    }

    public function actionPerformancebar($id)
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
            $last30 = strtotime ( '-10 day' , strtotime ( $tday ) ) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );

        }
        $maqref = Maquina::findOne($id);
        $drange = new Drange();


        $last30Graph = [];
        $labelLast30Graph = [];


        $errorsGraph = [];
        $tempErrors = [];
        $values = [];
        $toload = [];


        $fechas = $this::fechas($last30, $tday);
        $errors = Parciales::find()->groupBy('nombre_ventana')->all();

        foreach ($errors as $error) {
            $tempErrors[strtolower($error->nombre_ventana)] = [];
        }

        foreach($fechas as $date)
            {

                $labelLast30Graph[] = substr($date,5,5);

            }


         $maquina = Maquina::find()->where(['maquina_id' => $id])->all();

         if (count($maquina) > 0) {
             foreach ($maquina as $model) {
                unset($tempErrors);
                unset($values);
                unset($toload);
                $toload = [];

                 foreach ($errors as $error) {
                     $tempErrors[strtolower($error->nombre_ventana)] = [];
                }

                foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                }

                foreach ($model->getPartialerrors($last30,$today) as $merr) {
                    array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
                }

                foreach ($tempErrors as $temp ) {
                    unset($values);
                    foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                    }
                    foreach ($temp as $te) {
                        $hora = Totales::findOne($te['id_totales'])->hora_inicio;
                        $index = array_search(substr($hora, 0,10), $fechas);
                        $values[$index] = $te['error'];
                    }
                    array_push($toload, $values);

                }
                array_push($last30Graph,['label' => Yii::t('app','Deviation %'),'data' => $model->getTotalrech($last30,$today), 'borderColor' => 'rgb(216,76,26)', 'backgroundColor' => 'rgb(216,76,26)']);
                array_push($last30Graph,['label' => Yii::t('app','Real Production'),'data' => $model->getTotalprod($last30,$today), 'borderColor' => 'rgb(40,45,250)', 'backgroundColor' => 'rgb(40,45,250)']);
                array_push($last30Graph,['label' => Yii::t('app','Estimated Production'),'data' => $model->getTotalprodest($last30,$today), 'borderColor' => 'rgb(40,250,40)', 'backgroundColor' => 'rgb(40,250,40)']);


             }

         }


         return $this->render('performancebar',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange]);

    }

    public function actionPerformanceall()
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
            $last30 = strtotime ( '-10 day' , strtotime ( $tday ) ) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );
        }
        $drange = new Drange();

        $last30Graph = [];
        $labelLast30Graph = [];

        $errorsGraph = [];
        $tempErrors = [];
        $values = [];
        $toload = [];

        $fechas = $this::fechas($last30, $tday);
        $errors = Parciales::find()->groupBy('nombre_ventana')->all();

        foreach ($errors as $error) {
            $tempErrors[strtolower($error->nombre_ventana)] = [];
        }

        foreach($fechas as $date){
            $labelLast30Graph[] = substr($date,5,5);
        }

        $maquina = Maquina::find()->all();

        if (count($maquina) > 0) {
            foreach ($maquina as $model) {
                unset($tempErrors);
                unset($values);
                unset($toload);
                $toload = [];

                foreach ($errors as $error) {
                    $tempErrors[strtolower($error->nombre_ventana)] = [];
                }

                foreach ($fechas as $fecha) {
                    $values [] = 0 ;
                }

                foreach ($model->getPartialerrors($last30,$today) as $merr) {
                    array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
                }

                foreach ($tempErrors as $temp ) {
                    unset($values);
                    foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                    }
                    foreach ($temp as $te) {
                        $hora = Totales::findOne($te['id_totales'])->hora_inicio;
                        $index = array_search(substr($hora, 0,10), $fechas);
                        $values[$index] = $te['error'];
                    }
                    array_push($toload, $values);

                }

                $color = ''.rand(0,255).','.rand(0,255).','.rand(0,255).'';
                //array_push($last30Graph,['type' => 'line','label' => 'Producción Estimada','data' => $model->getTotalprodest($last30,$today),'fill' => 'false', 'borderColor' => 'rgb(40,250,40)', 'backgroundColor' => 'rgb(40,250,40)']);
                array_push($last30Graph,['label' => $model->nombre,'data' => $model->getTotalprod($last30,$today),'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);

            }

        }
        return $this->render('performanceall',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'drange' => $drange]);
    }

    /**
     * Finds the Maquina model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Maquina the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Maquina::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
}
