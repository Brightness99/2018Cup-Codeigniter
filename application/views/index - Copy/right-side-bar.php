<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jQuery.countdownTimer.js"></script>

<?php
	$timeDifference = 0;
	
	$start_date = date_create($first_match_time->kickoff);
	$end_start 	= date_create(@date("Y-m-d h:i:s"));
	$diff  		= date_diff( $start_date, $end_start );
	
	$diffTotal = $diff->y*365*24 + $diff->m*30*24 + $diff->h;
?>

<?php
	if($jugadores_answer){
		$jugadores_answer = $jugadores_answer->jugador_id;
	}else{
		$jugadores_answer = null;
	}
	
	if($equipos_answer){
		$equipos_answer = $equipos_answer->equipo_id;
	}else{
		$equipos_answer = null;
	}
?>


<div class="timer-right-block">
    <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="logo">
    <div class="timer-inner-block">
        <h5>Quedan</h5>
        <div class="timerConsist">
            <img src="<?php echo base_url(); ?>assets/img/big-timer.png" alt="timer">
			<?php if($diffTotal > 24){ ?>
				<p id="timer-pc"></p>
				<script type="text/javascript">
					$(function(){
					  $("#timer-pc").countdowntimer({
						dateAndTime : "<?php echo $first_match_time->kickoff; ?>",
						size : "lg"
					  });
					});
				</script>
			<?php }else{ ?>
				<p id="timer-pc">Expired</p>
			<?php } ?>
        </div>
        <p class="timernext">para el mundial</p>
    </div>
    <div class="dropdown-text">
        <p>¿Quien será el <br> proximo goleador?</p>
        <span class="custom-dropdown">
            <select id="player-list-pc">
                <option>Seleccionar</option>
				<?php if($jugadores){ foreach($jugadores as $jugadore){ ?>
					<option <?php if($jugadores_answer == $jugadore['id_jugador']){ ?> selected <?php } ?> value="<?php echo $jugadore['id_jugador']; ?>"><?php echo $jugadore['nombre_jugador']; ?></option>  
				<?php }} ?>
            </select>
        </span>
    </div>
    <div class="dropdown-text">
        <p>Pronostica quien <br> ganará el mundial</p>
        <span class="custom-dropdown">
            <select id="country-list-pc">
                <option>Seleccionar</option>
                <?php if($equipos){ foreach($equipos as $equipo){ ?>
					<option <?php if($equipos_answer == $equipo['team_id']){ ?> selected <?php } ?> value="<?php echo $equipo['team_id']; ?>"><?php echo $equipo['name']; ?></option>  
				<?php }} ?>
            </select>
        </span>
    </div>
	<?php if($diffTotal > 24){ ?>
		<div class="more-buttonRight">
			<button id="pc-guardar" data-toggle="modal" data-target="#myGuardar">Guardar</button>
		</div>
	<?php }else{ ?>
		<div class="more-buttonRight">
			<button id="pc-guardar" disabled="disabled" >Guardar</button>
		</div>
	<?php } ?>
</div>

<div class="modal fade" id="myGuardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<h3>Tus pronósticos fueron guardados!</h3>
			<div class="button-wrapper-table">
				<button  data-dismiss="modal">&nbsp;&nbsp; Cerrar &nbsp;&nbsp; </button>
			</div>
		</div>
	</div>
</div>

<script>
	$('#pc-guardar').on('click', function(){
		var pList = $('#player-list-pc').val();
		var cList = $('#country-list-pc').val();
		
		if(pList !== '' || cList !== ''){
			$.ajax({
				url: '<?php echo base_url().$_SESSION['company']->url; ?>/save-guardar',
				type: "POST",
				data: {pList:pList,cList:cList},
				dataType: "json",
				success: function(response){
					setTimeout(function(){ location.reload(); }, 3000);
				}
			});	
		}		
	});
</script>

<img src="<?php echo base_url(); ?>assets/img/right-corner-image.png" alt="corner" class="corner-image">

<style>
	#myGuardar  .modal-backdrop, .modal-backdrop.fade.in{
		opacity:0.9;
		background:rgba(0,0,0,0.9)!important;
	}
	#myGuardar .modal-content{
		text-align: center;
	}
	#myGuardar .modal-content  h3{
		font-size:24px;
		font-family: DushaFifa;
		font-weight:400;
	}
</style>