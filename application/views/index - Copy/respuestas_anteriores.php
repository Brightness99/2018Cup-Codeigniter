<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Trivias</title>
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

<body id="traviasPage">
	<div class="wrapper-pronosticos-block">
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
		
		<div class="pronosticos-block">
			<div class="hint-wrapper">
				<div class="topHint">
					<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>">
					<p> <?php echo $companyDetails->descripcion; ?> </p>
				</div>
			</div>
			<?php if($triviaList and count($user_trivia_answer) > 0){ $countTrivia = count($triviaList); ?>
				<?php foreach($triviaList as $i => $trivia){ $triviaCurrent = $i+1; ?>
					<?php
						$group = $trivia['trivia_details']->id_fase;

						if(in_array($group,array(1,2,3,4,5,6,7,8))){
							$group_name = 'Fase';
						}
						elseif(in_array($group,array(9))){
							$group_name = 'Octavos';
						}
						elseif(in_array($group,array(10))){
							$group_name = 'Cuartos';
						}
						elseif(in_array($group,array(11))){
							$group_name = 'Semi-final';
						}
						elseif(in_array($group,array(12,13))){
							$group_name = 'Final';
						}
					?>
						<div class="trivias-inner-block <?php if($i != 0){ ?> trivia-hide <?php } ?>" id="block-trivia-<?php echo $triviaCurrent; ?>" >
							<div class="wrapper-header-trivias">
								<h2>Trivia:<span> <?php echo $group_name; ?> Grupos</span></h2>
								<div class="timer-trivias">
									<img src="<?php echo base_url(); ?>assets/img/timer.png" alt="timer">
								</div>
							</div>
							<div class="checkbox-wrapper">
								<?php foreach($trivia['trivia_question'] as $key => $trivia_each){ $j = $key+1+$i; ?>
									<div class="radioBox">
										<h4><?php echo $trivia_each['pregunta']; ?></h4>
										<?php foreach($trivia_each['answer'] as $k => $answer){ ?>
											<p>
												<input type="radio"  value="<?php echo $answer['id_respuesta']; ?>"  disabled <?php if(in_array($answer['id_respuesta'],$user_trivia_answer)){ ?>checked<?php } ?>>
												<label for="answer<?php echo $answer['id_respuesta']; ?>"><?php echo $answer['respuesta']; ?> </label>
											</p>
										<?php } ?>
									</div>
								<?php $j++; } ?>
							</div>
							<?php if($i != 0){ ?>
								<button type="button" class="pull-left trivia-action-btn trivia-previous-btn" data-trivia-key = "<?php echo $triviaCurrent; ?>"><< Anterior</button>
							<?php } ?>
							<?php if($triviaCurrent != $countTrivia){ ?>
								<button type="button" class="pull-right trivia-action-btn trivia-next-btn"  data-trivia-key = "<?php echo $triviaCurrent; ?>">Siguiente >></button>
							<?php } ?>
						</div>
				<?php } ?>	
			<?php }else{ ?>
				<div class="trivias-inner-block">
					<div class="wrapper-header-trivias">
						<h3>Ningún record fue encontrado</h3>
					</div>
				</div>
			<?php } ?>
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="pronosticos-small-block trivias-Small-Bann">
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
			
			
			<?php if($triviaList){ ?>
				<?php foreach($triviaList as $trivia){ ?>
					<?php
						$group = $trivia['trivia_details']->id_fase;

						if(in_array($group,array(1,2,3,4,5,6,7,8))){
							$group_name = 'Fase';
						}
						elseif(in_array($group,array(9))){
							$group_name = 'Octavos';
						}
						elseif(in_array($group,array(10))){
							$group_name = 'Cuartos';
						}
						elseif(in_array($group,array(11))){
							$group_name = 'Semi-final';
						}
						elseif(in_array($group,array(12,13))){
							$group_name = 'Final';
						}
					?>
					<div class="small-trivias">
						<h3>Trivia: <span><?php echo $group_name; ?> Grupos</span></h3>
						<div class="trivias-slider">
							<?php foreach($trivia['trivia_question'] as $key => $trivia_each){ $j = $key+12; ?>
								<div class="radioBox">
									<h4><?php echo $trivia_each['pregunta']; ?></h4>
									<?php foreach($trivia_each['answer'] as $k => $answer){ ?>
										<p>
											<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" name="radio-group<?php echo $j; ?>" disabled <?php if(in_array($answer['id_respuesta'],$user_trivia_answer)){ ?>checked<?php } ?>>
											<label for="answer<?php echo $answer['id_respuesta']; ?>"><?php echo $answer['respuesta']; ?> </label>
										</p>
									<?php } ?>
								</div>
							<?php $j++; } ?>
						</div>
					</div>
				<?php } ?>	
			<?php }else{ ?>
				<div class="small-trivias">
					<h3>Ningún record fue encontrado</h3>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="menu-overlay"></div>
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
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<h2 class="first-header">
				   Felicidades
				</h2>
				<h3>Duplicaste <br> <span class="firstSpanModal">tus</span> <br> <span class="secSpanModal">Puntos</span></h3>
				<div class="modal-button-cont">
				   <button>Continuar</button>
				</div>
			</div>
		</div>
	</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
</body>


<script>
	$('#pc-form-btn').on('click', function(){
		$.ajax({
			url: '<?php echo base_url().$_SESSION['company']->url; ?>/save-trivia-record',
			type: "POST",
			data: $('#pc-trivia-form').serializeArray(),
			dataType: "json",
			success: function(response){
				location.reload();		
			}
		});	
	});
	
	$('#mobile-form-btn').on('click', function(){
		$.ajax({
			url: '<?php echo base_url().$_SESSION['company']->url; ?>/save-trivia-record',
			type: "POST",
			data: $('#mobile-trivia-form').serializeArray(),
			dataType: "json",
			success: function(response){
				location.reload();		
			}
		});	
	});
	
	$('.trivia-next-btn').on('click', function(){
		var triviaNow = $(this).attr('data-trivia-key');
		var triviaCount = '<?php echo $countTrivia; ?>';
		var nextTrivia = parseInt(triviaNow) + 1;
		
		$('#block-trivia-'+triviaNow).addClass('trivia-hide');
		$('#block-trivia-'+nextTrivia).removeClass('trivia-hide');
	});
	
	$('.trivia-previous-btn').on('click', function(){
		var triviaNow = $(this).attr('data-trivia-key');
		var triviaCount = '<?php echo $countTrivia; ?>';
		var nextTrivia = parseInt(triviaNow) - 1;
		
		$('#block-trivia-'+triviaNow).addClass('trivia-hide');
		$('#block-trivia-'+nextTrivia).removeClass('trivia-hide');
	});
</script>


<style>
.trivia-action-btn {
    cursor: pointer;
    font-size: 26px;
    font-family: DushaFifa;
    color: white;
    border: 0px;
    padding: 10px 52px;
    padding-top: 6px;
	margin-top: 10px;
    background: url(<?php echo base_url(); ?>assets/img/submitButtonRed.png);
    background-repeat: no-repeat;
    background-size: contain;
}

.trivia-hide{
	display: none;
}
</style>

</html>