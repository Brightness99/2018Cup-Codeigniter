<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Bienvenida</title>
	<script
  	src="https://code.jquery.com/jquery-3.3.1.js"
 	integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  	crossorigin="anonymous"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="imageSlider qweDSlider">
	<div class="main-img-block">
		<div class="left-menu-block">
			<div class="profile-block">
				<?php if($userDetails->imagen_perfil != ''){ ?>
					<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>" alt="profileMenu">
				<?php }else{ ?>
					<img src="<?php echo base_url(); ?>assets/img/profile-img.png" alt="profile">
				<?php } ?>
			</div>
			<div class="menu-open">
				<img src="<?php echo base_url(); ?>assets/img/menu-img.png" alt="menu-img">
				<p>Menú</p>
			</div>
			<div class="wrapper-menu-logo">
				<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="menu-logo">
			</div>
		</div>
		<?php if(count($companyDetails->pc_slider) > 0){ ?>
			<div class="image-slider-block">
				<div class="image-inner-slider">
					<?php foreach($companyDetails->pc_slider as $image){ ?>
						<div class="image-inner-wrapper" style="background:url('<?php echo $image; ?>'); "></div>
					<?php } ?>
				</div>
			</div>
		<?php }else{ ?>
			<div class="image-slider-block">
				<div class="image-inner-slider">
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.png'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.png'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.png'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.png'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.png'); "></div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="pronosticos-small-block">
		<div class="mobile-menu-pronosticos">
			<div class="left-side-mobile">
				<img src="<?php echo base_url(); ?>assets/img/mobileBurger.png" alt="mobileBurger">
				<p>Menú</p>
			</div>
			<div class="right-side-mobile">
				<p>Hola , <?php echo $userDetails->nombre; ?></p>
				<?php if($userDetails->imagen_perfil != ''){ ?>
					<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>" alt="profileMenu">
				<?php }else{ ?>
					<img src="<?php echo base_url(); ?>assets/img/profileMenu.png" alt="profileMenu">
				<?php } ?>
			</div>
		</div>
		<?php if(count($companyDetails->mobile_slider) > 0){ ?>
			<div class="small-image-slider">
				<?php foreach($companyDetails->mobile_slider as $image){ ?>
					<div class="small-image-inner" style="background:url('<?php echo $image; ?>'); "></div>
				<?php } ?>
			</div>
		<?php }else{ ?>
			<div class="small-image-slider">
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
			</div>
		<?php } ?>
	</div>
	
	<div class="menu-delta menu-big-delta">
		<div class="header-part">
			<?php if($userDetails->imagen_perfil != ''){ ?>
				<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>" alt="profileMenu">
			<?php }else{ ?>
				<img src="<?php echo base_url(); ?>assets/img/profileMenu.png" alt="profileMenu">
			<?php } ?>
			<p><?php echo $userDetails->nombre.' '.$userDetails->apellido; ?></p>
		</div>
		<div class="main-part">
			<ul>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/edit-profile'; ?>">Perfil</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/pronosticos/fase'; ?>">Pronósticos</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/ranking'; ?>">Ranking</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/premios'; ?>">Premios</a></li>
				<?php if($_SESSION['company']->is_trivia == 1){ ?>
					<li><a href="<?php echo base_url().$_SESSION['company']->url.'/trivias'; ?>">Trivias</a></li>
				<?php } ?>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/bases-y-condiciones'; ?>">Bases y Condiciones</a></li>
			</ul>
		</div>
		<div class="logo-part">
			<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="left-football-logo">
			<a href="<?php echo base_url().$_SESSION['company']->url.'/logout'; ?>">Cerrar Sesion</a>
		</div>
	</div>

	<div class="menu-overlay menuSLider"></div>
	
	<div class="menu-delta menu-small-delta">
		<div class="header-part">
			<?php if($userDetails->imagen_perfil != ''){ ?>
				<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>" alt="profileMenu">
			<?php }else{ ?>
				<img src="<?php echo base_url(); ?>assets/img/profileMenu.png" alt="profileMenu">
			<?php } ?>
			<p><?php echo $userDetails->nombre.' '.$userDetails->apellido; ?></p>
		</div>
		<div class="main-part">
			<ul>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/edit-profile'; ?>">Perfil</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/pronosticos/fase'; ?>">Pronósticos</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/ranking'; ?>">Ranking</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/premios'; ?>">Premios</a></li>
				<?php if($_SESSION['company']->is_trivia == 1){ ?>
					<li><a href="<?php echo base_url().$_SESSION['company']->url.'/trivias'; ?>">Trivias</a></li>
				<?php } ?>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/bases-y-condiciones'; ?>">Bases y Condiciones</a></li>
			</ul>
			<div class="wrapper-session">
				<a class="session" href="<?php echo base_url().$_SESSION['company']->url.'/logout'; ?>">Cerrar Sesion</a>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
</body>
</html>