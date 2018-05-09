<?php
/*
|Change with phases start date and end date
*/
$config_grupos_end_date 	= '';
$config_octavos_start_date 	= '2018-06-28';
$config_cuartos_start_date 	= '2018-07-03';
$config_semis_start_date 	= '2018-07-07';
$config_final_start_date 	= '2018-07-11';

$config_today_date			= date("Y-m-d");

if($config_today_date < $config_octavos_start_date)
{
	$pronosticos_url = '/pronosticos/fase';
} 
else if ($config_today_date < $config_cuartos_start_date && $config_today_date >= $config_octavos_start_date)
{
	$pronosticos_url = '/pronosticos/octavos';
}
else if ($config_today_date < $config_semis_start_date && $config_today_date >= $config_cuartos_start_date)
{
	$pronosticos_url = '/pronosticos/cuartos';
}
else if ($config_today_date < $config_final_start_date && $config_today_date >= $config_semis_start_date)
{
	$pronosticos_url = '/pronosticos/semi-final';
}
else if ($config_today_date >= $config_final_start_date)
{
	$pronosticos_url = '/pronosticos/final';
} else 
{
	$pronosticos_url = '/pronosticos/fase';
}

?>
<div class="menu-overlay"></div>
<div class="menu-delta menu-big-delta">
	<div class="header-part" style="background-image: url('http://todosalacancha.sietepuentes.com/assets/img/menuBack.png')">
		<div class="user-profile-round-lg" style="background-image: url(<?php echo $p_image; ?>);" >
				
		</div>
		<p><?php echo $userDetails->nombre.' '.$userDetails->apellido; ?></p>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<div class="main-part" style="background-image: url('http://todosalacancha.sietepuentes.com/assets/img/menuBack.png'); background-position: -5px -400px;padding-top: 10px;">
		<ul>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/edit-profile'; ?>">Perfil</a></li>
			<li><a <?php if($_SESSION['company']->is_trivia == 1 && $has_trivia == true && $trivia_user_record == false){ ?> data-toggle="modal" data-target="#triviaModal_q" data-backdrop="static" data-keyboard="false" href="#"  <?php }else{ ?> href="<?php echo base_url().$_SESSION['company']->url.$pronosticos_url; ?>"
  <?php } ?>>Pronósticos <?php echo "";?></a></li>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/ranking'; ?>">Ranking</a></li>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/premios'; ?>">Premios</a></li>
			<?php if($_SESSION['company']->is_trivia == 1){ ?>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/trivias'; ?>">Trivias</a></li>
			<?php } ?>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/bases-y-condiciones'; ?>">Bases y Condiciones</a></li>
			<!--<li><a href="javascript:void(0)" data-uv-lightbox="classic_widget" data-uv-mode="full" data-uv-primary-color="#cc6d00" data-uv-link-color="#007dbf" data-uv-default-mode="support" data-uv-forum-id="34190">Necesito Ayuda</a>-->
</li>
		</ul>
	</div>
	<div class="logo-part">
		<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="left-football-logo">
		<a href="<?php echo base_url().$_SESSION['company']->url.'/logout'; ?>">Cerrar Sesion</a>
	</div>
</div>

<div class="menu-delta menu-small-delta">
	<div class="header-part" style="background-image: url('http://todosalacancha.sietepuentes.com/assets/img/menuBack.png')">
		<div class="user-profile-round-lg" style="background-image: url(<?php echo $p_image; ?>);">
			
		</div>
		<p><?php echo $userDetails->nombre.' '.$userDetails->apellido; ?></p>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<div class="main-part" style="background-image: url('http://todosalacancha.sietepuentes.com/assets/img/menuBack.png'); background-position: -5px -400px;padding-top: 10px;">
		<ul>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/edit-profile'; ?>">Perfil</a></li>
			<li><a <?php if($_SESSION['company']->is_trivia == 1 && $has_trivia == true && $trivia_user_record == false){ ?> data-toggle="modal" data-target="#triviaModal_q" href="#" data-backdrop="static" data-keyboard="false" <?php }else{ ?> href="<?php echo base_url().$_SESSION['company']->url.$pronosticos_url; ?>"
  <?php } ?>>Pronósticos</a></li>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/ranking'; ?>">Ranking</a></li>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/premios'; ?>">Premios</a></li>
			<?php if($_SESSION['company']->is_trivia == 1){ ?>
				<li><a href="<?php echo base_url().$_SESSION['company']->url.'/trivias'; ?>">Trivias</a></li>
			<?php } ?>
			<li><a href="<?php echo base_url().$_SESSION['company']->url.'/bases-y-condiciones'; ?>">Bases y Condiciones</a></li>
			<!--<li><a href="javascript:void(0)" data-uv-lightbox="classic_widget" data-uv-mode="full" data-uv-primary-color="#cc6d00" data-uv-link-color="#007dbf" data-uv-default-mode="support" data-uv-forum-id="34190">Necesito Ayuda</a>-->
</li>
		</ul>
		<div class="wrapper-session">
			<a class="session" href="<?php echo base_url().$_SESSION['company']->url.'/logout'; ?>">Cerrar Sesion</a>
		</div>
	</div>
</div>






<?php if($_SESSION['company']->is_trivia == 1 && $has_trivia == true && $trivia_user_record == false){ ?>	
	<div class="modal fade" id="triviaModal_q" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<h3>¡Podés contestar la trivia y tenés chances de duplicar tus puntos!</h3>
				<div class="button-wrapper-table">
					<button id="can-predict-trivia">&nbsp;Ir a la trivia &nbsp; </button>  <button  id="can-predict-prediction">&nbsp;&nbsp; Cerrar &nbsp;&nbsp; </button>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		$('#can-predict-trivia').on('click', function(){
			window.location.href = "<?php echo base_url().$_SESSION['logged_in_company'].'/trivias'; ?>";
		});
		
		$('#can-predict-prediction').on('click', function(){
			window.location.href = "<?php echo base_url().$_SESSION['logged_in_company'].$pronosticos_url; ?>";
		});
		
	</script>
	
<?php } ?>


<style>
	#triviaModal_q  .modal-backdrop, .modal-backdrop.fade.in{
		opacity:0.9;
		background:rgba(0,0,0,0.9)!important;
	}
	#triviaModal_q .modal-content{
		text-align: center;
		padding: 10px;
	}
	#triviaModal_q .modal-content  h3{
		font-size:24px;
		font-family: DushaFifa;
		font-weight:400;
	}
</style>



