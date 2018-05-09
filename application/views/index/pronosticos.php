<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Pronosticos</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.0.3.js.download"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.countdownTimer.min.js.download"></script>
</head>

<style>
	div.roundred{
		background: none;
	}
	
	.small-item-wrap{
		padding-bottom: 5px;
	}
	
	.div-select{
		width: 54px;
		float: left;
		position: relative;
	}
	
	.div-select select{
		background-color: #d2171e;
		outline: none;
		background: url(<?php echo base_url(); ?>assets/img/score-bck.png);
		width: 100%;
		background-position: -26px;
		border-radius: 12px;
		height: 30px;
		color: #FFF;
		padding-left: 9px;
		font-family: DushaFifa;
		font-size: 18px;
	}
	
	.disabled-select-left{
		color: #000 !important;
		background: none !important;
		padding-left: 32px !important;
		border: none !important;
		-webkit-appearance: none !important;
		-moz-appearance: none !important;
	}
	
	.disabled-select-right{
		color: #000 !important;
		background: none !important;
		padding-left: 6px !important;
		border: none !important;
		-webkit-appearance: none !important;
		-moz-appearance: none !important;
	}
	
	
	.div-select::before {
		width: 2em;
		content: '';
		background: url(<?php echo base_url(); ?>assets/img/arrow-white.png);
		background-repeat: no-repeat;
		right: 10px;
		top: 10px;
		bottom: 0;
		z-index: 10000000;
		border-radius: 0 3px 3px 0;
	}
	
	
	.div-select::after {
		content: "";
		background-color: #d2171e;
		height: 1em;
		font-size: .625em;
		line-height: 1;
		right: 1.2em;
		top: 50%;
		margin-top: -.5em;
	}
	
	.div-select select option {
		background: #d2171e;
		color: #fff;
	}
	
	.middle-dash{
		width: 8px;
		text-align: center;
		float: left;
		font-weight: 600;
		font-family: DushaFifa;
	}
	
	.roundred select {
		background-color: #d2171e;
		outline: none;
		background: url(<?php echo base_url(); ?>assets/img/score-bck.png);
		width: 100%;
		background-position: -26px;
		border-radius: 12px;
		height: 30px;
		color: #FFF;
		padding-left: 9px;
		font-family: DushaFifa;
		font-size: 18px;
	}
	
	.roundred select option {
		background: #d2171e;
		color: #fff;
	}
	
	
	.score-small {
		background-position: 2px 0px;
		background-size: 96%;
	}
	
	.fech-block{
		width: 200px;
		height: 11px;
	}
</style>
<body class="pronosticDelta">
		
	<div class="wrapper-pronosticos-block">
		<?php include('inc/top-left-menu.php'); ?>
		<div class="pronosticos-block">
			<div class="hint-wrapper">
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
			</div>
			<div class="score-block" style="position: relative;">
				<img src="<?php echo base_url(); ?>/assets/img/39710540-79142e56-51f3-11e8-8f1a-fbef72dd9a28.gif" width="20" height="auto" style="position: absolute;top: 464px;left: 32px;">
				<div class="profile-block-pronosticos" id="justThis">
					<?php if($userDetails->imagen_perfil != ''){ ?>
						<div class="img-prof-block" style="width: 80px;height: 80px;border-radius: 50%;background-size: cover;background-repeat: no-repeat;background-position: center;background-image: url(<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $userDetails->imagen_perfil; ?>);">
							
						</div>
					<?php }else{ ?>
						<div class="img-prof-block" style="width: 80px;height: 80px;border-radius: 50%;background-size: cover;background-repeat: no-repeat;background-position: center;background-image: url(<?php echo base_url(); ?>assets/img/profileMenu.png);">
							
						</div>
					<?php } ?>
					<div class="text-prof-block">
						<h2>Hola, <?php echo $userDetails->nombre; ?>.</h2>
						<h4>Estás en el puesto <span><?php echo (!empty($user_rank)) ? $user_rank : 0; ?></span> del ranking con <span><?php echo (!empty($user_point->total_point)) ? $user_point->total_point : 0; ?></span> pts.</h4>
						<p>Completá los resultados de los siguientes partidos antes del <br>  miércoles 13/6 a las 12:00pm </p>
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
					<div class="football-table"  style="display: none;">
						<?php if(count($matchList) > 0){ foreach($matchList as $each_match){  ?>
							<?php
								$timeDifference = 0;

								$seconds 	= strtotime($each_match['kickoff']) - strtotime(@date("Y-m-d h:i:s"));
								$days    	= floor($seconds / 86400);
								$diffTotal  = $days*24;
							?>
							<div class="football-item">
								<div class="pronostico-football" >
									<div class="first-command">
										<img src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['home_team_country']); ?>.png" alt="<?php echo $each_match['home_team_name']; ?>">
										<p><?php echo $each_match['home_team_name']; ?></p>
									</div>
									<div class="<?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> time-expired <?php } ?>"> 										
										<p>
											<div class="div-select">
												<select <?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> class="disabled-select-left" disabled <?php } ?> id="home_predection_<?php echo $each_match['match_id']; ?>" name="home_predection[<?php echo $each_match['match_id']; ?>]">
													<option value="">X</option>

													<?php for($i=0;$i<=20;$i++){ ?>
														<option <?php if(isset($each_match['user_prediction']->home_goals)){ if($each_match['user_prediction']->home_goals == $i){ ?> selected <?php }} ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="middle-dash">-</div>
											<div class="div-select">
												<select <?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> class="disabled-select-right" disabled <?php } ?> id="away_predection_<?php echo $each_match['match_id']; ?>" name="away_predection[<?php echo $each_match['match_id']; ?>]">
													<option value="">X</option>
													<?php for($i=0;$i<=20;$i++){ ?>
														<option <?php if(isset($each_match['user_prediction']->away_goals)){ if($each_match['user_prediction']->away_goals == $i){ ?> selected <?php }} ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
													<?php } ?>
												</select>
											</div>
										</p>
									</div>
									<div class="second-command">
										<p><?php echo $each_match['away_team_name']; ?></p>
										<img src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['away_team_country']); ?>.png" alt="<?php echo $each_match['away_team_name']; ?>">
									</div>
								</div>
								<div class="wrapper-right-side-item ">
									<?php if($each_match['scored'] == 1){ ?>
										<div class="resuldo-football">
											<p><?php echo $each_match['home_goals']; ?>-<?php echo $each_match['away_goals']; ?></p>
										</div>
									<?php }else{ ?>
										<div class="resuldo-football">
											<p></p>
										</div>
									<?php } ?>
									
									
									<?php if(!$each_match['point']->puntos_empleado_valor && $each_match['scored'] == 1){ ?>
										<div class="puntos-football">
											<p>0</p>
										</div>
									<?php }else{ ?>
										<div class="puntos-football">
											<p><?php echo $each_match['point']->puntos_empleado_valor; ?></p>
										</div>
									<?php } ?>
									<div class="timer-football" id="time-left-<?php echo $each_match['match_id']; ?>">
										<?php if($each_match['scored'] == 1 || $diffTotal < 24){
											echo 'Finalizado';
										}?>
									</div>
									<?php if($each_match['scored'] != 1){ ?>
										<script type="text/javascript">
											$(function(){
												$("#time-left-<?php echo $each_match['match_id']; ?>").countdowntimer({
													startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
													dateAndTime : "<?php echo @date('Y/m/d H:i:s', strtotime($each_match['kickoff'])); ?>",
													size : "lg",
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
					<button type="button"  id="match-prediction-save-btn">Pronosticar</button>
					<a data-toggle="modal" data-target="#myModal" href="#" style="display: none;" id="match-prediction-save-btn-pc"></a>
				</div>
			</div>
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="pronosticos-small-block" style="position: relative;">
			<?php include('inc/mobile-top-menu.php'); ?>
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
			<div class="slider-wrap-block4">
				<div class="table-small-wrapper"  style="position: relative;">
					<form id="match-frm-mobile" >
						<?php if(count($matchList) > 0){ foreach($matchList as $each_match){  ?>
							<div class="small-item-wrap">
								<div class="country-block">
									<img class='left-image-country' src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['home_team_country']); ?>.png" alt="<?php echo $each_match['home_team_name']; ?>">
									<p><?php echo $each_match['home_team_name']; ?> <span>VS</span> <?php echo $each_match['away_team_name']; ?></p>
									<img class='right-image-country' src="<?php echo base_url();  ?>img/equiposBanderas/<?php echo strtolower($each_match['away_team_country']); ?>.png" alt="<?php echo $each_match['away_team_name']; ?>">
									
									<div class="clearfix"></div>
									<div class="fech-block">
										<p>Comienza en:
											<span id="time-left-mobile-<?php echo $each_match['match_id']; ?>">
												<?php if($each_match['scored'] == 1 || $diffTotal < 24){
													echo 'Finalizado';
												} ?>
											</span>
										</p>
									</div>
								</div>
								<div class="time-small-block" style="display: none;">
									<?php
										$timeDifference = 0;
										$seconds 	= strtotime($each_match['kickoff']) - strtotime(@date("Y-m-d h:i:s"));
										$days    	= floor($seconds / 86400);
										$diffTotal  = $days*24;
									?>
									<div class="roundred leftRound <?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> time-expired-mobile <?php } ?>" >
											<select class="h_p_mb <?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> disabled-select-left <?php } ?>" <?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> disabled <?php } ?> id="home_predection_<?php echo $each_match['match_id']; ?>" name="home_predection[<?php echo $each_match['match_id']; ?>]">
												<option value="">X</option>
												<?php for($i=0;$i<=20;$i++){ ?>
													<option <?php if(isset($each_match['user_prediction']->home_goals)){ if($each_match['user_prediction']->home_goals == $i){ ?> selected <?php }} ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
												<?php } ?>
											</select>
									</div>
									<div class="middle-dash">-</div>
									<div class="roundred rightRound<?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> time-expired-mobile <?php } ?>" >
											<select  class="a_p_mb <?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> disabled-select-right <?php } ?>" <?php if($diffTotal < 24 || $each_match['scored'] == 1){ ?> disabled <?php } ?> id="away_predection_<?php echo $each_match['match_id']; ?>" name="away_predection[<?php echo $each_match['match_id']; ?>]">
												<option value="">X</option>
												<?php for($i=0;$i<=20;$i++){ ?>
													<option <?php if(isset($each_match['user_prediction']->away_goals)){ if($each_match['user_prediction']->away_goals == $i){ ?> selected <?php }} ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
												<?php } ?>
											</select>
									</div>
									<img src="<?php echo base_url();  ?>assets/img/timer.png" class="small-timer" alt="timer">
									
									<?php if($each_match['scored'] != 1){ ?>
										<script type="text/javascript">
											$(function(){
											  $("#time-left-mobile-<?php echo $each_match['match_id']; ?>").countdowntimer({
												startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
												dateAndTime : "<?php echo @date('Y/m/d H:i:s', strtotime($each_match['kickoff'])); ?>",
												size : "lg",
											  });
											});
										</script>
									<?php } ?>
								</div>
							</div>
						<?php }} ?>
						<h1>&nbsp;</h1>
						<div class="small-button-wrapper" style="position: fixed;width: 100%;z-index: 999;bottom: 0;background: #FFF;padding: 10px 0px;">
							<button type="button" id="match-prediction-save-btn-mobile">Pronosticar</button>
						</div>
					</form>
				</div>
				<?php include('right-bar-mobile.php'); ?>
			</div>
		</div>
		
		<a data-toggle="modal" data-target="#myModal" href="#" style="display: none;" id="match-prediction-save-btn-mb"></a>
		
		<div class="modal fade" id="myModal-mb" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<h3> ¡Tus pronósticos ya fueron guardados!</h3>
					<div class="button-wrapper-table">
						<button  data-dismiss="modal">&nbsp;&nbsp; Cerrar &nbsp;&nbsp; </button>
					</div>
				</div>
			</div>
		</div>
			
	</div>
	
	<?php include('inc/left-menu.php'); ?>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<h3> ¡Tus pronósticos ya fueron guardados!</h3>
				<div class="button-wrapper-table">
					<button  data-dismiss="modal">&nbsp;&nbsp; Cerrar &nbsp;&nbsp; </button>
				</div>
			</div>
		</div>
	</div>
	
	<a data-toggle="modal" data-target="#errModal" href="#" style="display: none;" id="err-prediction"></a>
	
	<div class="modal fade" id="errModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<h3>Sus pronósticos no se guardaron, verifique que insertó el pronóstico para los dos equipos.</h3>
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
		var home_predection = $("#match-frm").find('select[name^="home_predection"]')
              .map(function(){return $(this).val();}).get();
			  
		var err= 0;
			  
		var away_predection = $("#match-frm").find('select[name^="away_predection"]')
              .map(function(){return $(this).val();}).get();
			  
		$.each(home_predection, $.proxy(function(index, item) {
			if(item !== ''){
				if(away_predection[index] === ''){
					$('#err-prediction').trigger('click');
					err++;
					return false;
				}
			}
		}, this));
		
		$.each(away_predection, $.proxy(function(index, item) {
			
			if(item !== ''){
				if(home_predection[index] === ''){
					$('#err-prediction').trigger('click');
					err++;
					return false;
				}
			}
		}, this));
		
		if(err === 0){
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
					$('#err-prediction').trigger('click');
					err++;
					return false;
				}
			}
		});
		
		$.each(away_mb_prediction, $.proxy(function(index, item) {
			if(item !== ''){
				if(home_mb_prediction[index] == ''){
					$('#err-prediction').trigger('click');
					err++;
					return false;
				}
			}
		}, this));
		
		if(err === 0){
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
	
	
	var frmH = $('#match-frm-mobile').height();
	var winH = $(window).height();
	
	if(frmH < winH){
		$('.small-button-wrapper').removeAttr('style');
	}
	
	window.onscroll = function() {
		if(frmH > winH){
			var spaceBelow = $(window).height() - $('#match-frm-mobile:last-child')[0].getBoundingClientRect().bottom;
		
			if(spaceBelow > 66){
				$('.small-button-wrapper').hide();
			}else{
				$('.small-button-wrapper').show();
			}
		}else{
			
		}
	};
	
</script>

<style>
	.table-small-wrapper{
		overflow: scroll;
	}
	
	#errModal  .modal-backdrop, .modal-backdrop.fade.in{
		opacity:0.9;
		background:rgba(0,0,0,0.9)!important;
	}
	#errModal .modal-content{
		text-align: center;
	}
	#errModal .modal-content  h3{
		font-size:24px;
		font-family: DushaFifa;
		font-weight:400;
	}
	.time-expired{
		background-image: none;
	}
	
	.time-expired p span{
		color: #333;
	}
	
	.time-expired p{
		color: #333;
	}
	
	.time-expired-mobile{
		background: none !important;
		color: #333  !important;
	}
	
	#triviaModal  .modal-backdrop, .modal-backdrop.fade.in{
		opacity:0.9;
		background:rgba(0,0,0,0.9)!important;
	}
	#triviaModal .modal-content{
		text-align: center;
		padding: 10px;
	}
	#triviaModal .modal-content  h3{
		font-size:24px;
		font-family: DushaFifa;
		font-weight:400;
	}
	
	.button-wrapper-table>button {
		background-position: center;
	}
</style>

	<script src="<?php echo base_url(); ?>assets/js/dw_con_scroller.js" type="text/javascript"></script>
	<script type="text/javascript">
		if ( DYN_WEB.Scroll_Div.isSupported() ) {
			DYN_WEB.Event.domReady( function() {
				var wndo = new DYN_WEB.Scroll_Div('wn', 'lyr');
				wndo.makeSmoothAuto( {axis:'v', bRepeat:true, repeatId:'rpt', speed:25, bPauseResume:true} );
				
			});
		}
	</script>
	
	<script>
		$(document).ready(function(){
			$('.football-table').show();
			$('.time-small-block').show();
		});
	</script>
	
	
</html>