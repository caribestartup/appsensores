<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\Maquina;

AppAsset::register($this);

$maquina = Maquina::getAllmachines();
$encendida = [];
$apagada = [];
$error = [];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="kyp-menu">

  <img id="menu-img-back" src="<?= Url::to('res/menu.png')?>">

	<div class="row menu-container nav-showed">

		<div class="col-md-4 menu-block">
			<div><label>TURNO: 3</label></div>
			<div><b>HORARIO: </b> 8:00 am - 4:00 pm</div>
		</div>

		<div class="col-md-4 menu-block">
			<div><label>SELECCIONE MÁQUINA</label></div>
      <div>
          <a class="option-reference" href="<?= Url::toRoute('site/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_home.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('local/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_map.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('/maquina/charts')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_charts.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('site/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_proccess.png')?>"></a>
      </div>
		</div>

		<div class="col-md-4 menu-block">
			<div><label>PRODUCCIÓN </label><sub> TURNO ACTUAL</sub></div>
			<div><b>PRODUCCIÓN ACTUAL:</b>  895 <img class="menu-img pull-right" src="<?= Url::to('res/eliminar_edicion.png')?>"></div>
			<div style="margin-top: 2%"><b>PRODUCCIÓN ESTIMADA:</b>  12000 <i class="pull-right" style="color:red">125</i></div>
		</div>

	</div>

</div>

<div class="wrap">
   
	<div clas="row">

		<?php foreach ($maquina as $maq) { ?>
			<?php 
				switch ($maq->getRealstate()) {
					case -1:
						$error[] = $maq;
						break;
					case 0:
						$apagada[] = $maq;
						break;
					case 1:
						$encendida[] = $maq;
						break;
					
					default:
						break;
				}
			?>
		<?php } ?>

		<div class="col-md-2">

			<?php if($encendida){ ?>
    			<label>MÁQUINAS ENCENDIDAS</label>
    		<?php } ?>
    		<?php foreach ($encendida as $machine) { ?>

    			<div class="machine machine-ok">
					<div class="machine-description">
						<img class="pull-left machine-img" src="<?= Url::to('res/icon_yes.png')?>">
						<label><?= strtoupper($machine->nombre) ?></label>
						<p class="text-primary">435/800</p>
						<a href="<?= Url::toRoute(['/maquina/production', 'id' => $machine->maquina_id])?>"><img class="img-responsive machine-back-img" src="<?= Url::to('res/machine_ok.png')?>"></a>
					</div>
				</div>
    			
    		<?php } ?>
			

			<?php if($apagada){ ?>
    			<label style="margin-top:10px">MÁQUINAS APAGADAS</label>
    		<?php } ?>
    		<?php foreach ($apagada as $machine) { ?>

    			<div class="machine machine-off">
					<div class="machine-description">
						<img class="pull-left machine-img" src="<?= Url::to('res/icon_no.png')?>">
						<label><?= strtoupper($machine->nombre) ?></label>
						<p class="text-danger">APAGADA</p>
						<a href="<?= Url::toRoute(['/maquina/production', 'id' => $machine->maquina_id])?>"><img class="img-responsive machine-back-img" src="<?= Url::to('res/machine_login.png')?>"></a>
					</div>
				</div>
    			
    		<?php } ?>

		</div>

		
    	<div class="col-md-2 machine-container-right pull-right">

    		<?php if($error){ ?>
    			<label>MÁQUINAS EN ERROR</label>
    		<?php } ?>
    		<?php foreach ($error as $machine) { ?>

    			<div class="machine machine-error">
					<div class="machine-description">
						<img class="machine-img" src="<?= Url::to('res/icon_no.png')?>">
						<label><?= strtoupper($machine->nombre) ?></label>
						<p class="text-danger">435/800</p>
						<a href="<?= Url::toRoute(['/maquina/production', 'id' => $machine->maquina_id])?>"><img class="img-responsive machine-back-img" src="<?= Url::to('res/machine_error.png')?>"></a>
					</div>
				</div>
    			
    		<?php } ?>

		</div>

		<div class="container container-main col-md-8">
        	<?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        	]) ?>
        	<?= $content ?>
    	</div>


	</div>

</div>


<?php $this->endBody() ?>

<!--==================SCRIPTS===================-->
<script type="text/javascript">
// Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('.kyp-menu').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $('.kyp-menu').removeClass('nav-down').addClass('nav-up');
        $('.menu-container').removeClass('nav-showed').addClass('nav-up');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('.kyp-menu').removeClass('nav-up').addClass('nav-down');
            $('.menu-container').removeClass('nav-up').addClass('nav-showed');
        }
    }
    
    lastScrollTop = st;
}

$(document).ready(function(){
  $('.container-main').css('height',$(window).height());
});
</script>


<!--==================SCRIPTS END===================-->
</body>
</html>
<?php $this->endPage() ?>
