<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php  // echo $this->render('_search', ['model' => $searchModel]);?>

   <!-- <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <div class="row">
        <div class="col-md-2 col-md-offset-5 section-options">
            <a class="option-reference"  href="<?= Url::toRoute('/site/signup')?>"><img class="img-responsive" src="<?= Url::to('res/icon_profile.png')?>"><?php echo Yii::t('app', 'NEW USER')?></a>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
             'name',
             'surname',
             array(
            'attribute' => 'role',
            'value'=> 'r',
            ),
             ['class' => '\kartik\grid\BooleanColumn',
             'attribute' => 'status',
            'trueLabel' => Yii::t('app','Yes'), 
            'falseLabel' => Yii::t('app','No')
            ],
            //'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'avatar',
            

           [
    'class' => 'kartik\grid\ActionColumn',
    'dropdown' => false,
    'vAlign'=>'middle',
    'template' => '{update} {turno} {reset} {delete}',
    'urlCreator' => function($action, $model, $key, $index) { 
            return Url::to([$action,'id'=>$key]);
    },
    'buttons'=>[
        'update' => function ($url, $model, $key) {
        if (User::findOne(Yii::$app->user->getId())->getRole() == 'Production Manager' || (User::findOne(Yii::$app->user->getId())->getRole() == 'Shift Manager' && User::turnoinarray($model->turnosint(),User::findOne(Yii::$app->user->getId())->turnosint()))) {
                return Html::a('<span class="fa fa-pencil"></span>', ['update', 'id'=>$model->id],['title'=> Yii::t('app','Update')]);
            }
        },
        'turno' => function ($url, $model, $key) {
        if(Yii::$app->user->identity->getId() != $model->id){
            if (User::findOne(Yii::$app->user->getId())->getRole() == 'Production Manager' || (User::findOne(Yii::$app->user->getId())->getRole() == 'Shift Manager' && User::turnoinarray($model->turnosint(),User::findOne(Yii::$app->user->getId())->turnosint()))) {
                return Html::a('<span class="fa fa-clock-o"></span>', ['turno/asignar', 'id'=>$model->id],['title'=> Yii::t('app','Work Shift')]);
            }
        }
        },
        'reset' => function ($url, $model, $key) {
        if(Yii::$app->user->identity->getId() != $model->id){
            if (User::findOne(Yii::$app->user->getId())->getRole() == 'Production Manager' || (User::findOne(Yii::$app->user->getId())->getRole() == 'Shift Manager' && User::turnoinarray($model->turnosint(),User::findOne(Yii::$app->user->getId())->turnosint()))) {
                return Html::a('<span class="fa fa-key "></span>', ['resetpass', 'id'=>$model->id],['data' => [
                    'confirm' => Yii::t('app','Do you want to reset password to "123456"?'),
                    'method' => 'post',
                ],'title'=>Yii::t('app', Yii::t('app','Reset Password'))]);
            }
        }
        },
        'delete' => function ($url, $model, $key) {
                    if(Yii::$app->user->identity->getId() != $model->id){
                        if (User::findOne(Yii::$app->user->getId())->getRole() == 'Production Manager' || (User::findOne(Yii::$app->user->getId())->getRole() == 'Shift Manager' && User::turnoinarray($model->turnosint(),User::findOne(Yii::$app->user->getId())->turnosint()))) {
                            return Html::a('<span class="fa fa-lock "></span>', ['delete', 'id'=>$model->id],['data' => [
                                'confirm' => Yii::t('app','Do you want to change access for this user?'),
                                'method' => 'post',
                            ],'title'=>Yii::t('app', Yii::t('app','Change Access'))]);
                        }
                    }
        },
    ],      
    'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
   
     ],
        ],
    ]); ?>
</div>
