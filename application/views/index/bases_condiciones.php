<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Bases y Condiciones</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
</head>
<body class="condinPage">
	<div class="wrapper-pronosticos-block">
		<?php include('inc/top-left-menu.php'); ?>
		<div class="pronosticos-block ">
			<div class="hint-wrapper">
				<div class="topHint">
					<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>">
					<p id="topHint-p"> <?php echo $companyDetails->descripcion; ?> </p>
				</div>
			</div>
			<div class="condiciones-blockDelta">
				<h3>Bases  y Condiciones</h3>
				<div class="condiciones-inner">
					<p style="font-size: 25px;font-weight: 700;">Bases  y Condiciones</p>
					<br/>
					<p>
						<?php echo $condiciones2; ?>
					</p>
					<br/>
					<br/>
					<p style="font-size: 25px;font-weight: 700;">MECANICA del JUEGO</p>
					<br/>
					<p>
						<?php echo $condiciones1; ?>
					</p>
				</div>
				
			</div>
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="condiciones-small">
			<?php include('inc/mobile-top-menu.php'); ?>
			<div class="condiciones-blockDelta">
				<h3>Bases  y Condiciones</h3>
				<div class="condiciones-inner">
					<p style="font-size: 25px;font-weight: 700;">Bases  y Condiciones</p>
					<br/>
					<p>
						<?php echo $condiciones2; ?>
					</p>
					<br/>
					<br/>
					<p style="font-size: 25px;font-weight: 700;">MECANICA del JUEGO</p>
					<br/>
					<p>
						<?php echo $condiciones1; ?>
					</p>
				</div>
				
			</div>
		</div>
	</div>
	<?php include('inc/left-menu.php'); ?>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
		
</body>

</html>