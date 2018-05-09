<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ranking</title>
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
<body class="rankingPAGE">
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
			<div class="ranking-wrapper">
				<div class="ranking-profile">
					<div class="img-prof-block" style="background-image: url(<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>);">
						
					</div>
					<h3>Estás en el puesto<br> <span><?php echo (!empty($user_rank)) ? $user_rank : 0; ?></span> del ránking  con <br><span><?php echo (!empty($user_point->total_point)) ? $user_point->total_point : 0; ?></span> pts.</h3>
				</div>
				<div class="top-rank-wrapp">
					<div class="top-ranking">
						<h3>Tu Ránking:</h3>
						<div class="non-top-block">
							<p>Grupos</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $groups_point['rank']; ?></p>
								<p><?php echo $groups_point['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Octavos</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $octavos_point['rank']; ?></p>
								<p><?php echo $octavos_point['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Cuartos</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $cuartos_point['rank']; ?></p>
								<p><?php echo $cuartos_point['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Semi-Final</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $semi_final_point['rank']; ?></p>
								<p><?php echo $semi_final_point['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Final</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $final_point['rank']; ?></p>
								<p><?php echo $final_point['total_point']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ranking-football">
				<button onclick="showRanking('general');">Ranking  General</button>
				<div class="left-side-block">
					<button onclick="showRanking('group');">Grupos</button>
					<button onclick="showRanking('octavos');">Octavos</button>
					<button onclick="showRanking('cuartos');">Cuartos</button>
				</div>
				<div class="main-foootball" id="ajax-ranking">
					<?php if($general_group_ranking){ foreach($general_group_ranking as $rank){ ?>
						<p><span><?php echo $rank['total_point']; ?></span>  <?php echo $rank['nombre'].' '.$rank['apellido']; ?></p>
					<?php }}else{ ?>
						<span>No hay resultados</span>
					<?php } ?>
				</div>
				<div class="right-side-block">
					<button onclick="showRanking('final');" class="final-button">Final</button>
					<button onclick="showRanking('semi');">Semi-Final</button>
				</div>
				<div class="football-ship">
					<img src="<?php echo base_url(); ?>assets/img/footBallShip.png" alt="footBallShip">
				</div>
			</div>
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="pronosticos-small-block">
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
					<select id="mobile-change-group">
						<option value="general">Ranking General</option>
						<option value="group">Grupos</option>  
						<option value="octavos">Octavos</option>
						<option value="cuartos">Cuartos</option>
						<option value="semi">Semi-Final</option>
						<option value="final">Final</option>
					</select>
				</span>
			</div>
			<div class="persons-score-match" id="mobile-ranking-list">
				<?php if($general_group_ranking){ foreach($general_group_ranking as $rank){ ?>
					<div class="person-info">
						<div class="wrapplinger-profile-image">
							<?php if($rank['imagen_perfil'] != ''){ ?>
								<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $rank['imagen_perfil']; ?>" alt="profileMenu">
							<?php }else{ ?>
								<img src="<?php echo base_url(); ?>assets/img/profileGrey.png" alt="profileMenu">
							<?php } ?>
						</div>
						<div class="wrapplinger-text-profile">
							<p><?php echo $rank['nombre'].' '.$rank['apellido']; ?></p>
							<span><?php echo $rank['total_point']; ?> pts</span>
						</div>
					</div>
				<?php }}else{ ?>
					<span>No hay resultados</span>
				<?php } ?>
			</div>
			<div class="person-all">
				<div class="person-item">
					<h3><?php echo (!empty($user_point->total_point)) ? $user_point->total_point : 0; ?></h3>
					<span>Tus Puntos</span>
				</div>
				<div class="person-item">
					<h3><?php echo (!empty($user_rank)) ? $user_rank : 0; ?></h3>
					<span>Tu Puesto</span>
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
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
</body>

<script>
	function showRanking(group){
		$.ajax({
			url: '<?php echo base_url().$_SESSION['company']->url; ?>/get-ajax-ranking',
			type: "POST",
			data: {group:group},
			dataType: "html",
			success: function(response){
				$('#ajax-ranking').html(response);
			}
		});	
	}
	
	function showRankingMobile(group){
		$.ajax({
			url: '<?php echo base_url().$_SESSION['company']->url; ?>/get-ajax-ranking-mobile',
			type: "POST",
			data: {group:group},
			dataType: "html",
			success: function(response){
				$('#mobile-ranking-list').html(response);
			}
		});	
	}
	
	$('#mobile-change-group').on('change', function(){
		var val = $(this).val();
		
		showRankingMobile(val);
	});
</script>
</html>