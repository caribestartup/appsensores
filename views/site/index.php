<?php
use yii\helpers\Url;
use yii\web\User;

/* @var $this yii\web\View */

$this->title = 'WELCOME';
?>
<div class="site-index">

<?php /*
  $data = file_get_contents(Url::to("Visio.json"));
  $products = json_decode($data, true);

  foreach ($products as $product) {
    print_r($product["data"]);
  } */
?>
<div class="section-info">
	<div><h2><b><?php echo Yii::t('app','WELCOME') ?></b></h2></div>
	<div><p><?php echo strtoupper(Yii::$app->user->identity->name);echo " "; echo strtoupper(Yii::$app->user->identity->surname) ?></p></div>
	<div><p><?php echo strtoupper(Yii::$app->user->identity->getR()); ?></p></div>
</div>

<div class="section-options">
	<?php if( Yii::$app->user->identity->getRole() != 'Operator' ) { ?>
		<div class="row">

			<div class="col-md-2 col-md-offset-1">
				<a class="option-reference"  href="<?= Url::toRoute('/user/index')?>"><img class="img-responsive" src="<?= Url::to('res/icon_profile.png')?>"><?php echo Yii::t('app','USERS') ?></a>
			</div>

			<div class="col-md-2 col-md-offset-2">
				<a class="option-reference" href="<?= Url::toRoute('/user/operarios')?>"><img class="img-responsive" src="<?= Url::to('res/icon_operator.png')?>"><?php echo Yii::t('app','OPERATORS\' PERFORMANCE') ?></a>
			</div>

			<div class="col-md-2 col-md-offset-2">
				<a class="option-reference" href="<?= Url::toRoute('/turno/index')?>"><img class="img-responsive" src="<?= Url::to('res/icon_gauge.png')?>"><?php echo Yii::t('app','WORK SHIFTS') ?></a>
			</div>

		</div>

		<div class="row">

			<div class="col-md-2 col-md-offset-1">
				<a class="option-reference" href="<?= Url::toRoute('/site/proccess')?>"><img class="img-responsive" src="<?= Url::to('res/icon_proccess.png')?>"><?php echo Yii::t('app','PROCESS') ?></a>
			</div>

			<div class="col-md-2 col-md-offset-2">
				<a class="option-reference" href="<?= Url::toRoute('/maquina/charts')?>"><img class="img-responsive" src="<?= Url::to('res/icon_charts.png')?>"><?php echo Yii::t('app','GRAPHS') ?></a>
			</div>

			<div class="col-md-2 col-md-offset-2">
				<a class="option-reference" href="<?= Url::toRoute('/local/index')?>"><img class="img-responsive" src="<?= Url::to('res/icon_map.png')?>"><?php echo Yii::t('app','EDIT PLANE') ?></a>
			</div>

		</div>
	<?php } else {?>
		<div class="row">

			<div class="col-md-2 col-md-offset-1">
				<a class="option-reference" href="<?= Url::toRoute('/maquina/assigne')?>"><?php echo Yii::t('app','Link Machine') ?></a>
			</div>

			<div class="col-md-2 col-md-offset-2">
				<a class="option-reference" href="<?= Url::toRoute('/asignacion/index')?>"><?php echo Yii::t('app','Assign Machines') ?></a>
			</div>

			<div class="col-md-2 col-md-offset-2">
				<a class="option-reference" href="<?= Url::toRoute('/local/index')?>"><img class="img-responsive" src="<?= Url::to('res/icon_map.png')?>"><?php echo Yii::t('app','EDIT PLANE') ?></a>
			</div>

		</div>
	<?php }?>

</div>

</div>
