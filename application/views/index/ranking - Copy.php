<div class="pronosticos-block">
	<div class="hint-wrapper">
		<div class="topHint">
			<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>">
			<p> <?php echo $companyDetails->descripcion; ?> </p>
		</div>
	</div>
	<div class="ranking-wrapper">
		<div class="ranking-profile">
			<?php if($userDetails->imagen_perfil != ''){ ?>
				<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>" alt="profile">
			<?php }else{ ?>
				<img src="<?php echo base_url(); ?>assets/img/profile.png" alt="profile">
			<?php } ?>
			<h3>Estás en el puesto<br> <span><?php echo (!empty($user_rank)) ? $user_rank : 0; ?></span> del ránking  con <br><span><?php echo (!empty($user_point->total_point)) ? $user_point->total_point : 0; ?></span> pts.</h3>
		</div>
		<div class="top-rank-wrapp">
			<div class="top-ranking">
			<h3>Tu Ránking:</h3>
			<div class="top-top-block">
				<div class="wrap-content">
					<p>Puesto</p>
					<p>Puntos</p>
				</div>
			</div>
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
			<button>Ranking  General</button>
			<div class="left-side-block">
				<button onclick="showRanking('group');">Grupos</button>
				<button onclick="showRanking('octavos');">Octavos</button>
				<button onclick="showRanking('cuartos');">Cuartos</button>
			</div>
			<div class="main-foootball" id="ajax-ranking">
				<?php if($general_group_ranking){ foreach($general_group_ranking as $rank){ ?>
					<p><span><?php echo $rank['total_point']; ?></span>  <?php echo $rank['nombre'].' '.$rank['apellido']; ?></p>
				<?php }}else{ ?>
					<span>No record found</span>
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


<script>
	function showRanking(group){
		$.ajax({
			url: '<?php echo base_url(); ?>'+company+'/get-ajax-ranking',
			type: "POST",
			data: {group:group},
			dataType: "html",
			success: function(response){
				$('#ajax-ranking').html(response);
			}
		});	
	}
</script>