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
                return $this->render('//asignacion/questions', [
                    'lote' => $id,
                    'maquina' => Yii::$app->request->post("radioButtonSelection"),
                    'questions' => $questions,
                ]);

            } else {
                $lote->maquina_id = Yii::$app->request->post("radioButtonSelection");
                $lote->estado = 'Activo';
                $lote->save();

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

    public function actionProceso()
    {
      // print_r($lote->load(Yii::$app->request->post()));
      // $searchModel = new MaquinaSearch();
      // $dataProvider = $searchModel->search(Maquina::getMachineForLote());
      //
      // if ($lote->load(Yii::$app->request->post())) {
      //     $lote->save();
      //     return $this->redirect(['pedido/view', 'id' => $lote->pedido]);
      // } else {
      //     return $this->render('assign', [
      //         'dataProvider' => $dataProvider,
      //         'lote' => $lote
      //     ]);
      // }
    }

    // public function actionAsignar($id)
    // {
    //     $user = User::findOne($id);
    //     $user_turno = new UserTurno();
    //     $user_turno->user = $id;
    //     if (Yii::$app->request->post('UserTurno')) {
    //
    //         $verify = UserTurno::find()->where(['user' => Yii::$app->request->post('UserTurno')['user'], 'turno' => Yii::$app->request->post('UserTurno')['turno']])->one();
    //
    //         if ($verify != null) {
    //             UiHelper::alert('<i class="icon fa fa-ban"></i>'. Yii::t('app','Work Shifts Assigned Previously'), UiHelper::DANGER);
    //         }
    //         else{
    //             $user_turno->user = Yii::$app->request->post('UserTurno')['user'];
    //             $user_turno->turno = Yii::$app->request->post('UserTurno')['turno'];
    //             $user_turno->save();
    //             $user_turno = new UserTurno();
    //             $user_turno->user = $id;
    //             UiHelper::alert('<i class="icon fa fa-check"></i>'. Yii::t('app','Work Shifts Assigned'), UiHelper::SUCCESS);
    //         }
    //     }
    //
    //     return $this->render('asignar', ['usuario' => $user, 'user_turno' => $user_turno]);
    // }
    //
    // public function actionCharts()
    // {
    //      if (Yii::$app->request->post()) {
    //
    //         $tday = date('Y-m-d', strtotime(substr(Yii::$app->request->post("Drange")["range"], -10)));
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime (substr(Yii::$app->request->post("Drange")["range"], 0,10)) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //
    //
    //     }else{
    //         $tday = date('Y-m-d');
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime ( '-30 day' , strtotime ( $tday ) ) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //     }
    //     $maqref = new Maquina();
    //     $drange = new Drange();
    //
    //
    //     $last30Graph = [];
    //     $labelLast30Graph = [];
    //
    //
    //     $errorsGraph = [];
    //     $tempErrors = [];
    //     $values = [];
    //     $toload = [];
    //     $errorsName = [];
    //
    //
    //     $fechas = $this::fechas($last30, $tday);
    //     $errors = Parciales::find()->groupBy('nombre_ventana')->all();
    //
    //     $count = 0;
    //     foreach ($errors as $error) {
    //         $count ++;
    //         $rec = "".$count."";
    //         $errorsGraph[$rec] = [];
    //         $tempErrors[strtolower($error->nombre_ventana)] = [];
    //         $errorsName[] = strtolower($error->nombre_ventana);
    //     }
    //
    //     foreach($fechas as $date)
    //         {
    //
    //             $labelLast30Graph[] = substr($date,5,5);
    //
    //         }
    //
    //
    //      $maquina = Turno::find()->all();
    //
    //      if (count($maquina) > 0) {
    //          foreach ($maquina as $model) {
    //
    //             unset($tempErrors);
    //             unset($values);
    //             unset($toload);
    //             $toload = [];
    //
    //              foreach ($errors as $error) {
    //                  $tempErrors[strtolower($error->nombre_ventana)] = [];
    //             }
    //
    //             foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //             }
    //
    //             foreach ($model->getPartialerrors($last30,$today) as $merr) {
    //                 array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
    //             }
    //
    //             foreach ($tempErrors as $temp ) {
    //                 unset($values);
    //                 foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //                 }
    //                 foreach ($temp as $te) {
    //                     $hora = Totales::findOne($te['id_totales'])->hora_inicio;
    //                     $index = array_search(substr($hora, 0,10), $fechas);
    //                     $values[$index] = $te['error'];
    //                 }
    //                 array_push($toload, $values);
    //
    //             }
    //             $color = ''.rand(0,255).','.rand(0,255).','.rand(0,255).'';
    //                 $count = 0;
    //                 foreach ($toload as $tl) {
    //                     $count ++;
    //                     $rec = "".$count."";
    //                     array_push($errorsGraph[$rec], ['label' => ''.$model->identificador.'','data' => $tl,'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
    //                 }
    //                 array_push($last30Graph,['label' => ''.$model->identificador.'','data' => $model->getTotalerrors($last30,$today),'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
    //
    //          }
    //
    //      }
    //
    //
    //      return $this->render('charts',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange, 'errorsName' => $errorsName]);
    // }
    //
    // public function actionPerformance($id)
    // {
    //      if (Yii::$app->request->post()) {
    //
    //         $tday = date('Y-m-d', strtotime(substr(Yii::$app->request->post("Drange")["range"], -10)));
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime (substr(Yii::$app->request->post("Drange")["range"], 0,10)) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //
    //
    //     }else{
    //         $tday = date('Y-m-d');
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime ( '-7 day' , strtotime ( $tday ) ) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //     }
    //     $maqref = new Maquina();
    //     $drange = new Drange();
    //     $turnoref = Turno::findOne($id);
    //
    //
    //     $last30Graph = [];
    //     $labelLast30Graph = [];
    //
    //
    //     $errorsGraph = [];
    //     $tempErrors = [];
    //     $values = [];
    //     $toload = [];
    //     $errorsName = [];
    //
    //
    //     $fechas = $this::fechas($last30, $tday);
    //     $errors = Parciales::find()->groupBy('nombre_ventana')->all();
    //
    //     foreach ($errors as $error) {
    //         $tempErrors[strtolower($error->nombre_ventana)] = [];
    //         $errorsName[] = strtolower($error->nombre_ventana);
    //     }
    //
    //     foreach($fechas as $date)
    //         {
    //
    //             $labelLast30Graph[] = substr($date,5,5);
    //
    //         }
    //
    //
    //      $maquina = Turno::find()->where(['id' => $id])->all();
    //
    //      if (count($maquina) > 0) {
    //          foreach ($maquina as $model) {
    //             unset($tempErrors);
    //             unset($values);
    //             unset($toload);
    //             $toload = [];
    //
    //              foreach ($errors as $error) {
    //                  $tempErrors[strtolower($error->nombre_ventana)] = [];
    //             }
    //
    //             foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //             }
    //
    //             foreach ($model->getPartialerrors($last30,$today) as $merr) {
    //                 array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
    //             }
    //
    //             foreach ($tempErrors as $temp ) {
    //                 unset($values);
    //                 foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //                 }
    //                 foreach ($temp as $te) {
    //                     $hora = Totales::findOne($te['id_totales'])->hora_inicio;
    //                     $index = array_search(substr($hora, 0,10), $fechas);
    //                     $values[$index] = $te['error'];
    //                 }
    //                 array_push($toload, $values);
    //
    //             }
    //
    //                 $count = 0;
    //                 foreach ($toload as $tl) {
    //                     $color = ''.rand(0,255).','.rand(0,255).','.rand(0,255).'';
    //                     $count ++;
    //                     $rec = "".$count."";
    //                     array_push($last30Graph, ['label' => ''.$errorsName[$rec-1].'','data' => $tl,'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
    //                 }
    //                 array_push($last30Graph,['type' => 'line','label' => Yii::t('app','Planned Production'),'data' => $model->getTotalprodest($last30,$today),'fill' => 'false', 'borderColor' => 'rgb(40,250,40)', 'backgroundColor' => 'rgb(40,250,40)']);
    //                 array_push($last30Graph,['type' => 'line','label' => 'Real Production','data' => $model->getTotalprod($last30,$today),'fill' => 'false', 'borderColor' => 'rgb(40,45,250)', 'backgroundColor' => 'rgb(40,45,250)']);
    //
    //          }
    //
    //      }
    //
    //
    //      return $this->render('performance',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange, 'turnoref' => $turnoref]);
    //
    // }
    //
    // public function actionPerformancebar($id)
    // {
    //      if (Yii::$app->request->post()) {
    //
    //         $tday = date('Y-m-d', strtotime(substr(Yii::$app->request->post("Drange")["range"], -10)));
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime (substr(Yii::$app->request->post("Drange")["range"], 0,10)) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //
    //
    //     }else{
    //         $tday = date('Y-m-d');
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime ( '-7 day' , strtotime ( $tday ) ) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //     }
    //     $maqref = new Maquina();
    //     $drange = new Drange();
    //     $turnoref = Turno::findOne($id);
    //
    //
    //     $last30Graph = [];
    //     $labelLast30Graph = [];
    //
    //
    //     $errorsGraph = [];
    //     $tempErrors = [];
    //     $values = [];
    //     $toload = [];
    //
    //
    //     $fechas = $this::fechas($last30, $tday);
    //     $errors = Parciales::find()->groupBy('nombre_ventana')->all();
    //
    //     foreach ($errors as $error) {
    //         $tempErrors[strtolower($error->nombre_ventana)] = [];
    //     }
    //
    //     foreach($fechas as $date)
    //         {
    //
    //             $labelLast30Graph[] = substr($date,5,5);
    //
    //         }
    //
    //
    //      $maquina = Turno::find()->where(['id' => $id])->all();
    //
    //      if (count($maquina) > 0) {
    //          foreach ($maquina as $model) {
    //             unset($tempErrors);
    //             unset($values);
    //             unset($toload);
    //             $toload = [];
    //
    //              foreach ($errors as $error) {
    //                  $tempErrors[strtolower($error->nombre_ventana)] = [];
    //             }
    //
    //             foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //             }
    //
    //             foreach ($model->getPartialerrors($last30,$today) as $merr) {
    //                 array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
    //             }
    //
    //             foreach ($tempErrors as $temp ) {
    //                 unset($values);
    //                 foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //                 }
    //                 foreach ($temp as $te) {
    //                     $hora = Totales::findOne($te['id_totales'])->hora_inicio;
    //                     $index = array_search(substr($hora, 0,10), $fechas);
    //                     $values[$index] = $te['error'];
    //                 }
    //                 array_push($toload, $values);
    //
    //             }
    //                 array_push($last30Graph,['label' => Yii::t('app','% Deviation'),'data' => $model->getTotalrech($last30,$today), 'borderColor' => 'rgb(216,76,26)', 'backgroundColor' => 'rgb(216,76,26)']);
    //                 array_push($last30Graph,['label' => Yii::t('app','Real Production'),'data' => $model->getTotalprod($last30,$today), 'borderColor' => 'rgb(40,45,250)', 'backgroundColor' => 'rgb(40,45,250)']);
    //                 array_push($last30Graph,['label' => Yii::t('app','Planned Production'),'data' => $model->getTotalprodest($last30,$today),'borderColor' => 'rgb(40,250,40)', 'backgroundColor' => 'rgb(40,250,40)']);
    //
    //
    //          }
    //
    //      }
    //
    //
    //      return $this->render('performancebar',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange, 'turnoref' => $turnoref]);
    //
    // }
    //
    // public function actionPerformanceall()
    // {
    //      if (Yii::$app->request->post()) {
    //
    //         $tday = date('Y-m-d', strtotime(substr(Yii::$app->request->post("Drange")["range"], -10)));
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime (substr(Yii::$app->request->post("Drange")["range"], 0,10)) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //
    //
    //     }else{
    //         $tday = date('Y-m-d');
    //         $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
    //         $last30 = strtotime ( '-7 day' , strtotime ( $tday ) ) ;
    //         $last30 = date ( 'Y-m-d' , $last30 );
    //         $today = date ( 'Y-m-d' , $today );
    //
    //     }
    //     $maqref = new Maquina();
    //     $drange = new Drange();
    //
    //
    //     $last30Graph = [];
    //     $labelLast30Graph = [];
    //
    //
    //     $errorsGraph = [];
    //     $tempErrors = [];
    //     $values = [];
    //     $toload = [];
    //
    //
    //     $fechas = $this::fechas($last30, $tday);
    //     $errors = Parciales::find()->groupBy('nombre_ventana')->all();
    //
    //     foreach ($errors as $error) {
    //         $tempErrors[strtolower($error->nombre_ventana)] = [];
    //     }
    //
    //     foreach($fechas as $date)
    //         {
    //
    //             $labelLast30Graph[] = substr($date,5,5);
    //
    //         }
    //
    //
    //      $maquina = Turno::find()->all();
    //
    //      if (count($maquina) > 0) {
    //          foreach ($maquina as $model) {
    //             unset($tempErrors);
    //             unset($values);
    //             unset($toload);
    //             $toload = [];
    //
    //              foreach ($errors as $error) {
    //                  $tempErrors[strtolower($error->nombre_ventana)] = [];
    //             }
    //
    //             foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //             }
    //
    //             foreach ($model->getPartialerrors($last30,$today) as $merr) {
    //                 array_push($tempErrors[strtolower($merr['nombre_ventana'])], $merr);
    //             }
    //
    //             foreach ($tempErrors as $temp ) {
    //                 unset($values);
    //                 foreach ($fechas as $fecha) {
    //                     $values [] = 0 ;
    //                 }
    //                 foreach ($temp as $te) {
    //                     $hora = Totales::findOne($te['id_totales'])->hora_inicio;
    //                     $index = array_search(substr($hora, 0,10), $fechas);
    //                     $values[$index] = $te['error'];
    //                 }
    //                 array_push($toload, $values);
    //
    //             }
    //
    //                $color = ''.rand(0,255).','.rand(0,255).','.rand(0,255).'';
    //                 //array_push($last30Graph,['type' => 'line','label' => 'Producción Estimada','data' => $model->getTotalprodest($last30,$today),'fill' => 'false', 'borderColor' => 'rgb(40,250,40)', 'backgroundColor' => 'rgb(40,250,40)']);
    //                 array_push($last30Graph,['label' => $model->identificador,'data' => $model->getTotalprod($last30,$today),'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
    //
    //          }
    //
    //      }
    //
    //
    //      return $this->render('performanceall',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange]);
    //
    // }
    //
    // /**
    //  * Finds the Turno model based on its primary key value.
    //  * If the model is not found, a 404 HTTP exception will be thrown.
    //  * @param integer $id
    //  * @return Turno the loaded model
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // protected function findModel($id)
    // {
    //     if (($model = Turno::findOne($id)) !== null) {
    //         return $model;
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     }
    // }
    //
    // public function fechas($start, $end) {
    //     $range = array();
    //
    //     if (is_string($start) === true) $start = strtotime($start);
    //     if (is_string($end) === true ) $end = strtotime($end);
    //
    //     if ($start > $end) return createDateRangeArray($end, $start);
    //
    //     do {
    //         $range[] = date('Y-m-d', $start);
    //         $start = strtotime("+ 1 day", $start);
    //     } while($start <= $end);
    //
    //     return $range;
    // }
}
