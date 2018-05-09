<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Trivias</title>
<script
  	src="https://code.jquery.com/jquery-3.3.1.js"
 	integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  	crossorigin="anonymous">
</script>
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
			
			<div class="trivias-inner-block">
				<?php if($trivia_user_record == null){ if($trivia){ ?>
					<div class="wrapper-header-trivias">
						<h2>Trivia:<span> <?php echo $group; ?> Grupos</span></h2>
						<div class="timer-trivias">
							<img src="<?php echo base_url(); ?>assets/img/timer.png" alt="timer">
							<p id="trivias-timer"></p>
						</div>
					</div>
					<div class="checkbox-wrapper">
						<form id="pc-trivia-form">
							<?php foreach($trivia as $key => $trivia_each){$j = $key+1; ?>
								<?php if($key == 0){ ?>
									<input type="hidden" id="end-pc-trivia-date" value="<?php echo $trivia_each['vencimiento']; ?>">
									<input type="hidden" id="id_trivia" name="id_trivia" value="<?php echo $trivia_each['id_trivia']; ?>">
								<?php } ?>
								<input type="hidden" name="id_pregunta[]" value="<?php echo $trivia_each['id_pregunta']; ?>">
								
								<div class="radioBox">
									<h4><?php echo $trivia_each['pregunta']; ?></h4>
									<?php foreach($trivia_each['answer'] as $k => $answer){ ?>
										<p>
											<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" name="radio-group<?php echo $j; ?>" checked>
											<label for="answer<?php echo $answer['id_respuesta']; ?>"><?php echo $answer['respuesta']; ?> </label>
										</p>
									<?php } ?>
								</div>
							<?php } ?>
						</form>
						<div class="trivias-button-wrapper">
							<a href="<?php echo base_url().$_SESSION['company']->url; ?>/respuestas-anteriores"><button class="respuest-button">Respuestas anteriores</button></a>
							<button class='responder-button' data-toggle='modal' id="pc-form-btn" data-target="#exampleModalCenter">Responder</button>
						</div>
					</div>
				<?php }else{ echo "No trivia"; }}else{ ?>
					<div class="wrapper-header-trivias">
						<h2>Trivia:<span> <?php echo $group; ?></span></h2>
						<div class="timer-trivias">
							<img src="<?php echo base_url(); ?>assets/img/timer.png" alt="timer">
						</div>
					</div>
					<div class="checkbox-wrapper">
						<form id="pc-trivia-form">
							<?php foreach($trivia as $key => $trivia_each){ $j = $key+1; ?>
								<?php if($key == 0){ ?>
									<input type="hidden" id="end-pc-trivia-date" value="<?php echo $trivia_each['vencimiento']; ?>">
									<input type="hidden" id="id_trivia" name="id_trivia" value="<?php echo $trivia_each['id_trivia']; ?>">
								<?php } ?>
								<div class="radioBox">
									<h4><?php echo $trivia_each['pregunta']; ?></h4>
									<?php foreach($trivia_each['answer'] as $k => $answer){ ?>
										<p>
											<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" disabled name="radio-group<?php echo $j; ?>" <?php if(in_array($answer['id_respuesta'],$trivia_user_record)){ ?>checked<?php } ?>>
											<label for="answer<?php echo $answer['id_respuesta']; ?>"><?php echo $answer['respuesta']; ?> </label>
										</p>
									<?php } ?>
								</div>
							<?php } ?>
						</form>
						<div class="trivias-button-wrapper">
							<a href="<?php echo base_url().$_SESSION['company']->url; ?>/respuestas-anteriores"><button class="respuest-button">Respuestas anteriores</button></a>
						</div>
					</div>
				<?php } ?>
			</div>
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
			
			<?php if(count($trivia_user_record) > 0){ if($trivia){ ?>
				<div class="small-trivias">
					<h3>Trivia: <span><?php echo $group; ?> Grupos</span></h3>
					<form id="mobile-trivia-form">
						<div class="trivias-slider">
							<?php foreach($trivia as $key => $trivia_each){ $j = $key+5; ?>
								<div class="radioBox">
									<?php if($key == 0){ ?>
										<input type="hidden" id="end-pc-trivia-date" value="<?php echo $trivia_each['vencimiento']; ?>">
										<input type="hidden" id="id_trivia" name="id_trivia" value="<?php echo $trivia_each['id_trivia']; ?>">
									<?php } ?>
									<input type="hidden" name="id_pregunta[]" value="<?php echo $trivia_each['id_pregunta']; ?>">
									<h4><?php echo $trivia_each['pregunta']; ?></h4>
									<?php foreach($trivia_each['answer'] as $k => $answer){ ?>
										<p>
											<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" name="radio-group<?php echo $j; ?>" <?php if(in_array($answer['id_respuesta'],$trivia_user_record)){ ?>checked<?php } ?>>
											<label for="answer<?php echo $answer['id_respuesta']; ?>"><?php echo $answer['respuesta']; ?> </label>
										</p>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</form>
				</div>
				<div class="small-timer-trivias">
					<img src="<?php echo base_url(); ?>assets/img/timer.png" alt="timer">
					<input type="hidden" id="end-mobile-trivia-date" value="<?php echo $trivia_each['vencimiento']; ?>">
					<p id="small-timer-trivias"></p>
				</div>
				<div class="small-trivias-button">
					<button data-toggle='modal' data-target="#exampleModalCenter">Responder</button>
				</div>
			<?php }else{ echo "No trivia"; }}else{ ?>
				<div class="small-trivias">
					<h3>Trivia: <span><?php echo $group; ?></span></h3>
					<div class="trivias-slider">
						<?php foreach($trivia as $key => $trivia_each){ $j = $key+5; ?>
							<div class="radioBox">
							<?php if($key == 0){ ?>
								<input type="hidden" id="end-pc-trivia-date" value="<?php echo $trivia_each['vencimiento']; ?>">
								<input type="hidden" id="id_trivia" name="id_trivia" value="<?php echo $trivia_each['id_trivia']; ?>">
							<?php } ?>
							<input type="hidden" name="id_pregunta[]" value="<?php echo $trivia_each['id_pregunta']; ?>">
								<h4><?php echo $trivia_each['pregunta']; ?></h4>
								<?php foreach($trivia_each['answer'] as $k => $answer){ ?>
									<p>
										<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" name="radio-group<?php echo $j; ?>" <?php if(in_array($answer['id_respuesta'],$trivia_user_record)){ ?>checked<?php } ?>>
										<label for="answer<?php echo $answer['id_respuesta']; ?>"><?php echo $answer['respuesta']; ?> </label>
									</p>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					
				</div>
				<div class="small-timer-trivias">
					<img src="<?php echo base_url(); ?>assets/img/timer.png" alt="timer">
				</div>
				<div class="small-trivias-button">
					<a href="<?php echo base_url().$_SESSION['company']->url; ?>/respuestas-anteriores"><button class="respuest-button">Respuestas anteriores</button></a>
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
				   Trivia
				</h2>
				<h3>Gracias<br> <span class="firstSpanModal">por</span> <br> <span class="secSpanModal">participar!</span></h3>
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
				setTimeout(function(){ location.reload(); }, 5000);	
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
				setTimeout(function(){ location.reload(); }, 5000);		
			}
		});	
	});
</script>

</html>