<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Bienvenida</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.0.3.js.download"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/wqytnQKrHHYWBd6ezBPyw.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>

</head>
<body class="imageSlider qweDSlider">
	<div class="main-img-block">
		<?php include('inc/top-left-menu.php'); ?>
		<?php if(count($companyDetails->pc_slider) > 0){ ?>
			<div class="image-slider-block">
				<div class="image-inner-slider">
					<?php foreach($companyDetails->pc_slider as $image){ ?>
						<div class="image-inner-wrapper" style="background:url('<?php echo $image; ?>'); "></div>
					<?php } ?>
				</div>
			</div>
		<?php }else{ ?>
			<div class="image-slider-block">
				<div class="image-inner-slider">
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.jpg'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.jpg'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.jpg'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.jpg'); "></div>
					<div class="image-inner-wrapper" style="background:url('<?php echo base_url(); ?>assets/img/footballSliderEsp.jpg'); "></div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="pronosticos-small-block">
		<?php include('inc/mobile-top-menu.php'); ?>
		<?php if(count($companyDetails->mobile_slider) > 0){ ?>
			<div class="small-image-slider">
				<?php foreach($companyDetails->mobile_slider as $image){ ?>
					<div class="small-image-inner" style="background:url('<?php echo $image; ?>'); "></div>
				<?php } ?>
			</div>
		<?php }else{ ?>
			<div class="small-image-slider">
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
				<div class="small-image-inner" style="background:url('<?php echo base_url(); ?>assets/img/small1.jpg');"></div>
			</div>
		<?php } ?>
	</div>
	
	<?php include('inc/left-menu.php'); ?>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
	
</body>
</html>
