<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Premios</title>
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
<body id="traviasPage" class="deltaPageOmega">
		<!-- Button trigger modal -->
<!-- Button trigger modal -->

<!-- Modal -->

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
			<div class="premios-block">
				<h2>Premios</h2>
				
				<?php
					$img_url = $premios_pc->nombre_archivo;
				?>
				
				
				<div class="image-premBlock" style="background-image: url('<?php echo base_url(); ?>img/<?php echo $_SESSION['logged_in_company'].'/'.$img_url; ?>');">
					<img src="<?php echo base_url(); ?>assets/img/footballLent.png" class="lent" alt="footballLent">
				</div>
			</div>
			
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="pronosticos-small-block smallPremiousBlock">
			<?php include('inc/mobile-top-menu.php'); ?>
			<div class="bannerBlock">
				<h2>Premios</h2>
				<?php
					$img_url = $premios_mb->nombre_archivo;
				?>
				<div class="innerBannerBlock" style="background-image: url('<?php echo base_url(); ?>img/<?php echo $_SESSION['logged_in_company'].'/'.$img_url; ?>');">
					<img src="<?php echo base_url(); ?>assets/img/footballLent.png" class="lent" alt="footballLent">
				</div>
			</div>
		</div>
	</div>
	<?php include('inc/left-menu.php'); ?>

	
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
</body>
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