<?php

namespace app\controllers;

use Yii;
use app\models\Parciales;
use app\models\User;
use app\models\UserSearch;
use app\models\Pass;
use app\models\Role;
use app\models\Maquina;
use app\models\Totales;
use app\models\Error;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\UiHelper;
use app\models\Drange;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','operarios','profile','errors','performance','changepass','resetpass', 'machine'],
                'rules' => [
                    [
                        'actions' => ['view','update','delete','operarios','profile','changepass','resetpass'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                        $valid_roles = ['Production Manager'];
                        return User::roleInArray($valid_roles) && User::isActive();
                        }
                     ],
                     [
                         'actions' => ['errors','performance','index', 'machine'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                         $valid_roles = ['Production Manager','Shift Manager'];
                         return User::roleInArray($valid_roles) && User::isActive();
                         }
                         ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $role = Role::find()->all();

        /*$arrRole = [];
        for ($i=Yii::$app->user->identity->role; $i < count($role)+1; $i++) {
            array_push($arrRole, $i);
        }
        $dataProvider->query->andWhere(['role'=> $arrRole]);
        */
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOperarios()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['role'=>'4']);

        return $this->render('operarios', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionProfile()
    {

        return $this->render('profile', [
            'model' => $this->findModel(Yii::$app->user->identity->getId()),
        ]);
    }

     public function actionErrors($id)
    {
         if (Yii::$app->request->post()) {

            $tday = date('Y-m-d', strtotime(substr(Yii::$app->request->post("Drange")["range"], -10)));
            $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
            $last30 = strtotime (substr(Yii::$app->request->post("Drange")["range"], 0,10)) ;
            $last30 = date ( 'Y-m-d' , $last30 );
            $today = date ( 'Y-m-d' , $today );



        }else{
            $tday = date('Y-m-d H:i:s');
            $today = strtotime ( '+1 day' , strtotime ( $tday ) ) ;;
            $last30 = strtotime ( '-30 day' , strtotime ( $tday ) ) ;
            $last30 = date ( 'Y-m-d H:i:s' , $last30 );
            $today = date ( 'Y-m-d H:i:s' , $today );

        }
        $maqref = new Maquina();
        $drange = new Drange();

        $last30Graph = [];
        $labelLast30Graph = [];


        $errorsGraph = [];
        $tempErrors = [];
        $values = [];
        $toload = [];


        $fechas = $this::fechas($last30, $tday);
        $errors = Error::find()->all();

        foreach ($errors as $error) {
            $errorsGraph[$error->ventana] = [];
            $tempErrors[$error->ventana] = [];
        }

        foreach($fechas as $date)
        {
            $labelLast30Graph[] = substr($date,5,5);
        }


         $maquina = User::find()->where(['id' => $id])->all();

         if (count($maquina) > 0) {
             foreach ($maquina as $model) {
                unset($tempErrors);
                unset($values);
                unset($toload);
                $toload = [];

                 foreach ($errors as $error) {
                    $tempErrors[$error->ventana] = [];
                }

                foreach ($fechas as $fecha) {
                        $values [] = 0 ;
                }

                foreach ($model->getPartialerrors($last30,$today) as $merr) {
                    array_push($tempErrors[$merr['ventana']], $merr);
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
                        array_push($errorsGraph[$rec], ['label' => ''.$model->name.' '.$model->surname.'','data' => $tl,'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
                    }
                    array_push($last30Graph,['label' => ''.$model->name.' '.$model->surname.'','data' => $model->getTotalerrors($last30,$today),'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);

             }

         }


         return $this->render('errors',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange]);

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
        $maqref = new Maquina();
        $drange = new Drange();
        $userref = User::findOne($id);


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


         $maquina = User::find()->where(['id' => $id])->all();

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


         return $this->render('performance',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange, 'userref' => $userref]);

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
        $maqref = new Maquina();
        $drange = new Drange();
        $userref = User::findOne($id);


        $last30Graph = [];
        $labelLast30Graph = [];


        $errorsGraph = [];
        $tempErrors = [];
        $values = [];
        $toload = [];


        $fechas = $this::fechas($last30, $tday);
        $errors = Error::find()->all();

        foreach ($errors as $error) {
            $tempErrors[$error->id] = [];
        }

        foreach($fechas as $date) {
            $labelLast30Graph[] = substr($date,5,5);
        }


         $maquina = User::find()->where(['id' => $id])->all();

         if (count($maquina) > 0) {
             foreach ($maquina as $model) {
                unset($tempErrors);
                unset($values);
                unset($toload);
                $toload = [];

                foreach ($errors as $error) {
                    $tempErrors[$error->id] = [];
                }

                foreach ($fechas as $fecha) {
                    $values [] = 0 ;
                }

                foreach ($model->getPartialerrors($last30,$today) as $merr) {
                   array_push($tempErrors[$merr['ventana']], $merr);
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

                array_push($last30Graph,['label' => Yii::t('app', '% Deviation'),'data' => $model->getTotalrech($last30,$today), 'borderColor' => 'rgb(216,76,26)', 'backgroundColor' => 'rgb(216,76,26)']);
                array_push($last30Graph,['label' => Yii::t('app', 'Real Production'),'data' => $model->getTotalprod($last30,$today), 'borderColor' => 'rgb(40,45,250)', 'backgroundColor' => 'rgb(40,45,250)']);
                array_push($last30Graph,['label' => Yii::t('app', 'Planned Production'),'data' => $model->getTotalprodest($last30,$today),'borderColor' => 'rgb(40,250,40)', 'backgroundColor' => 'rgb(40,250,40)']);


             }

         }


         return $this->render('performancebar',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange, 'userref' => $userref]);

    }

    public function actionMachine($id)
    {
        return $this->render('performanceMachine', [
            
        ]);
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
        $maqref = new Maquina();
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


        $maquina = User::find()->where(['role' => 4])->all();

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
                //array_push($last30Graph,['label' => $model->name.' '.$model->surname,'data' => $model->getTotalrech($last30,$today), 'borderColor' => 'rgb(216,76,26)', 'backgroundColor' => 'rgb(216,76,26)']);
                array_push($last30Graph,['label' => $model->name.' '.$model->surname,'data' => $model->getTotalprod($last30,$today),'fill' => 'false', 'borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);
                //array_push($last30Graph,['type' => 'line','label' => 'Producción Estimada','data' => $model->getTotalprodest($last30,$today),'fill' => 'false','borderColor' => 'rgb('.$color.')', 'backgroundColor' => 'rgb('.$color.')']);


            }

        }


        return $this->render('performancemacbar',['last30Graph' => $last30Graph,'labelLast30Graph' => $labelLast30Graph,'errorsGraph' => $errorsGraph,'maqref' => $maqref,'drange' => $drange]);

    }



    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if (Yii::$app->request->post()) {
        $model->name = $_POST['User']['name'];
        $model->surname = $_POST['User']['surname'];
        $model->username = $_POST['User']['username'];
        $model->email = $_POST['User']['email'];

        $model->save();
        return $this->redirect(['index']);
            }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->status == 1){
            $model->status = 0;
            $model->save();
        }
        else{
            $model->status = 1;
            $model->save();
        }

        return $this->redirect(['index']);
    }

    public function actionChangepass(){
        $model = new Pass();

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->security->validatePassword($model->old_pass, Yii::$app->user->identity->password_hash)){
                $user = User::findOne( Yii::$app->user->identity->id);
                $user->setPassword($model->password);
                $user->generateAuthKey();
                $user->save();

                UiHelper::alert("<h4><i class='icon fa fa-key'></i> ". Yii::t('app','Success!')."</h4><br>". Yii::t('app','The password has been changed'), UiHelper::SUCCESS);
                return $this->goHome();
            }
            else{
                $model = new Pass();
                UiHelper::callout("<h4><i class='icon fa fa-key'></i> ". Yii::t('app','Error')."</h4><br>". Yii::t('app','Invalid old password'), UiHelper::DANGER);
                return $this->render('pass', [
                'model' => $model,
                ]);
            }

        }

        return $this->render('pass', [
            'model' => $model,
        ]);
    }

    public function actionResetpass($id)
    {

        $model = $this->findModel($id);
        $model->setPassword("123456");
        $model->save();

        UiHelper::alert('<i class="icon fa fa-key"></i>'.Yii::t('app','Password for ').$model->username.Yii::t('app',' has been reseted'), UiHelper::SUCCESS);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
