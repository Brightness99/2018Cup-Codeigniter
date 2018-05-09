<?php if($group_ranking){ foreach($group_ranking as $rank){ ?>
	<p><span><?php echo $rank['total_point']; ?></span>  <?php echo $rank['nombre'].' '.$rank['apellido']; ?></p>
<?php }}else{ ?>
	<span>No hay resultados</span>
<?php } ?>