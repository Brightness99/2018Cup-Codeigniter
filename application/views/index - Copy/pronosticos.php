<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Pronosticos</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jQuery.countdownTimer.js"></script>
</head>
<body class="pronosticDelta">
		
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
				<div class="hint-wrapper">
					<div class="topHint">
						<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>">
						<p> <?php echo $companyDetails->descripcion; ?> </p>
					</div>
				</div>
			</div>
			<div class="score-block">
				<div class="profile-block-pronosticos" id="justThis">
					<div class="img-prof-block" style="background-image: url(<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>);">
						
					</div>
					<div class="text-prof-block">
						<h2>Hola, <?php echo $userDetails->nombre; ?>.</h2>
						<h4>Estás en el puesto <span><?php echo (!empty($user_rank)) ? $user_rank : 0; ?></span> del ranking con <span><?php echo (!empty($user_point->total_point)) ? $user_point->total_point : 0; ?></span> pts.</h4>
						<p>Completá los resultados de los siguientes partidos antes del <br> viernes 15/6 a las 18:00 Buena suerte!  </p>
					</div>
				</div>
				<div class="score-table">
					<ul>
						<li <?php if($this->uri->segment(3) == "fase"){ ?> class="active-fecha" <?php } ?>><a href="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/fase'; ?>">Fase Grupos</a></li>
						<li <?php if($this->uri->segment(3) == "octavos"){ ?> class="active-fecha" <?php } ?>><a href="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/octavos'; ?>">Octavos</a></li>
						<li <?php if($this->uri->segment(3) == "cuartos"){ ?> class="active-fecha" <?php } ?>><a href="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/cuartos'; ?>">Cuartos</a></li>
						<li <?php if($this->uri->segment(3) == "semi-final"){ ?> class="active-fecha" <?php } ?>><a href="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/semi-final'; ?>">Semi-Final</a></li>
						<li <?php if($this->uri->segment(3) == "final"){ ?> class="active-fecha" <?php } ?>><a href="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/final'; ?>">Final</a></li>
					</ul>
				</div>
				<div class="football-score-block">
					<div class="command-block">
						<p>Pronostico</p>
					</div>
					<div class="result-block">
						<p>Resultado</p>
					</div>
					<div class="puntos-block">
						<p>Puntos Obtenidos</p>
					</div>
					<div class="time-block">
						<p><img src="<?php echo base_url();  ?>assets/img/timer.png" alt="timer"></p>
					</div>
				</div>
				<form id="match-frm">
					<div class="football-table">
						<?php if(count($matchList) > 0){ foreach($matchList as $each_match){  ?>
							<div class="football-item">
								<div class="pronostico-football">
									<div class="first-command">
										<img src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['home_team_country']); ?>.png" alt="<?php echo $each_match['home_team_name']; ?>">
										<p><?php echo $each_match['home_team_name']; ?></p>
									</div>
									<div class="score-small">
										<input type="hidden" id="home_predection_<?php echo $each_match['match_id']; ?>"
										<?php
											if(isset($each_match['user_prediction'])){ ?> value = "<?php echo $each_match['user_prediction']->home_goals;  ?>" <?php } ?>
											name="home_predection[<?php echo $each_match['match_id']; ?>]" >
										
											<input type="hidden" id="away_predection_<?php echo $each_match['match_id']; ?>"
											<?php
												if(isset($each_match['user_prediction'])){ ?> value= "<?php echo $each_match['user_prediction']->away_goals;  ?>" <?php } ?>
											name="away_predection[<?php echo $each_match['match_id']; ?>]" >										
										<?php
											$timeDifference = 0;
											
											$start_date = date_create($each_match['kickoff']);
											$end_start 	= date_create();
											$diff  		= date_diff( $start_date, $end_start );
											
											$diffTotal = $diff->y*365*24 + $diff->m*30*24 + $diff->h;
										?>
										
										<p>
											<span <?php if($each_match['scored'] != 1 && $diffTotal > 24){ ?> contenteditable="true" <?php } ?> onkeyup="$('#home_predection_<?php echo $each_match['match_id']; ?>').val($(this).text())"><?php if(!empty($each_match['user_prediction'])){ echo $each_match['user_prediction']->home_goals; }else{ echo 'x'; } ?></span>
											-
											<span <?php if($each_match['scored'] != 1 && $diffTotal > 24){ ?> contenteditable="true" <?php } ?> onkeyup="$('#away_predection_<?php echo $each_match['match_id']; ?>').val($(this).text())"><?php if(!empty($each_match['user_prediction'])){ echo $each_match['user_prediction']->away_goals; }else{ echo 'x'; }  ?></span>
										</p>
									</div>
									<div class="second-command">
										<p><?php echo $each_match['away_team_name']; ?></p>
										<img src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['away_team_country']); ?>.png" alt="<?php echo $each_match['away_team_name']; ?>">
									</div>
								</div>
								<div class="wrapper-right-side-item ">
									<div class="resuldo-football">
										<p><?php echo $each_match['home_goals']; ?>-<?php echo $each_match['away_goals']; ?></p>
									</div>
									<div class="puntos-football">
										<p><?php echo $each_match['point']->puntos_empleado_valor; ?></p>
									</div>
									<div class="timer-football" id="time-left-<?php echo $each_match['match_id']; ?>">
										<?php if($each_match['scored'] == 1 || $diffTotal < 24){
											echo 'Finalizado';
										} ?>
									</div>
									<?php if($each_match['scored'] != 1 && $diffTotal > 24){ ?>
										<script type="text/javascript">
											$(function(){
											  $("#time-left-<?php echo $each_match['match_id']; ?>").countdowntimer({
												dateAndTime : "<?php echo $each_match['kickoff']; ?>",
												size : "lg"
											  });
											});
										</script>
									<?php } ?>
								</div>
							</div>
						<?php }} ?>
					</div>
				</form>
				<div class="button-wrapper-table">
					<button id="match-prediction-save-btn">Pronosticar</button>
					<a data-toggle="modal" data-target="#myModal" href="#" style="display: none;" id="match-prediction-save-btn-pc"></a>
				</div>
			</div>
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="pronosticos-small-block" style="position: relative;">
			<div class="mobile-menu-pronosticos">
				<div class="left-side-mobile">
					<img src="<?php echo base_url();  ?>assets/img/mobileBurger.png" alt="mobileBurger">
					<p>Menú</p>
				</div>
				<div class="right-side-mobile">
					<p>Hola , <?php echo $userDetails->nombre; ?></p><img src="<?php echo base_url();  ?>assets/img/mobileProf.png" alt="">
				</div>
			</div>
			<div class="small-dropdown omegaMenuDelta">
				<span class="custom-dropdown">
					<select id="mobile-group-change">
						<option <?php if($this->uri->segment(3) == "fase"){ ?> selected <?php } ?> value="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/fase'; ?>">Fase Grupos</option>
						<option <?php if($this->uri->segment(3) == "octavos"){ ?> selected <?php } ?> value="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/octavos'; ?>">Octavos</option>  
						<option <?php if($this->uri->segment(3) == "cuartos"){ ?> selected <?php } ?> value="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/cuartos'; ?>">Cuartos</option>
						<option <?php if($this->uri->segment(3) == "semi-final"){ ?> selected <?php } ?> value="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/semi-final'; ?>">Semi-Final</option>
						<option <?php if($this->uri->segment(3) == "final"){ ?>  selected <?php } ?> value="<?php echo base_url().$_SESSION['logged_in_company'].'/pronosticos/final'; ?>">Final</option>
					</select>
				</span>				
			</div>
			<div class="slider-wrap-block">
				<div class="table-small-wrapper">
					<form id="match-frm-mobile" >
						<?php if(count($matchList) > 0){ foreach($matchList as $each_match){  ?>
							<div class="small-item-wrap">
								<div class="country-block">
									<img class='left-image-country' src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['home_team_country']); ?>.png" alt="<?php echo $each_match['home_team_name']; ?>">
									<p><?php echo $each_match['home_team_name']; ?> <span>VS</span> <?php echo $each_match['away_team_name']; ?></p>
									<img class='right-image-country' src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['away_team_country']); ?>.png" alt="<?php echo $each_match['away_team_name']; ?>">
									<span class="fech-block">
										<p>Comienza en:
											<span id="time-left-mobile-<?php echo $each_match['match_id']; ?>">
												<?php if($each_match['scored'] == 1 || $diffTotal < 24){
													echo 'Finalizado';
												} ?>
											</span>
										</p>
									</span>
								</div>
								<input type="hidden" class="h_p_mb" id="mobile_home_predection_<?php echo $each_match['match_id']; ?>"
									<?php
										if(isset($each_match['user_prediction'])){ ?> value = "<?php echo $each_match['user_prediction']->home_goals;  ?>" <?php } ?>
										name="home_predection[<?php echo $each_match['match_id']; ?>]" >
									
										<input type="hidden" class="a_p_mb" id="mobile_away_predection_<?php echo $each_match['match_id']; ?>"
										<?php
											if(isset($each_match['user_prediction'])){ ?> value= "<?php echo $each_match['user_prediction']->away_goals;  ?>" <?php } ?>
										name="away_predection[<?php echo $each_match['match_id']; ?>]" >
								<div class="time-small-block">
									<?php
										$timeDifference = 0;
										
										$start_date = date_create($each_match['kickoff']);
										$end_start = date_create();
										$diff  	= date_diff( $start_date, $end_start );
										
										$diffTotal = $diff->y*365*24 + $diff->m*30*24 + $diff->h;
									?>
									
									<div class="roundred leftRound" <?php if($each_match['scored'] != 1 && $diffTotal > 24){ ?> contenteditable="true" <?php } ?> 	onkeyup="$('#mobile_home_predection_<?php echo $each_match['match_id']; ?>').val($(this).text())"><?php if(!empty($each_match['user_prediction'])){ echo $each_match['user_prediction']->home_goals; }else{ echo 'x'; }  ?>
									</div>
									<span>X</span>
									<div class="roundred rightRound" <?php if($each_match['scored'] != 1 && $diffTotal > 24){ ?> contenteditable="true" <?php } ?> onkeyup="$('#mobile_away_predection_<?php echo $each_match['match_id']; ?>').val($(this).text())"><?php if(!empty($each_match['user_prediction'])){ echo $each_match['user_prediction']->away_goals; }else{ echo 'x'; }  ?>
									</div>
									
									<img src="<?php echo base_url();  ?>assets/img/timer.png" class="small-timer" alt="timer">
									
									<?php if($each_match['scored'] != 1 && $diffTotal > 24){ ?>
										<script type="text/javascript">
											$(function(){
											  $("#time-left-mobile-<?php echo $each_match['match_id']; ?>").countdowntimer({
												dateAndTime : "<?php echo $each_match['kickoff']; ?>",
												size : "lg"
											  });
											});
										</script>
									<?php } ?>
								</div>
							</div>
						<?php }} ?>
					</form>
					<div class="button-wrapper-table prediction-btn-mb" style="background: #FFF;
							position: fixed;
							z-index: 999;
							bottom: 0;
							display: block;
							width: 12%;
							padding: 10px 0px;opacity: 1;">
							<button type="button" id="match-prediction-save-btn-mobile">Pronosticar</button>
						</div>
				</div>
				<?php include('right-bar-mobile.php'); ?>
			</div>
		</div>
		
		<a data-toggle="modal" data-target="#myModal" href="#" style="display: none;" id="match-prediction-save-btn-mb"></a>
		
		<div class="modal fade" id="myModal-mb" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<h3>Tus pronósticos fueron guardados!</h3>
					<div class="button-wrapper-table">
						<button  data-dismiss="modal">&nbsp;&nbsp; Cerrar &nbsp;&nbsp; </button>
					</div>
				</div>
			</div>
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
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<h3>Tus pronósticos fueron guardados!</h3>
				<div class="button-wrapper-table">
					<button  data-dismiss="modal">&nbsp;&nbsp; Cerrar &nbsp;&nbsp; </button>
				</div>
			</div>
		</div>
	</div>

  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
</body>
<script>
	$('#match-prediction-save-btn').on('click', function(){
		var home_predection = $('input[name^="home_predection"]')
              .map(function(){return $(this).val();}).get();
			  
		var err= 0;
			  
		var away_predection = $('input[name^="away_predection"]')
              .map(function(){return $(this).val();}).get();
			  
		$.each(home_predection, $.proxy(function(index, item) {
			
			if(item !== ''){
				if(away_predection[index] == ''){
					alert('Sus pronósticos no se guardaron, por favor verifique');
					err++;
				}
			}
		}, this));
		
		if(err == 0){
			$.ajax({
				url: '<?php echo base_url().$_SESSION['company']->url; ?>/save-prediction',
				type: "POST",
				data: $('#match-frm').serializeArray(),
				dataType: "json",
				success: function(response){
					$('#match-prediction-save-btn-pc').trigger('click');
					setTimeout(function(){ location.reload(); }, 5000);
				}
			});	
		}
	});
	
	$('#match-prediction-save-btn-mobile').on('click', function(){
		var home_mb_prediction = [];
		var away_mb_prediction = [];
		
		var err= 0;
		
		$('.h_p_mb').each(function(i,v){
			var val_h = $(this).val();
			home_mb_prediction.push(val_h);
		});
		
		$('.a_p_mb').each(function(i,v){
			var val_a = $(this).val();
			away_mb_prediction.push(val_a);
		});
		

		$.each(home_mb_prediction, function(index, item) {
			if(item !== ''){
				if(away_mb_prediction[index] == ''){
					alert('Sus pronósticos no se guardaron, por favor verifique');
					err++;
				}
			}
		});
		
		if(err == 0){
			$.ajax({
				url: '<?php echo base_url().$_SESSION['company']->url; ?>/save-prediction',
				type: "POST",
				data: $('#match-frm-mobile').serializeArray(),
				dataType: "json",
				success: function(response){
					$('#match-prediction-save-btn-mb').trigger('click');
					setTimeout(function(){ location.reload(); }, 3000);
				}
			});
		}
	});
	
	
	$('#mobile-group-change').on('change', function(){
		var link = $(this).val();
		
		window.location.replace(link);
	});
</script>

<style>
	.table-small-wrapper{
		overflow: scroll;
	}
</style>


</html>