<?php if($group_ranking){ foreach($group_ranking as $rank){ ?>
<div class="row">
							<div style="width: 40px;height: 40px;float: left;margin-right: 10px;margin-bottom: 10px;">
								<?php
									if($rank['imagen_perfil'] != ''){
										$p_image = base_url().'img/empleadosPerfil/'.$rank['imagen_perfil'];
									}else{
										$p_image = base_url().'assets/img/profileMenu.png';
									}
								?>
									
								
								<div class="pc-image" style="background-image: url(<?php echo $p_image; ?>);">
									
								</div>
								
							</div>
							<div  style="font-family: DushaFifa;width: 173px; min-height: 20px;float: left;margin-right: 10px;">
								<span><?php echo $rank['total_point']; ?></span>  <?php echo $rank['nombre'].' '.$rank['apellido']; ?>
							</div>
							<div class="clearfix"></div>
						</div>
						
						<style>
						.pc-image{
							width: 40px;
							height: 40px;
							background-position: center;
							background-size: cover;
							border-radius: 2px;
						}
						</style>
						
<?php }}else{ ?>
	<span>No hay resultados</span>
<?php } ?>