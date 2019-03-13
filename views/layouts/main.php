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

<div class="kyp-menu">

  <img id="menu-img-back" src="<?= Url::to('res/menu.png')?>">

	<div class="row menu-container nav-showed">

		<div class="col-md-4 col-xs-4 menu-block">
			<div><label><?php if(count($turno) != 0){echo $turno[0]["identificador"];}else{echo Yii::t('app','WORK SHIFT');}?></label></div>
			<div><b><?php echo Yii::t('app','WORKING HOURS') ?>: </b><?php if(count($turno) != 0){echo substr($turno[0]["inicio"],0, -3);}else{echo "00:00:00";}?> - <?php if(count($turno) != 0){echo substr($turno[0]["fin"], 0, -3);}else{echo "00:00:00";}?></div>
		</div>

		<div class="col-md-4 col-xs-4 menu-block">

      <div>
          <a class="option-reference" href="<?= Url::toRoute('site/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_home.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('local/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_map.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('/maquina/charts')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_charts.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('/site/proccess')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_proccess.png')?>"></a>
      </div>
		</div>

		<div class="col-md-4 col-xs-4 menu-block">
			<div><label><?php echo Yii::t('app','PRODUCTION') ?> </label><sub> <?php echo Yii::t('app','CURRENT SHIFT') ?></sub></div>
			<div><b><?php echo Yii::t('app','ESTIMATED PRODUCTION') ?>:</b>  <?= Maquina::getTotalprodnall() ?> <img class="menu-img pull-right" src="<?= Url::to('res/eliminar_edicion.png')?>"></div>
			<div style="margin-top: 2%"><b><?php echo Yii::t('app','PLANNED PRODUCTION') ?>:</b>  <?= Maquina::getTotalprodestnall() ?> <i class="pull-right" style="color:red"><?= Maquina::getTotalerrornall() ?></i></div>
		</div>


	</div>

	 <div class="pull-right" style="position: absolute;  right: -9%; top:10px">
	 <a href="#" >
       		<?php echo Html::beginForm(['/user/changepass'], 'post');
        	       echo Html::tag('i','',['class' => 'fa fa-key text-primary']);
        	       echo Html::submitButton(Yii::t('app','CHANGE PASS'), ['class' => 'btn btn-link']);
        	       echo Html::endForm();?>
       	</a>
	<a href="#">
       		<?php echo Html::beginForm(['/site/logout'], 'post');
        	       echo Html::tag('i','',['class' => 'fa fa-lock text-primary']);
        	       echo Html::submitButton(Yii::t('app','LOGOUT'), ['class' => 'btn btn-link']);
        	       echo Html::endForm();?>
       	</a>
	 </div>

</div>

<style>

</style>
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

		<div class="col-md-2 col-xs-2">

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


    	<div class="col-md-2 machine-container-right pull-right">

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

		<div class="container container-main col-md-8 col-xs-8">
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
