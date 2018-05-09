<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Perfil</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/css/bootstrap-modal.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-2.0.3.js.download"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.countdownTimer.min.js.download"></script>
</head>


<body  class="recupPAge">
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
			
			<h2 class="recupepar">Cambiar mis datos de perfil</h2>
			<div class="wrapper-recupaper-form">
				<div class="inner-recupaper-form">
					<form id="edit-profile-frm" action="<?php echo base_url().$_SESSION['company']->url; ?>/change-profile" method="post" enctype="multipart/form-data">
						
						<?php
						if($this->session->flashdata('item')) {
							$message = $this->session->flashdata('item');
						?>
						<div class="group-form">
							<h4 style="color: green;"><?php echo $message; ?></h4>
						</div>
						
						<?php
						}
						?>
						<div class="group-form">
							<h4>Cambiar mi imagen de perfil</h4>
							<input type="file" id="user_image" name="user_image" accept="image/*">
						</div>
						<div class="group-form">
							<h4>Nueva Contraseña</h4>
							<input type="password" name="password" id="password">
						</div>
						<div class="group-form lastRecup">
							<h4>Repetí la nueva contraseña</h4>
							<input type="password" name="cpassword" id="confirm-password">
						</div>
						<div class="group-wrapp-form">
							<button type="button" id="edit-profile-btn">Editar mi perfil</button>
						</div>
						<div class="err-class"></div>
					</form>
				</div>
			</div>
			
			<?php include('right-side-bar.php'); ?>
		</div>
		<div class="condiciones-small">
			<?php include('inc/mobile-top-menu.php'); ?>
			<div class="small-INN">
				<h2>Olvido Contraseña</h2>
				<form id="edit-profile-frm-mobile" action="<?php echo base_url().$_SESSION['company']->url; ?>/change-profile" method="post" enctype="multipart/form-data">
						
					<?php
						if($this->session->flashdata('item')) {
							$message = $this->session->flashdata('item');
					?>
						<div class="group-form">
							<h4 style="color: green;"><?php echo $message; ?></h4>
						</div>
					<?php
						}
					?>
					<div class="small-form-group">
						<h4>Cambiar mi imagen de perfil</h4>
						<input type="file" name="user_image" style="float: left;" accept="image/*">
					</div>
					<div class="small-form-group">
						<h4>Nueva Contraseña</h4>
						<input type="password" name="password" id="password-mobile">
					</div>
					<div class="small-form-group lastRecup">
						<h4>Repetí la nueva contraseña</h4>
						<input type="password" name="cpassword" id="confirm-password-mobile">
					</div>
					<div class="group-wrapp-form">
						<button type="button" id="edit-profile-btn-mobile">Editar mi perfil</button>
					</div>
					<div class="err-class"></div>
				</form>
			</div>
		</div>
	</div>
	<?php include('inc/left-menu.php'); ?>

  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
</body>
<style>
	.err-class{
		color: red;
		text-align: center;
		width: 100%;
		display: none;
		margin: 10px 0px;
	}
</style>

<script>
	$('#edit-profile-btn').on('click', function(){
		var password = $.trim($('#password').val());
		var cpassword = $.trim($('#confirm-password').val());
		
		var err = 0;
		
		if(password !== '' && cpassword !== ''){
			if(password !== cpassword){
				$('.err-class').text(' La contraseña debe coincidir');
				$('.err-class').fadeIn();
				err++;
				return false;
			}
		}else if(password !== '' && cpassword == ''){
			if(password !== cpassword){
				$('.err-class').text(' La contraseña debe coincidir');
				$('.err-class').fadeIn();
				err++;
				return false;
			}
		}else if(password == '' && cpassword !== ''){
			if(password !== cpassword){
				$('.err-class').text('Password & confirm password are not same!');
				$('.err-class').fadeIn();
				err++;
				return false;
			}
		}
		
		if(err == 0){
			$('#edit-profile-frm').submit();
		}
		
	});
	
	$('#edit-profile-btn-mobile').on('click', function(){
		var password = $.trim($('#password-mobile').val());
		var cpassword = $.trim($('#confirm-password-mobile').val());
		
		var err = 0;
		
		if(password !== '' && cpassword !== ''){
			if(password !== cpassword){
				$('.err-class').text('Password & confirm password are not same!');
				$('.err-class').fadeIn();
				err++;
				return false;
			}
		}else if(password !== '' && cpassword == ''){
			if(password !== cpassword){
				$('.err-class').text('Password & confirm password are not same!');
				$('.err-class').fadeIn();
				err++;
				return false;
			}
		}else if(password == '' && cpassword !== ''){
			if(password !== cpassword){
				$('.err-class').text('Password & confirm password are not same!');
				$('.err-class').fadeIn();
				err++;
				return false;
			}
		}
		
		if(err == 0){
			$('#edit-profile-frm-mobile').submit();
		}
	});
	
	$('#user_image').bind('change', function() {
		var size = this.files[0].size/1024/1024;
		var max_size = <?php echo $this->config->item('profile_max_size_img') ?>;
		if(size > max_size){
			alert('Image size should be less than '+max_size+'MB');
			var $el = $('#user_image');
			$el.wrap('<form>').closest('form').get(0).reset();
			$el.unwrap();
		}
	});
</script>
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