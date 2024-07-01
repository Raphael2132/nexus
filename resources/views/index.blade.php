<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Nexus - Gestão Empresarial</title>
        <link rel="icon" href="img/sistema/icone_nexus.png" type="image/png">

        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,800italic,700italic,600italic,400italic,300italic,800,700,600' rel='stylesheet' type='text/css'>

        <link href="html/index/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="html/index/css/style.css" rel="stylesheet" type="text/css">
        <link href="html/index/css/font-awesome.css" rel="stylesheet" type="text/css">
        <link href="html/index/css/responsive.css" rel="stylesheet" type="text/css">
        <link href="html/index/css/animate.css" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="html/index/js/jquery.1.8.3.min.js"></script>
        <script type="text/javascript" src="html/index/js/bootstrap.js"></script>
        <script type="text/javascript" src="html/index/js/jquery-scrolltofixed.js"></script>
        <script type="text/javascript" src="html/index/js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="html/index/js/jquery.isotope.js"></script>
        <script type="text/javascript" src="html/index/js/wow.js"></script>
        <script type="text/javascript" src="html/index/js/classie.js"></script>

        <!-- Styles -->
        <style>
        </style>
    </head>
<body style="overflow:hidden;">
<div style="overflow:hidden;">
<header class="header" id="header"><!--header-start-->
	<div class="container">
    	<figure class="logo animated fadeInDown delay-07s">
        	<a href="#"><img src="img/sistema/logo_nexus_c.png" alt=""></a>	
        </figure>	
        <h1 class="animated fadeInDown delay-07s">Bem Vindo ao Novo</h1>
        <ul class="we-create animated fadeInUp delay-1s">
        	<li>Seu novo conceito de gerenciamento de empresas</li>
        </ul>
            @auth
            <a class="link animated fadeInUp delay-1s" href="{{ url('/home') }}">Voltar a Home</a>
            @else
            <a class="link animated fadeInUp delay-1s" href="{{ route('login') }}">Acessar Sistema</a>
                @if (Route::has('register'))
                    <!-- não iremos cadastrar aqui<li><a href="{{ route('register') }}" >Cadastrar</a></li>-->
                @endif
            @endauth
            <a class="link animated fadeInUp delay-1s" href="#">Saiba Mais</a>
    </div>
</div>
</header><!--header-end-->




    <script type="text/javascript">
    $(document).ready(function(e) {
        $('#test').scrollToFixed();
        $('.res-nav_click').click(function(){
            $('.main-nav').slideToggle();
            return false    
            
        });
        
    });
</script>

    <!-- Trecho não utilizado que gera erro no console da pagina por causa do getElementById
    <script>
        wow = new WOW(
            {
            animateClass: 'animated',
            offset:       100
            }
        );
        wow.init();
        document.getElementById('').onclick = function() {
            var section = document.createElement('section');
            section.className = 'wow fadeInDown';
            this.parentNode.insertBefore(section, this);
        };
    </script>
    -->


<script type="text/javascript">
	$(window).load(function(){
		
		$('.main-nav li a').bind('click',function(event){
			var $anchor = $(this);
			
			$('html, body').stop().animate({
				scrollTop: $($anchor.attr('href')).offset().top - 102
			}, 1500,'easeInOutExpo');
			/*
			if you don't want to use the easing effects:
			$('html, body').stop().animate({
				scrollTop: $($anchor.attr('href')).offset().top
			}, 1000);
			*/
			event.preventDefault();
		});
	})
</script>

<script type="text/javascript">

$(window).load(function(){
  
  
  var $container = $('.portfolioContainer'),
      $body = $('body'),
      colW = 375,
      columns = null;

  
  $container.isotope({
    // disable window resizing
    resizable: true,
    masonry: {
      columnWidth: colW
    }
  });
  
  $(window).smartresize(function(){
    // check if columns has changed
    var currentColumns = Math.floor( ( $body.width() -30 ) / colW );
    if ( currentColumns !== columns ) {
      // set new column count
      columns = currentColumns;
      // apply width to container manually, then trigger relayout
      $container.width( columns * colW )
        .isotope('reLayout');
    }
    
  }).smartresize(); // trigger resize to set container width
  $('.portfolioFilter a').click(function(){
        $('.portfolioFilter .current').removeClass('current');
        $(this).addClass('current');
 
        var selector = $(this).attr('data-filter');
        $container.isotope({
			
            filter: selector,
         });
         return false;
    });
  
});

</script>
</body>
</html>
