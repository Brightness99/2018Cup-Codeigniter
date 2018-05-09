<div class="pronosticos-block">
	<div class="hint-wrapper">
		<div class="topHint">
			<img src="<?php echo $companyDetails->pc_logo; ?>" alt="<?php echo $companyDetails->empresa; ?>">
			<p> <?php echo $companyDetails->descripcion; ?> </p>
		</div>
	</div>
	
	<h2 class="recupepar">Cambiar mis datos de perfil</h2>
	
		<div class="wrapper-recupaper-form">
			<div class="inner-recupaper-form">
				<form id="edit-profile-frm" action="<?php echo base_url().$_SESSION['company']->empresa; ?>/change-profile" method="post" enctype="multipart/form-data">
					
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
						<input type="file" name="user_image">
					</div>
					<div class="group-form">
						<h4>Nueva Contraseña</h4>
						<input type="text" name="password" id="password">
					</div>
					<div class="group-form lastRecup">
						<h4>Repetí la nueva contraseña</h4>
						<input type="text" id="confirm-password">
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
		
		if(password !== '' && cpassword !== ''){
			if(password !== cpassword){
				$('.err-class').text('Password & confirm password are not same!');
				$('.err-class').fadeIn();
			}else{
				$('#edit-profile-frm').submit();
			}
		}else{
			return false;
		}
	});
</script>