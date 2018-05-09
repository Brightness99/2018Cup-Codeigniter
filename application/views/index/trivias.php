<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Trivias</title>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.0.3.js.download"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.countdownTimer.min.js.download"></script>
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
			
			<div class="trivias-inner-block">
				<?php if($user_trivia_answer == null){ if($trivia){  ?>
					<div class="wrapper-header-trivias">
						<h2>Trivia:<span> <?php if($group == "Fase"){echo "Fase Grupos";}else{echo $group;} ?></span></h2>
						<div class="timer-trivias">
							<img src="<?php echo base_url(); ?>assets/img/timer.png" alt="timer">
							<p id="trivias-timer-new"></p>
							
							
							<script type="text/javascript">
								$(function(){
									$("#trivias-timer-new").countdowntimer({
										startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
										dateAndTime : "<?php echo @date('Y/m/d H:i:s', strtotime($trivia[0]['vencimiento'])); ?>",
										size : "lg",
									});
								});
							</script>
							
							
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
											<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" name="radio-group<?php echo $j; ?>">
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
				<?php }else{ echo "<h2 style='text-align: center;'>No hay trivias para contestar</h2>"; }}else{ ?>
					<div class="wrapper-header-trivias">
						<h2>Trivia:<span>  <?php if($group == "Fase"){echo "Fase Grupos";}else{echo $group;} ?></span></h2>
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
											<input type="radio" id="answer-mobile-<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" disabled name="radio-group<?php echo $j; ?>" <?php if(in_array($answer['id_respuesta'],$user_trivia_answer)){ ?>checked<?php } ?>>
											<label for="answer-mobile-<?php echo $answer['id_respuesta']; ?>" <?php if($answer['respuesta_correcta'] == 1 && (strtotime($trivia_each['vencimiento']) < strtotime(@date('Y-m-d h:i:s')))){ ?> class="text-success" <?php } ?> ><?php echo $answer['respuesta']; ?> </label>
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
			<?php include('inc/mobile-top-menu.php'); ?>
			
			<?php if($user_trivia_answer == null){ if($trivia){ ?>
				<div class="small-trivias">
					<h3>Trivia: <span> <?php if($group == "Fase"){echo "Fase Grupos";}else{echo $group;} ?> </span></h3>
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
											<input type="radio" id="answer-mobile-<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" name="radio-group<?php echo $j; ?>" >
											<label for="answer-mobile-<?php echo $answer['id_respuesta']; ?>"><?php echo $answer['respuesta']; ?> </label>
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
					<p id="small-timer-trivias-new"></p>
					<script type="text/javascript">
						$(function(){
							$("#small-timer-trivias-new").countdowntimer({
								startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
								dateAndTime : "<?php echo @date('Y/m/d H:i:s', strtotime($trivia[0]['vencimiento'])); ?>",
								size : "lg",
							});
						});
					</script>
				</div>
				<div class="small-trivias-button">
					<button data-toggle='modal' id="mobile-form-btn" data-target="#exampleModalCenter">Responder</button>
				</div>
			<?php }else{ echo "<h2 style='text-align: center;'>No hay trivias para contestar</h2>"; }}else{ ?>
				<div class="small-trivias">
					<h3>Trivia: <span> <?php if($group == "Fase"){echo "Fase Grupos";}else{echo $group;} ?></span></h3>
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
										<input type="radio" id="answer<?php echo $answer['id_respuesta']; ?>" value="<?php echo $answer['id_respuesta']; ?>" disabled="disabled" name="radio-group<?php echo $j; ?>" <?php if(in_array($answer['id_respuesta'],$user_trivia_answer)){ ?>checked<?php } ?>>
										<label for="answer<?php echo $answer['id_respuesta']; ?>" <?php if($answer['respuesta_correcta'] == 1 && (strtotime($trivia_each['vencimiento']) < strtotime(@date('Y-m-d h:i:s')))){ ?> class="text-success" <?php } ?>><?php echo $answer['respuesta']; ?> </label>
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
	<?php include('inc/left-menu.php'); ?>
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
		var selValue1 = $('#pc-trivia-form').find('input[name=radio-group1]:checked').val();
		var selValue2 = $('#pc-trivia-form').find('input[name=radio-group2]:checked').val();
		var selValue3 = $('#pc-trivia-form').find('input[name=radio-group3]:checked').val();		
		
		if(selValue1 == undefined || selValue2 == undefined || selValue3 == undefined){
			alert('Debe completar las tres preguntas');
			return false;
		}
		
		$.ajax({
			url: '<?php echo base_url().$_SESSION['company']->url; ?>/save-trivia-record',
			type: "POST",
			data: $('#pc-trivia-form').serializeArray(),
			dataType: "json",
			success: function(response){
				setTimeout(function(){ location.reload(); }, 3000);	
			}
		});	
	});
	
	$('#mobile-form-btn').on('click', function(){
		var selValue1 = $('#mobile-trivia-form').find('input[name=radio-group5]:checked').val();
		var selValue2 = $('#mobile-trivia-form').find('input[name=radio-group6]:checked').val();
		var selValue3 = $('#mobile-trivia-form').find('input[name=radio-group7]:checked').val();
				
		if(selValue1 == undefined || selValue2 == undefined || selValue3 == undefined){
			alert('Debe completar las tres preguntas');
			return false;
		}
		
		$.ajax({
			url: '<?php echo base_url().$_SESSION['company']->url; ?>/save-trivia-record',
			type: "POST",
			data: $('#mobile-trivia-form').serializeArray(),
			dataType: "json",
			success: function(response){
				setTimeout(function(){ location.reload(); }, 3000);		
			}
		});	
	});
	
	
</script>
<style>
	.slick-slide {
outline: none !important;
}

.text-success{
	color: #37a000 !important;
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