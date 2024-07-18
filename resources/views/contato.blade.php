@extends('adminlte::page')

@section('title', 'Contato')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Área de Contato</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Contato</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<x-adminlte-card theme="navy" theme-mode="outline">
    <div class="row">
        <div class="col-md-5">
            <div class="wow fadeInDown delay-03s" style="margin-top: 20px;">
                <div class="row d-flex align-items-center justify-content-center"> 
                    <div class="text-center logo-contato">
                        <img src="{{ asset('img/sistema/FusionTechLogo1.png') }}" class="rounded" alt="..." style="max-width:40%;  width: auto; height: auto;">
                    </div>
                </div>
            <div class="row d-flex align-items-center justify-content-center" style="margin-top: 20px;">
            <div class="wow fadeInLeft delay-05s">
            	<div class="contact-info-box address clearfix">
                	<h3><i class="fa-solid fa-location-dot"></i></i>Endereço:</h3>
                	<span>Rua Daércio Quero Robles, 120<br>CEP: 13876-341<br>Jardim Almeida<br>São João da Boa Vista, São Paulo</span>
                </div>
                <div class="contact-info-box phone clearfix">
                	<h3><i class="fa-solid fa-phone"></i>Telefone:</h3>
                	<span>(19) 9 9901-9111 / (19) 9 9230-4154</span>
                </div>
                <div class="contact-info-box email clearfix">
                	<h3><i class="fa-solid fa-envelope"></i></i>Email:</h3>
                	<span>atendimentofusiontech.com@fusiontech.com</span>
                </div>
            	<div class="contact-info-box hours clearfix">
                	<h3><i class="fa-solid fa-clock"></i></i>Horário Atendimento:</h3>
                	<span><strong>Segunda à Sexta: </strong> 09:00 - 18:00<br><strong>Sábado: </strong> 09:00 - 13:00<br><strong>Domingo e Feriados: </strong>Fechado</span>
                </div>
                <div class="wow fadeInLeft delay-06s">
                <ul class="social-link d-flex align-items-center justify-content-center">
                	<li class="twitter"><a href="#"><i class="fa-brands fa-x-twitter"></i></i></a></li>
                    <li class="facebook"><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li class="youtube"><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                    <li class="instagram"><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                    <li class="whatsapp"><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                    <li class="site"><a href="#"><i class="fa-solid fa-globe"></i></a></li>
                </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-7 d-flex align-items-center justify-content-center">
    <div class="row">
        <form class="row col-md-12" method="post" action="{{}}" id="formulario-contato" novalidate="novalidate">
        @csrf
        <div class="wow fadeInUp delay-07s col-md-12">
            <!-- Código -->
            <x-adminlte-input name="nome" label="Nome" type="text" placeholder="Informe seu nome" label-class="text-lightblue" fgroup-class="col-md-12">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-user text-lightblue"></i>
                    </div>
                </x-slot>
            </x-adminlte-input></div>

            <div class="wow fadeInUp delay-08s col-md-12">
            <x-adminlte-input name="email" label="Email" type="email" placeholder="email@exemplo.com" label-class="text-lightblue" fgroup-class="col-md-12">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </x-slot>
            </x-adminlte-input></div>

            <div class="wow fadeInUp delay-09s col-md-12">
            <x-adminlte-input name="assunto" label="Assunto" type="text" placeholder="Informe o assunto" label-class="text-lightblue" fgroup-class="col-md-12">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fa-solid fa-list-ul"></i>
                    </div>
                </x-slot>
            </x-adminlte-input></div>

            <div class="wow fadeInUp delay-10s col-md-12">
                <x-adminlte-textarea name="msg" label="Menssagem" rows=5 igroup-size="sm" label-class="text-primary" placeholder="Escreva sua menssagem..." fgroup-class="col-md-12" disable-feedback>
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-lg fa-comment-dots text-primary"></i>
                        </div>
                    </x-slot>
                </x-adminlte-textarea>
            </div>

            <div class="wow fadeInUp delay-11s col-md-12">
            <x-adminlte-button class="btn-flat" type="submit" label="Enviar" theme="info" icon="fa-solid fa-paper-plane"/></div>
        </form>
    </div>
</div>
</x-adminlte-card>
@stop

@section('css')
<style>
    /* Contact
---------------------------------*/
.main-section.contact{
	padding:90px 0 100px;
}

.main-section.contact{
	background:url(../img/bg-map.png) left 190px no-repeat;
}
.contact-info-box{
	font-size:15px;
	margin:0 0 14px 68px;
	padding-left:0;
}
.contact-info-box h3{
	font-size: 15px;
	font-weight:400;
	float:left;
	width:102px;
	margin-right:12px;
	line-height:28px;
}
.contact-info-box h3 i{
	font-style:normal;
	font-size:18px;
	color:#222222;
	font-family: 'FontAwesome';
	font-weight:normal;
	margin-right:7px;
}
.contact-info-box span{
	line-height:28px;
	display:block;
	overflow:hidden;
}
.social-link{
	/*padding:35px 0;
	margin:0 0 0 68px;*/
	display:block;
	overflow:hidden;
	list-style:none;
    height: 100px;
}
.social-link li{
	float:left;
	margin-right:8px;
}
.social-link li a{
	display:block;
	width:50px;
	height:50px;
	text-align:center;
	line-height:50px;
	font-size:25px;
	color:#fff;
	background:#222222;
	border-radius:50%;
	transition:all 0.3s ease-in-out;
}
.social-link li a:hover, .social-link li a:focus{
	text-decoration:none;
}
.twitter a:hover {
	background: #55acee;
    transform: scale(1.2);
}
.facebook a:hover {
	background: #3b5998;
    transform: scale(1.2);
}
.whatsapp a:hover {
	background: #45c355;
    transform: scale(1.2);
}
.instagram a:hover {
	background: linear-gradient(
        45deg,
        #f09433 0%,
        #e6683c 25%,
        #dc2743 50%,
        #cc2366 75%,
        #bc1888 100%
      );
    transform: scale(1.2);
}
.youtube a:hover {
	background: #ff0000;
    transform: scale(1.2);
}
.site a:hover {
	background: #0000ff;
    transform: scale(1.2);
}

.form{
	margin:0 66px 0 30px;
}
.input-text{
	padding:15px 16px;
	border:1px solid #ccc;
	width:100%;
	height:50px;
	display:block;
	border-radius:4px;
	font-size:15px;
	color:#aaa;
	font-family: 'Open Sans', sans-serif;
	margin:0 0 15px 0;
	transition:all 0.3s ease-in-out;
	-moz-transition:all 0.3s ease-in-out;
	-webkit-transition:all 0.3s ease-in-out;
}
.input-text:focus {
	border: 1px solid #7cc576;
	outline:0;
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(124, 197, 118, 0.3);
	-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(124, 197, 118, 0.3);	
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(124, 197, 118, 0.3);
}

.input-text.text-area{
	height:165px;
	resize:none;
	overflow:auto;
}
.input-btn{
	width:175px;
	height:50px;
	background:#7cc576;
	border-radius:4px;
	color:#ffffff;
	font-size:14px;
	text-transform:uppercase;
	font-family: 'Montserrat', sans-serif;
	font-weight:400;
	border:0px;
	transition:all 0.3s ease-in-out;
	-moz-transition:all 0.3s ease-in-out;
	-webkit-transition:all 0.3s ease-in-out;
}

.input-btn:hover{
	background: #111;
	color: #fff;
}

/* Clear Floated Elements
---------------------------------*/

.clearfix:before,
.clearfix:after {
  content: '\0020';
  display: block;
  overflow: hidden;
  visibility: hidden;
  width: 0;
  height: 0;
}

.clearfix:after {
  clear: both;
}

/* Animation Timers
---------------------------------*/
.delay-02s { 
	animation-delay: 0.2s; 
	-webkit-animation-delay: 0.2s; 
}
.delay-03s { 
	animation-delay: 0.3s; 
	-webkit-animation-delay: 0.3s; 
}
.delay-04s { 
	animation-delay: 0.4s; 
	-webkit-animation-delay: 0.4s; 
}

.delay-05s { 
	animation-delay: 0.5s; 
	-webkit-animation-delay: 0.5s; 
}
.delay-06s { 
	animation-delay: 0.6s; 
	-webkit-animation-delay: 0.6s; 
}

.delay-07s { 
	animation-delay: 0.7s; 
	-webkit-animation-delay: 0.7s; 
}
.delay-08s { 
	animation-delay: 0.8s; 
	-webkit-animation-delay: 0.8s; 
}

.delay-09s { 
	animation-delay: 0.9s; 
	-webkit-animation-delay: 0.9s; 
}
.delay-1s { 
	animation-delay: 1s; 
	-webkit-animation-delay: 1s; 
}
.delay-12s { 
	animation-delay: 1.2s; 
	-webkit-animation-delay: 1.2s; 
}
.delay-10s { 
	animation-delay: 1.0s; 
	-webkit-animation-delay: 1.0s; 
}

.delay-11s { 
	animation-delay: 1.1s; 
	-webkit-animation-delay: 1.1s; 
}
</style>
<link rel="stylesheet" href="{{ asset('html/contato/css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('html/contato/css/animate.css') }}">
@stop

@section('js')
<script src="{{asset('html/contato/js/wow.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(e) {
        $('#test').scrollToFixed();
        $('.res-nav_click').click(function(){
            $('.main-nav').slideToggle();
            return false    
            
        });
        
    });
</script>

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
@stop