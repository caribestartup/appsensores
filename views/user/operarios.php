<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Operators');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
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
            'template' => '{performance} {error}',
            'urlCreator' => function($action, $model, $key, $index) { 
                    return Url::to([$action,'id'=>$key]);
            },
            'buttons'=>[
            
                'performance' => function ($url, $model, $key) {
                    return Html::a('<span class="fa fa-bar-chart "></span>', ['performance', 'id'=>$model->id],['title'=> Yii::t('app','Performance')]);
                },
                'error' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-line-chart "></span>', ['errors', 'id'=>$model->id],['title'=> Yii::t('app','Errors')]);
                },
            ],      
          
        ]

           
        ],
    ]); ?>
</div>
