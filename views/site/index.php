<?php
use yii\helpers\Url;
use yii\web\User;

/* @var $this yii\web\View */

$this->title = 'Appsesores';
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
		<div class="row col-md-12">

			<div class="col-md-3">
				<a class="option-reference"  href="<?= Url::toRoute('/user/index')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/icon_profile.png')?>"><?php echo Yii::t('app','USERS') ?></a>
			</div>

			<div class="col-md-3">
				<a class="option-reference" href="<?= Url::toRoute('/user/operarios')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/icon_operator.png')?>"><?php echo Yii::t('app','OPERATORS\' PERFORMANCE') ?></a>
			</div>

			<div class="col-md-3">
				<a class="option-reference" href="<?= Url::toRoute('/turno/index')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/icon_gauge.png')?>"><?php echo Yii::t('app','WORK SHIFTS') ?></a>
			</div>

			<div class="col-md-3 ">
				<a class="option-reference" href="<?= Url::toRoute('/site/proccess')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/icon_proccess.png')?>"><?php echo Yii::t('app','PROCESS') ?></a>
			</div>

		</div>

		<div class="row col-md-12">
			<div class="col-md-3 ">
				<a class="option-reference" href="<?= Url::toRoute('/maquina/charts')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/icon_charts.png')?>"><?php echo Yii::t('app','GRAPHS') ?></a>
			</div>

			<div class="col-md-3 hidden-xs">
				<a class="option-reference" href="<?= Url::toRoute('/local/index')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/icon_map.png')?>"><?php echo Yii::t('app','EDIT PLANE') ?></a>
			</div>

			<div class="col-md-3 ">
				<a class="option-reference" href="<?= Url::toRoute('/pedido/index')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/menuOrder.png')?>"><?php echo Yii::t('app','ORDES') ?></a>
			</div>
			<div class="col-md-3 ">
				<a class="option-reference" href="<?= Url::toRoute('/maquina/index')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/menuMaquina.png')?>"><?php echo Yii::t('app','MACHINES') ?></a>
			</div>

		</div>

	<?php } else {?>
		<div class="row col-md-12">

			<!-- <div class="col-md-3 ">
				<a class="option-reference" href="<?= Url::toRoute('/maquina/assigne')?>"><?php echo Yii::t('app','Link Machine') ?></a>
			</div> -->
			<div class="col-md-3 ">
			</div>
			<div class="col-md-3 ">
				<a class="option-reference" href="<?= Url::toRoute('/asignacion/index')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/menuMaquina.png')?>"><?php echo Yii::t('app','Assign Machines') ?></a>
			</div>

			<div class="col-md-3 ">
				<a class="option-reference" href="<?= Url::toRoute('/pedido/index')?>"><img class="img-responsive col-xs-12" src="<?= Url::to('res/menuOrder.png')?>"><?php echo Yii::t('app','Manage Orders') ?></a>
			</div>
			<div class="col-md-3 ">
			</div>

		</div>
	<?php }?>

</div>

</div>
