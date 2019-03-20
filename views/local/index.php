<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LocalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Plane Edit');
?>
<div class="local-index">

    <div class="row">

        <!-- <div class="sub-menu"> -->
            <div class="sub-menu-title"><h4><?php echo Yii::t('app','Plane Edit')?></h4></div>
            <div class="row sub-menu-options">
                <div class="col-md-3 col-md-offset-6">
                    <select id="locales"><option><?php echo Yii::t('app','Select')?></option>
                        <?php foreach ($dataProvider as $local) {
                            echo "<option value=".$local->local_id.">".$local->nombre."</option>";
                        } ?>
                    </select>
                </div>

            </div>
        <!-- </div> -->
        <div class="col-md-1 pull-right"><a class="option-reference" href="#" id="asave" style="display: none;"><img id="plan-save" class="img-responsive" style="cursor:pointer" src="<?=Url::to('res/icon_yes.png')?>"></a></div>
        <div class="col-md-1 pull-right"><a class="option-reference" href="#" id="aedit"><img class="img-responsive" src="<?=Url::to('res/icon_edit.png')?>"></a></div>
        <div class="col-md-1 pull-right"><a class="option-reference" href="<?= Url::toRoute('local/create') ?>"><img class="img-responsive" src="<?=Url::to('res/icon_plan.png')?>"></a></div>

    </div>


    <div class="row">
        <div class="plan-container" id="plan-container"></div>
    </div>




</div>
