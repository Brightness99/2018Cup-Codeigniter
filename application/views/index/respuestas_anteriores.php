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
			<?php if($triviaList and count($user_trivia_answer) > 0){ $countTrivia = count($triviaList); ?>
				<?php foreach($triviaList as $i => $trivia){ $triviaCurrent = $i+1; ?>
					<?php
						$group = $trivia['trivia_details']->id_fase;

						if(in_array($group,array(1,2,3,4,5,6,7,8))){
							//$group_name = 'Fase';
							$group_name = 'Fase Grupos';
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
								<h2>Trivia:<span> <?php echo $group_name; ?> </span></h2>
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
												<label for="answer<?php echo $answer['id_respuesta']; ?>" <?php if($answer['respuesta_correcta'] == 1 && strtotime($trivia['trivia_details']->vencimiento) < strtotime(@date('Y-m-d h:i:s'))){ ?> class="text-success" <?php } ?>><?php echo $answer['respuesta']; ?> </label>
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
						<h3>Ningún registro fue encontrado</h3>
					</div>
				</div>
			<?php } ?>
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="pronosticos-small-block trivias-Small-Bann">
			<?php include('inc/mobile-top-menu.php'); ?>
			
			<?php if($triviaList){ ?>
				<?php foreach($triviaList as $trivia){ ?>
					<?php
						$group = $trivia['trivia_details']->id_fase;

						if(in_array($group,array(1,2,3,4,5,6,7,8))){
							//$group_name = 'Fase';
							$group_name = 'Fase Grupos';
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
						<h3>Trivia: <span><?php echo $group_name; ?> </span></h3>
						<div class="trivias-slider">
							<?php foreach($trivia['trivia_question'] as $key => $trivia_each){ $j = $key+12; ?>
								<div class="radioBox">
									<h4><?php echo $trivia_each['pregunta']; ?></h4>
									<?php foreach($trivia_each['answer'] as $k => $answer){ ?>
										<p>
											<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" name="radio-group<?php echo $j; ?>" disabled <?php if(in_array($answer['id_respuesta'],$user_trivia_answer)){ ?>checked<?php } ?>>
											<label for="answer<?php echo $answer['id_respuesta']; ?>"<?php if($answer['respuesta_correcta'] == 1 && strtotime($trivia['trivia_details']->vencimiento) < strtotime(@date('Y-m-d h:i:s'))){ ?> class="text-success" <?php } ?>><?php echo $answer['respuesta']; ?> </label>
										</p>
									<?php } ?>
								</div>
							<?php $j++; } ?>
						</div>
					</div>
				<?php } ?>	
			<?php }else{ ?>
				<div class="small-trivias">
					<h3>No se encontraron respuestas anteriores</h3>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php include('inc/left-menu.php'); ?>
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
.text-success{
	color: #37a000 !important;
}
.trivia-hide{
	display: none;
}
</style>
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



</html>