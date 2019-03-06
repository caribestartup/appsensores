<?php

namespace app\controllers;

use Yii;
use app\models\Local;
use app\models\LocalSearch;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Maquina;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 * LocalController implements the CRUD actions for Local model.
 */
class LocalController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','saveimg','control','loadlocal'],
                'rules' => [
                    [
                        'actions' => ['index','view','create','update','delete','saveimg','control','loadlocal'],
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
     * Lists all Local models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $dataProvider = Local::find()->all();
        $maquina = Maquina::find()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'maquina' => $maquina,
        ]);
    }

    /**
     * Displays a single Local model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Local model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Local();

        if ($model->load(Yii::$app->request->post())) {
            $model->plano = UploadedFile::getInstance($model, 'plano');
            $imageName = $model->nombre;
            $model->plano->saveAs('plano/'.$imageName.'.'.$model->plano->extension);
            $model->plano = 'plano/'.$imageName.'.'.$model->plano->extension;
            $model->save();
            return $this->redirect(['view', 'id' => $model->local_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Local model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->plano = UploadedFile::getInstance($model, 'plano');
            $imageName = $model->nombre;
            $model->plano->saveAs('plano/'.$imageName.'.'.$model->plano->extension);
            $model->plano = 'plano/'.$imageName.'.'.$model->plano->extension;
            $model->save();
            return $this->redirect(['view', 'id' => $model->local_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Local model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Local model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Local the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Local::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*AJAX ACTIONS*/
    public function actionSaveimg($id, $posx, $posy, $width, $height, $matrix)
    {

        
        $machine = Maquina::findOne($id);
        if ($machine){
            $machine->posx =substr($posx, 0, -2);
            $machine->posy = substr($posy, 0, -2);
            $machine->ancho = substr($width, 0, -2);
            $machine->largo = substr($height, 0, -2);
            $machine->matrix = $matrix;
            $machine->save();
            echo Yii::t('app','Machines\' Positions saved and recorded');
        }
      
    }

     public function actionControl()
    {
       $machine = Maquina::findOne(Yii::$app->request->post('id'));

        $json_vars["x"] = $machine->posx; 
        $json_vars["y"] = $machine->posy;
        $json_vars["w"] = $machine->ancho;
        $json_vars["h"] = $machine->largo;
        $json_vars["m"] = $machine->matrix; 
        $json_vars["i"] = $machine->maquina_id;   

        echo json_encode($json_vars);

      
    }

    public function actionLoadlocal($id)
    {
        $model = Local::findOne($id);
        $maquina = Maquina::find()->where(['local'=>$model->local_id])->all();
        echo '<img id="plan-back" class="img-all-width" src="'.Url::to($model->plano).'">';

            if ($maquina) {
                foreach ($maquina as $machine) {
                   /*echo '<div class="mecha draggable resizable" style="position:absolute; top:'.$machine->posy.'%; left:'.$machine->posx.'%; width:'.$machine->ancho.'%; height:'.$machine->largo.'%" value="'.$machine->maquina_id.'">'; 
                   echo '<img style="width:100%; height:100%" src="'.Url::to("maquina/horizontal.png").'">';                
                   echo "</div>"; */
                   echo '<img src="'.Url::to("maquina/horizontal.png").'" class="mecha" value="'.$machine->maquina_id.'"></img>';
                }
            }
    }


}
