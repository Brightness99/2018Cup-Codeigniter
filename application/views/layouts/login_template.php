<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($title)){ echo $title; }else{ echo 'Login'; } ?></title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- UserVoice JavaScript SDK (only needed once on a page) -->
	<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/wqytnQKrHHYWBd6ezBPyw.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>

	<!-- A tab to launch the Classic Widget -->
	<!--
	<script>
	UserVoice = window.UserVoice || [];
	UserVoice.push(['showTab', 'classic_widget', {
	  mode: 'support',
	  primary_color: '#cc6d00',
	  link_color: '#ffffff',
	  tab_label: 'Tenes dudas o algun problema? Consultanos!',
	  tab_color: '#cc6d00',
	  tab_position: 'middle-right',
	  tab_inverted: false
	}]);
	</script>		-->
</head>
<body class="login-page" id="indexPage">
	
	<div class="login-block">
		<div class="container">
			<div class="col-lg-5 col-md-6 col-sm-12">
				<div class="small-wrapp">
					<img src="<?php echo base_url(); ?>assets/img/right-footbal-logo.png" alt="logo">
				</div>
				<div class="form-outer">
					<div class="form-inner-block">	
						<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>" class="image-nestle">
						<form id="login-frm" class="login-Page" onsubmit="return false;">
							<div class="wrapper-input">
								<div class="first-inp">
									<i class="fas fa-user"></i>
								</div>
								<input class="form-input userName-inpt" placeholder="Username" type="text" id="user_name" name="user_name" autocomplete="off">
							</div>
							<div class="wrapper-input">
								<div class="first-inp">
									<i class="fas fa-lock"></i>
								</div>
								<input class="form-input password-inpt" placeholder="********" type="password" id="user_pass" name="user_pass" autocomplete="off">
							</div>
							<div class="bottom-button">
								<input type="submit" value="Ingresar" id="login-btn">
							</div>
							<div class="text-center" id="login-err" style="display: none;color: #FFF;"></div>
							<div>
								<a href="javascript:void(0)" data-uv-lightbox="classic_widget" data-uv-mode="full" data-uv-primary-color="#cc6d00" data-uv-link-color="#007dbf" data-uv-default-mode="support">
									<img src="<?php echo base_url(); ?>assets/img/question-mark6.png" alt="ayuda">
								</a>
							</div>
						</form>

					</div> 
					<div class="wrapper-left-logo">
						<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="logo">
					</div>
				</div>
			</div>
			<div class="col-lg-7 col-md-6 col-sm-12 right-side">
				<div class="big-logo-wrapper">
					<img src="<?php echo base_url(); ?>assets/img/right-footbal-logo.png" alt="big-logo">
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" id="user_id">
	
	<a data-toggle="modal" data-target="#indexModal" id="login-condition-modal" style="display: none;"></a>
	
	<div class="modal fade" id="indexModal" tabindex="-1" role="dialog" aria-labelledby="indexModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="terminos">
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
				<div class="wrapper-checkbox">
					<label class="container">he leido los terminos y condiciones y acepto
						<input type="checkbox" checked="checked" id="condition_checkbox" value="1">
						<span class="checkmark"></span>
					</label>
				</div>
				<div class="buttonMainModal">
					<button id="condition-accept-btn">Ingresar</button>
				</div>
				<img src="<?php echo base_url(); ?>assets/img/wolf.png" alt="wolf">
			</div>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>assets/js/login.js"></script>
</body>
<input type="hidden" id="current-company" value="<?php echo $_SESSION['company']->url; ?>">
</html>