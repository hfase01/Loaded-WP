<!doctype html>  

<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title><?php bloginfo('name'); ?><?php wp_title('-', true, 'left'); ?></title>
				
		<meta name="viewport" content="width=device-width; initial-scale=1.0">
		
		<!-- icons & favicons -->
		<!-- For iPhone 4 -->
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/h/apple-touch-icon.png">
		<!-- For iPad 1-->
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/m/apple-touch-icon.png">
		<!-- For iPhone 3G, iPod Touch and Android -->
		<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/l/apple-touch-icon-precomposed.png">
		<!-- For Nokia -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/l/apple-touch-icon.png">
		<!-- For everything else -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		
		<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>window.jQuery || document.write(unescape('%3Cscript src="<?php echo get_template_directory_uri(); ?>/library/js/libs/jquery-1.7.1.min.js"%3E%3C/script%3E'))</script>
		
		<script src="<?php echo get_template_directory_uri(); ?>/library/js/modernizr.full.min.js"></script>
		
		<!-- media-queries.js (fallback) -->
		<!--[if lt IE 9]>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>			
		<![endif]-->

		<!-- html5.js -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->
		
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css">
		
		<?php
			$theme_options_styles = '';
		
			$heading_typography = of_get_option('heading_typography');
			if ($heading_typography) {
				$theme_options_styles .= '
				h1, h2, h3, h4, h5, h6{ 
					font-family: ' . $heading_typography['face'] . '; 
					font-weight: ' . $heading_typography['style'] . '; 
					color: ' . $heading_typography['color'] . '; 
				}';
			}
			
			$main_body_typography = of_get_option('main_body_typography');
			if ($main_body_typography) {
				$theme_options_styles .= '
				body{ 
					font-family: ' . $main_body_typography['face'] . '; 
					font-weight: ' . $main_body_typography['style'] . '; 
					color: ' . $main_body_typography['color'] . '; 
				}';
			}
			
			$link_color = of_get_option('link_color');
			if ($link_color) {
				$theme_options_styles .= '
				a{ 
					color: ' . $link_color . '; 
				}';
			}
			
			$link_hover_color = of_get_option('link_hover_color');
			if ($link_hover_color) {
				$theme_options_styles .= '
				a:hover{ 
					color: ' . $link_hover_color . '; 
				}';
			}
			
			$link_active_color = of_get_option('link_active_color');
			if ($link_active_color) {
				$theme_options_styles .= '
				a:active{ 
					color: ' . $link_active_color . '; 
				}';
			}
			
			$topbar_position = of_get_option('nav_position');
			if ($topbar_position == 'scroll') {
				$theme_options_styles .= '
				.navbar{ 
					position: static; 
				}
				body{
					padding-top: 0;
				}
				'	
				;
			}
			
			$topbar_bg_color = of_get_option('top_nav_bg_color');
			if ($topbar_bg_color) {
				$theme_options_styles .= '
				.navbar-inner, .navbar .fill { 
					background-color: '. $topbar_bg_color . ';
				}';
			}
			
			$use_gradient = of_get_option('showhidden_gradient');
			if ($use_gradient) {
				$topbar_bottom_gradient_color = of_get_option('top_nav_bottom_gradient_color');
			
				$theme_options_styles .= '
				.navbar-inner, .navbar .fill {
					background-image: -khtml-gradient(linear, left top, left bottom, from(' . $topbar_bg_color . '), to('. $topbar_bottom_gradient_color . '));
					background-image: -moz-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
					background-image: -ms-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
					background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ' . $topbar_bg_color . '), color-stop(100%, '. $topbar_bottom_gradient_color . '));
					background-image: -webkit-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . '2);
					background-image: -o-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
					background-image: linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
					filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'' . $topbar_bg_color . '\', endColorstr=\''. $topbar_bottom_gradient_color . '2\', GradientType=0);
				}';
			}
			else{
				$theme_options_styles .= '.navbar-inner, .navbar .fill { background-image: none; };';
			}	
			
			$topbar_link_color = of_get_option('top_nav_link_color');
			if ($topbar_link_color) {
				$theme_options_styles .= '
				.navbar .nav li a { 
					color: '. $topbar_link_color . ';
				}';
			}
			
			$topbar_link_hover_color = of_get_option('top_nav_link_hover_color');
			if ($topbar_link_hover_color) {
				$theme_options_styles .= '
				.navbar .nav li a:hover { 
					color: '. $topbar_link_hover_color . ';
				}';
			}
			
			$topbar_dropdown_hover_bg_color = of_get_option('top_nav_dropdown_hover_bg');
			if ($topbar_dropdown_hover_bg_color) {
				$theme_options_styles .= '
					.dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover {
						background-color: ' . $topbar_dropdown_hover_bg_color . ';
					}
				';
			}
			
			$topbar_dropdown_item_color = of_get_option('top_nav_dropdown_item');
			if ($topbar_dropdown_item_color){
				$theme_options_styles .= '
					.dropdown-menu a{
						color: ' . $topbar_dropdown_item_color . ' !important;
					}
				';
			}
			
			$hero_unit_bg_color = of_get_option('hero_unit_bg_color');
			if ($hero_unit_bg_color) {
				$theme_options_styles .= '
				.hero-unit { 
					background-color: '. $hero_unit_bg_color . ';
				}';
			}
			
			$suppress_comments_message = of_get_option('suppress_comments_message');
			if ($suppress_comments_message){
				$theme_options_styles .= '
				#main article {
					border-bottom: none;
				}';
			}
			
			$additional_css = of_get_option('wpbs_css');
			if( $additional_css ){
				$theme_options_styles .= $additional_css;
			}
					
			if($theme_options_styles){
				echo '<style>' 
				. $theme_options_styles . '
				</style>';
			}
		
			$bootstrap_theme = of_get_option('wpbs_theme');
			$use_theme = of_get_option('showhidden_themes');
			
			if( $bootstrap_theme && $use_theme ){
				if( $bootstrap_theme == 'default' ){
		?>
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css">
		<?php
				}
				else {
		?>
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/admin/themes/<?php echo $bootstrap_theme; ?>.css">
		<?php
				}
			}
		?>
		
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
		
		<?php 

			// check wp user level
			get_currentuserinfo(); 
			// store to use later
			global $user_level; 
		
		?>
		<script>
 
// animation globals
var t=0; 
var frameInterval = 25; // in ms
var canvas = null; // canvas DOM object
var context = null; // canvas context
 
// ball globals
var ballRadius = 10;
 
// physics globals
var collisionDamper = 0.15;
var floorFriction = 0.0029 * frameInterval;
var mouseForceMultiplier = 0.55 * frameInterval;
var restoreForce =0.0028 * frameInterval;
 
var mouseX = 99999;
var mouseY = 99999;
 
var balls = null;
 
function Ball(x,y,vx,vy,color) {
	this.x=x;
	this.y=y;
	this.vx=vx;
	this.vy=vy;
	this.color=color;
 
	this.origX = x;
	this.origY = y;
}
 
function init() {
	canvas=document.getElementById("myCanvas");
	context=canvas.getContext("2d");
	initStageObjects();
	setInterval(updateStage, frameInterval);
}
 
function updateStage() {
	t+=frameInterval; 
	clearCanvas();
	updateStageObjects();
	drawStageObjects();	
}
 
function initStageObjects() {
	balls = new Array();
 
	var green = "#3A5BCD";
	var black = "#000000";
	var red = "#FF0000";
	
	balls.push(new Ball(100,20,0,0,black));
	balls.push(new Ball(100,25,0,0,black));
	balls.push(new Ball(100,30,0,0,black));
	balls.push(new Ball(100,35,0,0,black));
	balls.push(new Ball(100,40,0,0,black));
	balls.push(new Ball(100,45,0,0,black));
	balls.push(new Ball(100,50,0,0,black));
	balls.push(new Ball(100,55,0,0,black));
	balls.push(new Ball(100,60,0,0,black));
	balls.push(new Ball(100,65,0,0,black));
	balls.push(new Ball(100,70,0,0,black));
	balls.push(new Ball(100,75,0,0,black));
	balls.push(new Ball(100,80,0,0,black));
	balls.push(new Ball(100,85,0,0,black));
	balls.push(new Ball(100,90,0,0,black));
	balls.push(new Ball(100,95,0,0,black));
	balls.push(new Ball(100,100,0,0,black));
	balls.push(new Ball(100,105,0,0,black));
	balls.push(new Ball(100,110,0,0,black));
	balls.push(new Ball(100,115,0,0,black));
	balls.push(new Ball(100,120,0,0,black));
	balls.push(new Ball(100,125,0,0,black));
	balls.push(new Ball(100,130,0,0,black));
	balls.push(new Ball(100,135,0,0,black));
	balls.push(new Ball(100,140,0,0,black));
	balls.push(new Ball(100,145,0,0,black));
	balls.push(new Ball(100,150,0,0,black));
	balls.push(new Ball(100,155,0,0,black));
	balls.push(new Ball(150,20,0,0,black));
	balls.push(new Ball(150,25,0,0,black));
	balls.push(new Ball(150,30,0,0,black));
	balls.push(new Ball(150,35,0,0,black));
	balls.push(new Ball(150,40,0,0,black));
	balls.push(new Ball(150,45,0,0,black));
	balls.push(new Ball(150,50,0,0,black));
	balls.push(new Ball(150,55,0,0,black));
	balls.push(new Ball(150,60,0,0,black));
	balls.push(new Ball(150,65,0,0,black));
	balls.push(new Ball(150,70,0,0,black));
	balls.push(new Ball(150,75,0,0,black));
	balls.push(new Ball(150,80,0,0,black));
	balls.push(new Ball(150,85,0,0,black));
	balls.push(new Ball(150,90,0,0,black));
	balls.push(new Ball(150,95,0,0,black));
	balls.push(new Ball(150,100,0,0,black));
	balls.push(new Ball(150,105,0,0,black));
	balls.push(new Ball(150,110,0,0,black));
	balls.push(new Ball(150,115,0,0,black));
	balls.push(new Ball(150,120,0,0,black));
	balls.push(new Ball(150,125,0,0,black));
	balls.push(new Ball(150,130,0,0,black));
	balls.push(new Ball(150,135,0,0,black));
	balls.push(new Ball(150,140,0,0,black));
	balls.push(new Ball(150,145,0,0,black));
	balls.push(new Ball(150,150,0,0,black));
	balls.push(new Ball(150,155,0,0,black));
	balls.push(new Ball(100,100,0,0,black));
	balls.push(new Ball(105,100,0,0,black));
	balls.push(new Ball(110,100,0,0,black));
	balls.push(new Ball(115,100,0,0,black));
	balls.push(new Ball(120,100,0,0,black));
	balls.push(new Ball(125,100,0,0,black));
	balls.push(new Ball(130,100,0,0,black));
	balls.push(new Ball(135,100,0,0,black));
	balls.push(new Ball(140,100,0,0,black));
	balls.push(new Ball(145,100,0,0,black));	
	balls.push(new Ball(150,100,0,0,black));
	balls.push(new Ball(155,100,0,0,black));
	balls.push(new Ball(160,100,0,0,black));
	balls.push(new Ball(165,100,0,0,black));
	balls.push(new Ball(170,100,0,0,black));
	balls.push(new Ball(175,100,0,0,black));
	balls.push(new Ball(180,100,0,0,black));
	balls.push(new Ball(185,100,0,0,black));
	balls.push(new Ball(190,100,0,0,black));
	balls.push(new Ball(150,20,0,0,black));
	balls.push(new Ball(155,20,0,0,black));
	balls.push(new Ball(160,20,0,0,black));
	balls.push(new Ball(165,20,0,0,black));
	balls.push(new Ball(170,20,0,0,black));
	balls.push(new Ball(175,20,0,0,black));
	balls.push(new Ball(180,20,0,0,black));
	balls.push(new Ball(185,20,0,0,black));
	balls.push(new Ball(190,20,0,0,black));
	balls.push(new Ball(195,20,0,0,black));
	balls.push(new Ball(200,20,0,0,black));
	balls.push(new Ball(205,20,0,0,black));
	balls.push(new Ball(210,20,0,0,black));
	balls.push(new Ball(215,20,0,0,black));	
	balls.push(new Ball(180,60,0,0,black));
	balls.push(new Ball(180,65,0,0,green));
	balls.push(new Ball(180,70,0,0,green));
	balls.push(new Ball(180,75,0,0,green));
	balls.push(new Ball(180,80,0,0,green));
	balls.push(new Ball(180,85,0,0,green));
	balls.push(new Ball(180,90,0,0,green));
	balls.push(new Ball(180,95,0,0,green));
	balls.push(new Ball(180,100,0,0,green));
	balls.push(new Ball(180,105,0,0,green));
	balls.push(new Ball(180,110,0,0,green));
	balls.push(new Ball(180,115,0,0,green));
	balls.push(new Ball(180,120,0,0,green));
	balls.push(new Ball(180,125,0,0,green));
	balls.push(new Ball(180,130,0,0,green));
	balls.push(new Ball(180,135,0,0,green));
	balls.push(new Ball(180,140,0,0,green));
	balls.push(new Ball(180,145,0,0,green));
	balls.push(new Ball(180,150,0,0,green));
	balls.push(new Ball(180,155,0,0,green));	
	balls.push(new Ball(180,60,0,0,green));
	balls.push(new Ball(185,60,0,0,green));
	balls.push(new Ball(190,60,0,0,green));
	balls.push(new Ball(195,60,0,0,green));
	balls.push(new Ball(200,60,0,0,green));
	balls.push(new Ball(205,60,0,0,green));
	balls.push(new Ball(210,60,0,0,green));
	balls.push(new Ball(215,60,0,0,green));
	balls.push(new Ball(220,60,0,0,green));	
	balls.push(new Ball(225,60,0,0,green));
	balls.push(new Ball(225,65,0,0,green));
	balls.push(new Ball(225,70,0,0,green));
	balls.push(new Ball(225,75,0,0,green));
	balls.push(new Ball(225,80,0,0,green));
	balls.push(new Ball(225,85,0,0,green));
	balls.push(new Ball(225,90,0,0,green));
	balls.push(new Ball(225,95,0,0,green));
	balls.push(new Ball(225,100,0,0,green));	
	balls.push(new Ball(225,105,0,0,green));
	balls.push(new Ball(225,110,0,0,green));
	balls.push(new Ball(225,115,0,0,green));
	balls.push(new Ball(225,120,0,0,green));
	balls.push(new Ball(225,125,0,0,green));
	balls.push(new Ball(225,130,0,0,green));
	balls.push(new Ball(225,135,0,0,green));
	balls.push(new Ball(225,140,0,0,green));
	balls.push(new Ball(225,145,0,0,green));
	balls.push(new Ball(225,150,0,0,green));
	balls.push(new Ball(225,155,0,0,green));
	balls.push(new Ball(255,60,0,0,green));
	balls.push(new Ball(260,60,0,0,green));
	balls.push(new Ball(265,60,0,0,green));
	balls.push(new Ball(270,60,0,0,green));
	balls.push(new Ball(275,60,0,0,green));
	balls.push(new Ball(280,60,0,0,green));
	balls.push(new Ball(285,60,0,0,green));
	balls.push(new Ball(290,60,0,0,green));
	balls.push(new Ball(255,60,0,0,green));
	balls.push(new Ball(255,65,0,0,green));
	balls.push(new Ball(255,70,0,0,green));
	balls.push(new Ball(255,75,0,0,green));
	balls.push(new Ball(255,80,0,0,green));
	balls.push(new Ball(255,85,0,0,green));
	balls.push(new Ball(255,90,0,0,green));
	balls.push(new Ball(255,95,0,0,green));
	balls.push(new Ball(255,100,0,0,green));	
	balls.push(new Ball(255,105,0,0,green));
	balls.push(new Ball(260,105,0,0,green));
	balls.push(new Ball(265,105,0,0,green));
	balls.push(new Ball(270,105,0,0,green));
	balls.push(new Ball(275,105,0,0,green));
	balls.push(new Ball(280,105,0,0,green));
	balls.push(new Ball(285,105,0,0,green));
	balls.push(new Ball(290,105,0,0,green));
	balls.push(new Ball(290,110,0,0,green));
	balls.push(new Ball(290,115,0,0,green));
	balls.push(new Ball(290,120,0,0,green));
	balls.push(new Ball(290,125,0,0,green));
	balls.push(new Ball(290,130,0,0,green));
	balls.push(new Ball(290,135,0,0,green));
	balls.push(new Ball(290,140,0,0,green));
	balls.push(new Ball(290,145,0,0,green));
	balls.push(new Ball(290,150,0,0,green));
	balls.push(new Ball(290,155,0,0,green));
	balls.push(new Ball(290,155,0,0,green));
	balls.push(new Ball(255,155,0,0,green));
	balls.push(new Ball(260,155,0,0,green));
	balls.push(new Ball(265,155,0,0,green));
	balls.push(new Ball(270,155,0,0,green));
	balls.push(new Ball(275,155,0,0,green));
	balls.push(new Ball(280,155,0,0,green));
	balls.push(new Ball(285,155,0,0,green));
	balls.push(new Ball(290,155,0,0,green));
	balls.push(new Ball(320,60,0,0,green));
	balls.push(new Ball(325,60,0,0,green));
	balls.push(new Ball(330,60,0,0,green));
	balls.push(new Ball(335,60,0,0,green));
	balls.push(new Ball(340,60,0,0,green));
	balls.push(new Ball(345,60,0,0,green));
	balls.push(new Ball(350,60,0,0,green));
	balls.push(new Ball(355,60,0,0,green));
	balls.push(new Ball(320,65,0,0,green));
	balls.push(new Ball(320,70,0,0,green));
	balls.push(new Ball(320,75,0,0,green));
	balls.push(new Ball(320,80,0,0,green));
	balls.push(new Ball(320,85,0,0,green));
	balls.push(new Ball(320,90,0,0,green));
	balls.push(new Ball(320,95,0,0,green));
	balls.push(new Ball(320,100,0,0,green));
	balls.push(new Ball(320,105,0,0,green));
	balls.push(new Ball(320,110,0,0,green));
	balls.push(new Ball(320,115,0,0,green));
	balls.push(new Ball(320,120,0,0,green));
	balls.push(new Ball(320,125,0,0,green));
	balls.push(new Ball(320,130,0,0,green));
	balls.push(new Ball(320,135,0,0,green));
	balls.push(new Ball(320,140,0,0,green));
	balls.push(new Ball(320,145,0,0,green));
	balls.push(new Ball(320,150,0,0,green));
	balls.push(new Ball(320,155,0,0,green));
	balls.push(new Ball(320,100,0,0,green));
	balls.push(new Ball(325,100,0,0,green));
	balls.push(new Ball(330,100,0,0,green));
	balls.push(new Ball(335,100,0,0,green));
	balls.push(new Ball(340,100,0,0,green));
	balls.push(new Ball(345,100,0,0,green));
	balls.push(new Ball(350,100,0,0,green));
	balls.push(new Ball(355,100,0,0,green));
	balls.push(new Ball(320,155,0,0,green));
	balls.push(new Ball(325,155,0,0,green));
	balls.push(new Ball(330,155,0,0,green));
	balls.push(new Ball(335,155,0,0,green));
	balls.push(new Ball(340,155,0,0,green));
	balls.push(new Ball(345,155,0,0,green));
	balls.push(new Ball(350,155,0,0,green));
	balls.push(new Ball(355,155,0,0,green));
	balls.push(new Ball(195,100,0,0,red));
}
 
function drawStageObjects() {
	for (var n=0; n<balls.length; n++) {	
		context.beginPath();
		context.arc(balls[n].x,balls[n].y,ballRadius,
			0,2*Math.PI,false);
		context.fillStyle=balls[n].color;
		context.fill();20
	}
 
 
}
 
function updateStageObjects() {
 
	for (var n=0; n<balls.length; n++) {
 
		// set ball position based on velocity
		balls[n].y+=balls[n].vy;
		balls[n].x+=balls[n].vx;
 
		// restore forces
 
 
 
		if (balls[n].x > balls[n].origX) {
			balls[n].vx -= restoreForce;
		}
		else {
			balls[n].vx += restoreForce;
		}
		if (balls[n].y > balls[n].origY) {
			balls[n].vy -= restoreForce;
		}
		else {
			balls[n].vy += restoreForce;
		}
 
 
 
		// mouse forces
		var distX = balls[n].x - mouseX;
		var distY = balls[n].y - mouseY;
 
		var radius = Math.sqrt(Math.pow(distX,2) + 
			Math.pow(distY,2));
 
		var totalDist = Math.abs(distX) + Math.abs(distY);
 
		var forceX = (Math.abs(distX) / totalDist) * 
			(1/radius) * mouseForceMultiplier;
		var forceY = (Math.abs(distY) / totalDist) * 
			(1/radius) * mouseForceMultiplier;
 
		if (distX>0) { // mouse is left of ball
			balls[n].vx += forceX;
		}
		else {
			balls[n].vx -= forceX;
		}
		if (distY>0) { // mouse is on top of ball
			balls[n].vy += forceY;
		}
		else {
			balls[n].vy -= forceY;
		}
 
 
		// floor friction
		if (balls[n].vx>0) {
			balls[n].vx-=floorFriction;
		}
		else if (balls[n].vx<0) {
			balls[n].vx+=floorFriction;
		}
		if (balls[n].vy>0) {
			balls[n].vy-=floorFriction;
		}
		else if (balls[n].vy<0) {
			balls[n].vy+=floorFriction;
		}
 
		// floor condition
		if (balls[n].y > (canvas.height-ballRadius)) {
			balls[n].y=canvas.height-ballRadius-2;
			balls[n].vy*=-1; 
			balls[n].vy*=(1-collisionDamper);
		}
 
		// ceiling condition
		if (balls[n].y < (ballRadius)) {
			balls[n].y=ballRadius+2;
			balls[n].vy*=-1; 
			balls[n].vy*=(1-collisionDamper);
		}
 
		// right wall condition
		if (balls[n].x > (canvas.width-ballRadius)) {
			balls[n].x=canvas.width-ballRadius-2;
			balls[n].vx*=-1;
			balls[n].vx*=(1-collisionDamper);
		}
 
		// left wall condition
		if (balls[n].x < (ballRadius)) {
			balls[n].x=ballRadius+2;
			balls[n].vx*=-1;
			balls[n].vx*=(1-collisionDamper);
		}	
	}
}
 
function clearCanvas() {
	context.clearRect(0,0,canvas.width, canvas.height);
}
 
function handleMouseMove(evt) {
	mouseX = evt.clientX - canvas.offsetLeft;
	mouseY = evt.clientY - canvas.offsetTop;	
}
 
function handleMouseOut() {
	mouseX = 99999;
	mouseY = 99999;
}
 
</script>		
	</head>
	
	<body onload="init()" <?php body_class(); ?>>
				
		<header role="banner">
		
			<div id="inner-header" class="clearfix">
				
				<div class="navbar navbar-fixed-top">
					<div class="navbar-inner">
						<div class="container-fluid nav-container">
							<nav role="navigation">
								<a class="brand" id="logo" title="<?php echo get_bloginfo('description'); ?>" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
								
								<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							        <span class="icon-bar"></span>
							        <span class="icon-bar"></span>
							        <span class="icon-bar"></span>
								</a>
								
								<div class="nav-collapse">
									<?php bones_main_nav(); // Adjust using Menus in Wordpress Admin ?>
								</div>
								
							</nav>
							
							<?php if(of_get_option('search_bar', '1')) {?>
							<form class="navbar-search pull-right" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
								<input name="s" id="s" type="text" class="search-query" placeholder="<?php _e('Search','bonestheme'); ?>">
							</form>
							<?php } ?>
							
						</div>
					</div>
				</div>
			
			</div> <!-- end #inner-header -->
		
		</header> <!-- end header -->
		
		<div class="container-fluid">
