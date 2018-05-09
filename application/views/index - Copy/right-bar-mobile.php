
<div class="small-another-block" >
    <div class="wrapper-inner-me">
        <h3>Goleador  y Ganador del mundial</h3>
    </div>
    <div class="small-dropdown">
        <div class="dropdown-text">
        <p>¿Quién será el goleador de Russia 2018?</p>
        <span class="custom-dropdown">
            <select id="player-list-mb">
                <option>Seleccionar</option>
				<?php if($jugadores){ foreach($jugadores as $jugadore){ ?>
					<option <?php if($jugadores_answer == $jugadore['id_jugador']){ ?> selected <?php } ?> value="<?php echo $jugadore['id_jugador']; ?>"><?php echo $jugadore['nombre_jugador']; ?></option>  
				<?php }} ?>
            </select>
        </span>

    </div>
        <div class="dropdown-text  noborder">
            <p>¿Qué equipo ganará el mundial?</p>
            <span class="custom-dropdown">
                <select id="country-list-mb">
					<option>Seleccionar</option>
					<?php if($equipos){ foreach($equipos as $equipo){ ?>
						<option <?php if($equipos_answer == $equipo['team_id']){ ?> selected <?php } ?> value="<?php echo $equipo['team_id']; ?>"><?php echo $equipo['name']; ?></option>  
					<?php }} ?>
				</select>
            </span>

        </div>
    </div>
    
	<?php if($diffTotal > 24){ ?>
		<div class="wrapper-sec-slide">
			<button id="mb-guardar" data-toggle="modal" data-target="#mbGuardar">Enviar Respuesta</button>
		</div>
	<?php }else{ ?>
		<div class="wrapper-sec-slide">
			<button id="mb-guardar">Enviar Respuesta</button>
		</div>
	<?php } ?>
	
	<div class="modal fade" id="mbGuardar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

<style>
	#mbGuardar  .modal-backdrop, .modal-backdrop.fade.in{
		opacity:0.9;
		background:rgba(0,0,0,0.9)!important;
	}
	#mbGuardar .modal-content{
		text-align: center;
	}
	#mbGuardar .modal-content  h3{
		font-size:24px;
		font-family: DushaFifa;
		font-weight:400;
	}
</style>


<script>
	$('#mb-guardar').on('click', function(){
		var pList = $('#player-list-mb').val();
		var cList = $('#country-list-mb').val();
		
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