
<?php if($group_ranking){ foreach($group_ranking as $rank){ ?>
	<div class="person-info">
		<div class="wrapplinger-profile-image">
			<?php if($rank['imagen_perfil'] != ''){ ?>
				<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $rank['imagen_perfil']; ?>" alt="profileMenu">
			<?php }else{ ?>
				<img src="<?php echo base_url(); ?>assets/img/profileGrey.png" alt="profileMenu">
			<?php } ?>
		</div>
		<div class="wrapplinger-text-profile">
			<p><?php echo $rank['nombre'].' '.$rank['apellido']; ?></p>
			<span><?php echo $rank['total_point']; ?> pts</span>
		</div>
	</div>
<?php }}else{ ?>
	<span>No hay resultados</span>
<?php } ?>