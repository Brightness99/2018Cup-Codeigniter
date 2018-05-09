<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit Profile</title>
<script
  	src="https://code.jquery.com/jquery-3.3.1.js"
 	integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  	crossorigin="anonymous"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<style>
	.user-profile-round-sm{
		width: 45px;
		height: 45px;
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
		border-radius: 50%;
		margin: 0 auto;
	}
	
	.user-profile-round-lg{
		width: 100px;
		height: 100px;
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
		border-radius: 50%;
		margin: 0 auto;
	}
</style>

<body  class="recupPAge">
	<div class="wrapper-pronosticos-block">
		<div class="left-menu-block">
			<div class="profile-block">
				<div class="user-profile-round-sm" style="background-image: url(<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>);">
					
				</div>
			</div>
			<div class="menu-open">
				<img src="<?php echo base_url(); ?>assets/img/menu-img.png" alt="menu-img">
				<p>Menú</p>
			</div>
			<div class="wrapper-menu-logo">
				<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="menu-logo">
			</div>
		</div>

		<div class="pronosticos-block">
			<div class="hint-wrapper">
				<div class="topHint">
					<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>">
					<p> <?php echo $companyDetails->descripcion; ?> </p>
				</div>
			</div>
			
			<h2 class="recupepar">Cambiar mis datos de perfil</h2>
			<div class="wrapper-recupaper-form">
				<div class="inner-recupaper-form">
					<form id="edit-profile-frm" action="<?php echo base_url().$_SESSION['company']->url; ?>/change-profile" method="post" enctype="multipart/form-data">
						
						<?php
						if($this->session->flashdata('item')) {
							$message = $this->session->flashdata('item');
						?>
						<div class="group-form">
							<h4 style="color: green;"><?php echo $message; ?></h4>
						</div>
						
						<?php
						}
						?>
						<div class="group-form">
							<h4>Cambiar mi imagen de perfil</h4>
							<input type="file" name="user_image">
						</div>
						<div class="group-form">
							<h4>Nueva Contraseña</h4>
							<input type="text" name="password" id="password">
						</div>
						<div class="group-form lastRecup">
							<h4>Repetí la nueva contraseña</h4>
							<input type="text" id="confirm-password">
						</div>
						<div class="group-wrapp-form">
							<button type="button" id="edit-profile-btn">Editar mi perfil</button>
						</div>
						<div class="err-class"></div>
					</form>
				</div>
			</div>
			
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="condiciones-small">
			<div class="mobile-menu-pronosticos">
				<div class="left-side-mobile">
					<img src="<?php echo base_url();  ?>assets/img/mobileBurger.png" alt="mobileBurger">
					<p>Menú</p>
				</div>
				<div class="right-side-mobile">
					<p>Hola , <?php echo $userDetails->nombre; ?></p><img src="<?php echo base_url();  ?>assets/img/mobileProf.png" alt="">
				</div>
			</div>
			<div class="small-INN">
				<h2>Olvido Contraseña</h2>
				<form id="edit-profile-frm-mobile" action="<?php echo base_url().$_SESSION['company']->url; ?>/change-profile" method="post" enctype="multipart/form-data">
						
					<?php
						if($this->session->flashdata('item')) {
							$message = $this->session->flashdata('item');
					?>
						<div class="group-form">
							<h4 style="color: green;"><?php echo $message; ?></h4>
						</div>
					<?php
						}
					?>
					<div class="small-form-group">
						<h4>Cambiar mi imagen de perfil</h4>
						<input type="file" name="user_image" style="float: left;">
					</div>
					<div class="small-form-group">
						<h4>Nueva Contraseña</h4>
						<input type="text" name="password" id="password-mobile">
					</div>
					<div class="small-form-group lastRecup">
						<h4>Repetí la nueva contraseña</h4>
						<input type="text" id="confirm-password-mobile">
					</div>
					<div class="group-wrapp-form">
						<button type="button" id="edit-profile-btn-mobile">Editar mi perfil</button>
					</div>
					<div class="err-class"></div>
				</form>
			</div>
		</div>
	</div>
	<div class="menu-overlay"></div>
	<div class="menu-delta menu-big-delta">
		<div class="header-part">
			<div class="user-profile-round-lg" style="background-image: url(<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>);">
					
			</div>
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

	
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
</body>
<style>
	.err-class{
		color: red;
		text-align: center;
		width: 100%;
		display: none;
		margin: 10px 0px;
	}
</style>

<script>
	$('#edit-profile-btn').on('click', function(){
		var password = $.trim($('#password').val());
		var cpassword = $.trim($('#confirm-password').val());
		
		if(password !== '' && cpassword !== ''){
			if(password !== cpassword){
				$('.err-class').text('Password & confirm password are not same!');
				$('.err-class').fadeIn();
			}else{
				$('#edit-profile-frm').submit();
			}
		}else{
			return false;
		}
	});
	
	$('#edit-profile-btn-mobile').on('click', function(){
		var password = $.trim($('#password-mobile').val());
		var cpassword = $.trim($('#confirm-password-mobile').val());
		
		if(password !== '' && cpassword !== ''){
			if(password !== cpassword){
				$('.err-class').text('Password & confirm password are not same!');
				$('.err-class').fadeIn();
			}else{
				$('#edit-profile-frm-mobile').submit();
			}
		}else{
			return false;
		}
	});
</script>
</html>