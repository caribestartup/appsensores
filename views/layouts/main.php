<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\widgets\Flash;
use yii\helpers\Url;
use app\models\Maquina;
use app\widgets\Alert;

AppAsset::register($this);

$maquina = Maquina::getAllmachines();
$encendida = [];
$apagada = [];
$error = [];

$turno = Maquina::getLasturno();

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

<style>
    .menu-font {
        font-size: larger;
        font-weight: 600;
        color: black;
    }

    .menu-fotnt:hover{
        /* color: #00000070; */
        color: red;
    }

    .menu-font-logo {
        font-size: x-large;
        font-weight: 600;
    }

    .nav-fixed {
        /* padding: 10px; */
        z-index: 1000;
        background-color: white;
        width: 100%;
        left: 0;
        top: 0;
        position: fixed;
    }

    .remove-li-point{
        list-style: none;
    }

    .machine-top {
        margin-top: 80px;
    }

    .navbar-ul-top {
        margin-top: 10px;
    }

    @media only screen and (max-width: 768px) {
      /* For mobile phones: */
      .buttons {
        text-align: center;
      }
    }
</style>

<div class="kyp-menu">

  <img id="menu-img-back" src="<?= Url::to('res/menu.png')?>">

	<div class="row menu-container nav-showed">


		<div class="col-md-2 col-xs-4 menu-block hidden-xs">
			<div><label><?php if(count($turno) != 0){echo $turno[0]["identificador"];}else{echo Yii::t('app','WORK SHIFT');}?></label></div>
			<div><b><?php echo Yii::t('app','WORKING HOURS') ?>: </b><?php if(count($turno) != 0){echo substr($turno[0]["inicio"],0, -3);}else{echo "00:00:00";}?> - <?php if(count($turno) != 0){echo substr($turno[0]["fin"], 0, -3);}else{echo "00:00:00";}?></div>
		</div>

        <?php if( Yii::$app->user->identity->getRole() != 'Operator' ) { ?>
    		<div class="col-md-5 col-xs-12 menu-block">
                <a class="option-reference col-md-3 col-xs-3" href="<?= Url::toRoute('site/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_home.png')?>"></a>
                <a class="option-reference col-md-3 col-xs-3" href="<?= Url::toRoute('local/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_map.png')?>"></a>
                <a class="option-reference col-md-3 col-xs-3" href="<?= Url::toRoute('/maquina/charts')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_charts.png')?>"></a>
                <a class="option-reference col-md-3 col-xs-3" href="<?= Url::toRoute('/site/proccess')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_proccess.png')?>"></a>
            </div>
        <?php } else {?>
            <div class="col-md-5 col-xs-12 menu-block">
                <a class="col-md-2 col-xs-1" href="#"></a>
                <a class="option-reference col-md-3 col-xs-3" href="<?= Url::toRoute('site/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_home.png')?>"></a>
                <a class="option-reference col-md-3 col-xs-3" href="<?= Url::toRoute('/asignacion/index')?>"><img class="img-menu-item" src="<?= Url::to('res/menuMaquina.png')?>"></a>
                <a class="option-reference col-md-3 col-xs-3" href="<?= Url::toRoute('/pedido/index')?>"><img class="img-menu-item" src="<?= Url::to('res/menuOrder.png')?>"></a>
            </div>
        <?php }?>

		<div class="col-md-3 col-xs-4 menu-block hidden-xs">
			<div>
                <label><?php echo Yii::t('app','PRODUCTION') ?> </label>
                <sub> <?php echo Yii::t('app','CURRENT SHIFT') ?></sub>
            </div>
			<div>
                <b><?php echo Yii::t('app','PLANNED PRODUCTION') ?>: </b><?= Maquina::getTotalprodestnall() ?>

            </div>
			<div>
                <b><?php echo Yii::t('app','REAL PRODUCTION TODAY') ?>: </b><?= Maquina::getTotalprodnall() ?>
                <img class="menu-img pull-right" src="<?= Url::to('res/eliminar_edicion.png')?>">
                <i class="pull-right" style="color:red">- <?= Maquina::getTotalerrornall() ?></i>

            </div>
        </div>

        <div class="col-md-2 col-xs-12 buttons" style="top:10px">
            <a class="fa fa-key" title="Change Password" href="<?= Url::toRoute('/user/changepass')?>"></a>
            <br class="hidden-xs">
            <a class="fa fa-lock" title="Logout" href="<?= Url::toRoute('/site/logout')?>"></a>
            <!-- <a href="#" >

                <?php echo Html::beginForm(['/user/changepass'], 'post');
                       echo Html::tag('i','',['class' => 'fa fa-key text-primary']);
                       echo Html::submitButton(Yii::t('app','CHANGE PASS'), ['class' => 'btn btn-link']);
                       echo Html::endForm();?>
            </a> -->
            <!-- <a href="#">
                <?php echo Html::beginForm(['/site/logout'], 'post');
                       echo Html::tag('i','',['class' => 'fa fa-lock text-primary']);
                       echo Html::submitButton(Yii::t('app','LOGOUT'), ['class' => 'btn btn-link']);
                       echo Html::endForm();?>
            </a> -->
        </div>



	</div>



</div>

<!-- <nav class="navbar navbar-light bg-white navbar-expand-sm nav-fixed">
  <a class="navbar-brand menu-font-logo" style="color: black;" href="#">
    <img src="<?= Url::to('res/icon_logo.png')?>" width="40" height="40" alt="logo">
    KYP
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-8" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-between" id="navbar-list-8">
    <ul class="navbar-nav navbar-ul-top">
      <li class="nav-item">
        <a class="nav-link menu-font" href="<?= Url::toRoute('site/index')?>">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link menu-font" href="<?= Url::toRoute('local/index')?>">Locals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link menu-font" href="<?= Url::toRoute('/maquina/charts')?>">Graphics</a>
    </li>
      <li class="nav-item">
        <a class="nav-link menu-font" href="<?= Url::toRoute('/site/proccess')?>">Process</a>
      </li>
      <li class="nav-item hidden-md hidden-lg">
        <a class="nav-link menu-font" style="color: black;" href="#">Logout</a>
      </li>
    </ul>

    <div class="right-side d-flex hidden-xs float-right">
      <ul class="navbar-nav">
			<li class="nav-link" style=" width: 250px;">
                <b style="color:red"> <?php echo Yii::t('app','ERRORS:') ?></b>
                <img class="menu-img" src="<?= Url::to('res/eliminar_edicion.png')?>">
                <i class="" style="color:red">
                    <?= Maquina::getTotalerrornall() ?>
                </i>
                <br>
                <b><?php echo Yii::t('app','PLANNED PRODUCTION') ?>:</b>
                <?= Maquina::getTotalprodestnall() ?>
                <br>


                <b><?php echo Yii::t('app','ESTIMATED PRODUCTION') ?>:</b>
                <?= Maquina::getTotalprodnall() ?>

            </li>
          <li class="nav-link">
              <p class="nav-link menu-font" style="margin-top: 15px;"> <?php echo Yii::$app->user->identity->name;echo " "; echo Yii::$app->user->identity->surname ?></p>
          </li>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" style="color: black;" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="<?= Url::to('res/icon_profile.png')?>" width="40" height="40" class="rounded-circle">
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#">Dashboard</a>
            <a class="dropdown-item" href="#">Edit Profile</a>
            <a class="dropdown-item" href="#">Log Out</a>
          </div>
        </li>
      </ul>
    </div>

  </div>
</nav> -->


<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js"></script> -->
<!------ Include the above in your HEAD tag ---------->

<div class="wrap">

	<div clas="row">

		<?php foreach ($maquina as $maq) { ?>
			<?php
				switch ($maq->state) {
					case 'Error':
						$error[] = $maq;
						break;
					case 'Terminado':
						$apagada[] = $maq;
						break;
          case 'Detenido':
						$apagada[] = $maq;
						break;
					case 'Activo':
						$encendida[] = $maq;
						break;

					default:
						break;
				}
			?>
		<?php } ?>

		<div class="col-md-2 col-xs-2 hidden-xs machine-top">

			<?php if($encendida){ ?>
    			<label><?php echo Yii::t('app','MACHINES ON') ?></label>
    		<?php } ?>
    		<?php foreach ($encendida as $machine) { ?>

    			<div class="machine machine-ok">
					<div class="machine-description">
						<img class="pull-left machine-img" src="<?= Url::to('res/icon_yes.png')?>">
						<label><?= strtoupper($machine->nombre) ?></label>
						<p class="text-primary"><?= $machine->getTotalprodn() ?>/<?= $machine->getTotalprodestn() ?></p>
						<a href="<?= Url::toRoute(['/maquina/production', 'id' => $machine->maquina_id])?>">

              <img class="img-responsive machine-back-img" src="<?= Url::to('res/machine_ok.png')?>"></a>

					</div>
				</div>

    		<?php } ?>


			<?php if($apagada){ ?>
    			<label style="margin-top:10px"><?php echo Yii::t('app','MACHINES OFF') ?></label>
    		<?php } ?>
    		<?php foreach ($apagada as $machine) { ?>

    			<div class="machine machine-off">
					<div class="machine-description">
						<img class="pull-left machine-img" src="<?= Url::to('res/icon_no.png')?>">
						<label><?= strtoupper($machine->nombre) ?></label>
						<p class="text-danger"><?php echo Yii::t('app','OFF') ?></p>
						<a href="<?= Url::toRoute(['/maquina/production', 'id' => $machine->maquina_id])?>"><img class="img-responsive machine-back-img" src="<?= Url::to('res/machine_login.png')?>"></a>
					</div>
				</div>

    		<?php } ?>

		</div>


    	<div class="col-md-2 machine-container-right pull-right hidden-xs machine-top">

    		<?php if($error){ ?>
    			<label><?php echo Yii::t('app','MACHINES ERROR') ?></label>
    		<?php } ?>
    		<?php foreach ($error as $machine) { ?>

    			<div class="machine machine-error">
					<div class="machine-description">
						<img class="machine-img" src="<?= Url::to('res/icon_no.png')?>">
						<label><?= strtoupper($machine->nombre) ?></label>
						<p class="text-danger"><?= $machine->getTotalprodn() ?>/<?= $machine->getTotalprodestn() ?></p>
						<a href="<?= Url::toRoute(['/maquina/production', 'id' => $machine->maquina_id])?>"><img class="img-responsive machine-back-img" src="<?= Url::to('res/machine_error.png')?>"></a>
					</div>
				</div>

    		<?php } ?>

		</div>

		<div class="container container-main col-md-8 col-xs-12">
        	<?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        	]) ?>
          <?= Flash::widget() ?>
        	<?= $content ?>
    	</div>


	</div>

</div>

  <!-- Modal -->
 <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
       <div class="modal-header box box-info">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" id="myModalLabel"> <?php echo strtoupper(Yii::T('app','Information!')) ?> </h4>
       </div>
       <div class="modal-body" id="getCode" style="overflow-x: scroll;">

       </div>
       <div class="row">
         <div class="col-md-2 col-md-offset-5">
           <?= Html::Button(Yii::t('app', 'OK!'), ['class' => 'btn btn-success', 'data-dismiss' => "modal", 'style' => 'width:100%; margin-bottom:10px']) ?>
         </div>
       </div>
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

$('#plan-save').click(function(){

  var heightB = $('.plan-container').height();
  var widthB = $('.plan-container').width();

  var id = null;
  var posx = null;
  var posy = null;
  var width = null;
  var height = null;

  //$('.mecha').freetrans('destroy');

    $('.mecha').each(function(key, element){
      id = $(this).attr('value');
      var b = $(this).siblings('div');
      posx = b.css('left');
      posy = b.css('top');
      width = b.css('width');
      height = b.css('height');
      matrix = b.css('transform');



      var param = {"id" :id,
              "posx" : posx,
              "posy" : posy,
              "width" : width,
              "height" : height,
              "matrix" : matrix,
        };

      <?php $url = Url::to(['local/saveimg']); ?>

     $.ajax({
      data:param,
          url:"<?php echo $url;?>",
          type:"get",
          success: function(info){
          order();
          $("#getCode").html(info);
          $("#getCodeModal").modal('show');
          $("#aedit").css("display","block");
          $("#asave").css("display","none");

        },

       });

    });


 });


function order(){

  $('.mecha').each(function($e) {

        id = $(this).attr('value');

        var param = {"id" : id};

           <?php $url = Url::to(['local/control']); ?>

           $.ajax({
            data:param,
                url:"<?php echo $url;?>",
                type:"post",
                success: function(info){

                  var heightB = $('.plan-container').height();
                  var widthB = $('.plan-container').width();

                  var vx = info["x"] ;
                  var vy = info["y"] ;
                  var width = info["w"] ;
                  var height = info["h"];


                  $('.mecha[value='+info["i"]+']').css('width', width);
                  $('.mecha[value='+info["i"]+']').css('height', height);
                  $('.mecha[value='+info["i"]+']').css('top', vy);
                  $('.mecha[value='+info["i"]+']').css('left', vx);


                  var m = getRotationDegrees(info["m"]);
                  //alert(info["m"]);

                  $('.mecha[value='+info["i"]+']').freetrans({
                  x: vx,
                  y: vy,
                  angle: m,

                  })

                  $('.mecha[value='+info["i"]+']').css('transform', info["m"]);

                  $('.mecha[value='+info["i"]+']').freetrans('destroy');

              },
              dataType:"json",
             });





        });
}

function reorder(){

  $('.mecha').each(function($e) {

        id = $(this).attr('value');

        var param = {"id" : id};

           <?php $url = Url::to(['local/control']); ?>

           $.ajax({
            data:param,
                url:"<?php echo $url;?>",
                type:"post",
                success: function(info){

                  var heightB = $('.plan-container').height();
                  var widthB = $('.plan-container').width();

                  var vx = info["x"] ;
                  var vy = info["y"] ;
                  var width = info["w"] ;
                  var height = info["h"];


                  $('.mecha[value='+info["i"]+']').css('width', width);
                  $('.mecha[value='+info["i"]+']').css('height', height);
                  $('.mecha[value='+info["i"]+']').css('top', vy);
                  $('.mecha[value='+info["i"]+']').css('left', vx);


                  var m = getRotationDegrees(info["m"]);
                  //alert(info["m"]);

                  $('.mecha[value='+info["i"]+']').freetrans({
                  x: vx,
                  y: vy,
                  angle: m,

                  })

                  $('.mecha[value='+info["i"]+']').css('transform', info["m"]);

              },
              dataType:"json",
             });





        });
}

function getRotationDegrees(matrix) {

        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
    return angle;
}

$("#locales").change(function() {

  <?php $loading = Url::to('res/cargando.gif')?>
  $('#plan-container').html("<img class='center-block' src='<?php echo $loading;?>' />");

  $("#aedit").css("display","block");
  $("#asave").css("display","none");

  var sel = $(this).val();

  var param = {"id" : sel};

           <?php $url = Url::to(['local/loadlocal']); ?>

           $.ajax({
            data:param,
                url:"<?php echo $url;?>",
                type:"get",
                success: function(info){

                $('#plan-container').html(info);
                  order();
              },

             });
});

$("#aedit").click(function(){
  var sel = $("#locales").val();
  if (sel > 0) {
    reorder();
    $("#asave").css("display","block");
    $("#aedit").css("display","none");
  }

  });

</script>

<!--==================SCRIPTS END===================-->
</body>
</html>
<?php $this->endPage() ?>
