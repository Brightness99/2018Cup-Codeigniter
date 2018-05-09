<?php
	if($userDetails->imagen_perfil != ''){ 
		$p_image =  base_url().'img/empleadosPerfil/'.$userDetails->imagen_perfil;
	}else{
		$p_image =  base_url().'assets/img/profile-img.png';
	}
?>

<div class="left-menu-block">
	<div class="profile-block">
		<div class="user-profile-round-sm" style="background-image: url(<?php echo $p_image; ?>);">
			
		</div>
		<div>&nbsp;&nbsp;&nbsp;</div>
		<div>
			<a href="javascript:void(0)" data-uv-lightbox="classic_widget" data-uv-mode="full" data-uv-primary-color="#cc6d00" data-uv-link-color="#007dbf" data-uv-default-mode="support">
				<img src="<?php echo base_url(); ?>assets/img/question-mark6.png" alt="ayuda">
			</a>
		</div>
	</div>
	
	<div class="menu-open">
		<img src="<?php echo base_url(); ?>assets/img/menu-img.png" alt="menu-img">
		<p>Menú</p>
	</div>
	<div class="wrapper-menu-logo">
		<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="menu-logo">
	</div>
</div>