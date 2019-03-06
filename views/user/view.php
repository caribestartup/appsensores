<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name." ".$model->surname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Usuarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="row section-options">
        <p>
            <div class="col-md-2 col-md-offset-5">
                <a class="option-reference"  href="<?= Url::toRoute('/user/update')?>&id=<?= $model->id?>"><img class="img-responsive" src="<?= Url::to('res/icon_edit.png')?>"></a>
            </div>
        </p>
    </div>

    <div class="row section-options">
        <h3>NOMBRE</h3>
        <p><?= $model->name." ".$model->surname ?></p>
        <h3>USUARIO</h3>
        <p><?= $model->username ?></p>
        <h3>PERMISO</h3>
        <p><?= $model->r ?></p>
         <h3>CORREO</h3>
        <p><?= $model->email ?></p>
    </div>

    <!--<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            'created_at',
            'updated_at',
            'avatar',
            'name',
            'surname',
            'role',
        ],
    ]) ?>-->

</div>
