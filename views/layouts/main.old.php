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
            <div><label><?php if(count($turno) != 0){echo $turno[0]["identificador"];}else{echo "Turno:?";}?></label></div>
            <div><b>HORARIO: </b><?php if(count($turno) != 0){echo substr($turno[0]["inicio"],0, -3);}else{echo "00:00:00";}?> - <?php if(count($turno) != 0){echo substr($turno[0]["fin"], 0, -3);}else{echo "00:00:00";}?></div>
        </div>

        <div class="col-md-4 col-xs-4 menu-block">
            <div><label>SELECCIONE MÁQUINA</label></div>
      <div>
          <a class="option-reference" href="<?= Url::toRoute('site/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_home.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('local/index')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_map.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('/maquina/charts')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_charts.png')?>"></a>
          <a class="option-reference" href="<?= Url::toRoute('/site/proccess')?>"><img class="img-menu-item" src="<?= Url::to('res/icon_proccess.png')?>"></a>
      </div>
        </div>

        <div class="col-md-4 col-xs-4 menu-block">
            <div><label>PRODUCCIÓN </label><sub> TURNO ACTUAL</sub></div>
            <div><b>PRODUCCIÓN ACTUAL:</b>  <?= Maquina::getTotalprodnall() ?> <img class="menu-img pull-right" src="<?= Url::to('res/eliminar_edicion.png')?>"></div>
            <div style="margin-top: 2%"><b>PRODUCCIÓN ESTIMADA:</b>  <?= Maquina::getTotalprodestnall() ?> <i class="pull-right" style="color:red"><?= Maquina::getTotalerrornall() ?></i></div>
        </div>


    </div>

  
  <a class="option-reference pull-right" style="position: absolute; width: 5%; right: -9%; text-align: center;"  href="<?= Url::toRoute('/user/profile')?>"><img class="img-responsive" src="<?= Url::to('res/icon_profile.png')?>">PERFIL</a>

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

        <div class="col-md-2 col-xs-2">

            <?php if($encendida){ ?>
                <label>MÁQUINAS ENCENDIDAS</label>
            <?php } ?>
            <?php foreach ($encendida as $machine) { ?>

                <div class="machine machine-ok">
                    <div class="machine-description">
                        <img class="pull-left machine-img" src="<?= Url::to('res/icon_yes.png')?>">
                        <label><?= strtoupper($machine->nombre) ?></label>
                        <p class="text-primary"><?= $machine->getTotalprodn() ?>/<?= $machine->getTotalprodestn() ?></p>
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

$('#plan-save').click(function(){

  var heightB = $('.plan-container').height();
  var widthB = $('.plan-container').width();

  var id = null;
  var posx = null;
  var posy = null;
  var width = null;
  var height = null;

  $('.mecha').freetrans('destroy');

    $('.mecha').each(function(key, element){
      id = $(this).attr('value');
      var b = $(this).siblings('div');
      posx = ($(this).position().left * 100) / widthB;
      posy = ($(this).position().top * 100) / heightB;
      width = ($(this).width() * 100) / widthB;
      height = ($(this).height() * 100) / heightB;
      matrix = $(this).css('transform');

      

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
           alert(info);    
        },  
       
       });

    });
   
 
 });


$(document).ready(function(){

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

                  var vx = (info["x"] * widthB) / 100;
                  var vy = (info["y"] * heightB) / 100;
                  var width = (info["w"] * widthB) / 100;
                  var height = (info["h"] * heightB) / 100;


                  $('.mecha[value='+info["i"]+']').css('width', width);
                  $('.mecha[value='+info["i"]+']').css('height', height);
                   //$('.mecha[value='+info["i"]+']').css('top', vy);
                  //$('.mecha[value='+info["i"]+']').css('left', vx); 
                  

                  var m = getRotationDegrees(info["m"]);
                  alert(info["m"]);

                  $('.mecha[value='+info["i"]+']').freetrans({
                  x: vx,
                  y: vy,
                  angle: m, 

                  })  
                  
                  //$('.mecha[value='+info["i"]+']').css('transform', info["m"]); 
              },  
              dataType:"json",
             });
        

             
              

        });
});

function getRotationDegrees(matrix) {
  
        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
    return angle;
}
</script>

<!--==================SCRIPTS END===================-->
</body>
</html>
<?php $this->endPage() ?>
