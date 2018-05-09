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
		<?php include('inc/top-left-menu.php'); ?>
		<div class="pronosticos-block">
			<div class="hint-wrapper">
				<div class="topHint">
					<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>">
					<div id="wn">
						<div id="lyr">
							<div><?php echo $companyDetails->descripcion; ?></div>
							<div id="rpt"><?php echo $companyDetails->descripcion; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="ranking-wrapper">
				<div class="ranking-profile">
					<?php if($userDetails->imagen_perfil != ''){ ?>
						<div class="img-prof-block" style="width: 80px;height: 80px;border-radius: 50%;background-size: cover;background-repeat: no-repeat;background-position: center;background-image: url(<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>);">
							
						</div>
					<?php }else{ ?>
						<div class="img-prof-block" style="width: 80px;height: 80px;border-radius: 50%;background-size: cover;background-repeat: no-repeat;background-position: center;background-image: url(<?php echo base_url(); ?>assets/img/profileMenu.png);">
							
						</div>
					<?php } ?>
					<h3>Estás en el puesto<br> <span><?php echo (!empty($user_rank)) ? $user_rank : 0; ?></span> del ránking  con <br><span><?php echo (!empty($user_point->total_point)) ? $user_point->total_point : 0; ?></span> pts.</h3>
				</div>
				<div class="top-ranking-wrapp">
					<div class="top-ranking">
						<h3>Tu Ránking:</h3>
						<div class="top-top-block">
						<div class="wrap-content">
							<p>Puesto</p>
							<p>Puntos Pronósticos</p>
							<p>Puntos Trivias</p>
						</div>
					</div>
						<div class="non-top-block">
							<p>Grupos</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $groups_point['rank']; ?></p>
								<p><?php echo $groups_point['total_point']; ?></p>
								<p><?php echo $groups_point_trivias['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Octavos</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $octavos_point['rank']; ?></p>
								<p><?php echo $octavos_point['total_point']; ?></p>
								<p><?php echo $octavos_point_trivias['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Cuartos</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $cuartos_point['rank']; ?></p>
								<p><?php echo $cuartos_point['total_point']; ?></p>
								<p><?php echo $cuartos_point_trivias['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Semi-Final</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $semi_final_point['rank']; ?></p>
								<p><?php echo $semi_final_point['total_point']; ?></p>
								<p><?php echo $semi_final_point_trivias['total_point']; ?></p>
							</div>
						</div>
						<div class="non-top-block">
							<p>Final</p>	
							<div class="non-general-block clearfix">
								<p><?php echo $final_point['rank']; ?></p>
								<p><?php echo $final_point['total_point']; ?></p>
								<p><?php echo $final_point_trivias['total_point']; ?></p>
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
						<div class="row">
							<div style="width: 40px;height: 40px;float: left;margin-right: 10px;margin-bottom: 10px;">
								<?php
									if($rank['imagen_perfil'] != ''){
										$p_image = base_url().'img/empleadosPerfil/'.$rank['imagen_perfil'];
									}else{
										$p_image = base_url().'assets/img/profileMenu.png';
									}
								?>
									
								
								<div class="pc-image" style="background-image: url(<?php echo $p_image; ?>);">
									
								</div>
								
							</div>
							<div  style="width: 173px; min-height: 20px;float: left;margin-right: 10px;font-family: DushaFifa;">
								<span><?php echo $rank['total_point']; ?></span>  <?php echo $rank['nombre'].' '.$rank['apellido']; ?>
							</div>
							<div class="clearfix"></div>
						</div>
						
						<style>
						.pc-image{
							width: 40px;
							height: 40px;
							background-position: center;
							background-size: cover;
							border-radius: 2px;
						}
						</style>
						
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
			<?php include('inc/mobile-top-menu.php'); ?>
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
								<img src="<?php echo base_url(); ?>assets/img/profileMenu.png" alt="profileMenu">
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

	</div>
	<?php include('inc/left-menu.php'); ?>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/dw_con_scroller.js" type="text/javascript"></script>
	<script type="text/javascript">
		if ( DYN_WEB.Scroll_Div.isSupported() ) {
			
			DYN_WEB.Event.domReady( function() {
				
				// arguments: id of scroll area div, id of content div
				var wndo = new DYN_WEB.Scroll_Div('wn', 'lyr');
				// see info online at http://www.dyn-web.com/code/scrollers/continuous/documentation.php
				wndo.makeSmoothAuto( {axis:'v', bRepeat:true, repeatId:'rpt', speed:25, bPauseResume:true} );
				
			});
		}
	</script>
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