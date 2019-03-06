<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
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

<div>
   
	

		<div class="col-md-6 col-md-offset-3">
        	<?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        	]) ?>
        	<div style="text-align: -moz-center;text-align: -webkit-center">
        	<img class="img-responsive" alt="kyp-logo" src="<?php echo Url::to('res/icon_logo.png') ?>">
        	</div>
        	<?= $content ?>
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


    $('.mecha').each(function(key, element){
      id = $(this).attr('value');
      posx = ($(this).position().left * 100) / widthB;
      posy = ($(this).position().top * 100) / heightB;
      width = ($(this).width() * 100) / widthB;
      height = ($(this).height() * 100) / heightB;

      

      var param = {"id" :id,
              "posx" : posx,
              "posy" : posy,
              "width" : width,
              "height" : height,
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
</script>
<!--==================SCRIPTS END===================-->
</body>
</html>
<?php $this->endPage() ?>
