<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($title)){ echo $title; }else{ echo 'Title'; } ?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.countdownTimer.min.js"></script> 
</head>
<body class="imageSlider qweDSlider">
	<?php
		if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SESSION['current_device'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SESSION['current_device'],0,4)))
        { 
	?>
		<div class="main-img-block">
			<div class="left-menu-block">
				<div class="menu-open">
					<img src="<?php echo base_url(); ?>assets/img/menu-img.png" alt="menu-img">
					<p>Menú</p>
					
				</div>
				<div class="wrapper-menu-logo">
						<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="menu-logo">
					</div>
			</div>
			
			<?php echo $contents; ?>
		</div>
	<?php } ?>
	<?php
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SESSION['current_device'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SESSION['current_device'],0,4)))
        { 
	?>
		<div class="pronosticos-small-block">
			<div class="mobile-menu-pronosticos">
				<div class="left-side-mobile">
					<img src="<?php echo base_url(); ?>assets/img/mobileBurger.png" alt="mobileBurger">
					<p>Menú</p>
				</div>
				<div class="right-side-mobile">
					<p>Hola , <?php echo $nombre; ?></p><img src="<?php echo base_url(); ?>assets/img/mobileProf.png" alt="">
				</div>
			</div>
			<?php echo $contents; ?>
		</div>
	<?php } ?>
	
	<div class="menu-delta menu-big-delta">
		<div class="header-part">
			<?php if($imagen_perfil != ''){ ?>
				<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $imagen_perfil; ?>" alt="profileMenu">
			<?php }else{ ?>
				<img src="<?php echo base_url(); ?>assets/img/profileMenu.png" alt="profileMenu">
			<?php } ?>
			<p><?php echo $nombre.' '.$apellido; ?></p>
		</div>
		<div class="main-part">
			<ul>
				<li><a href="<?php echo base_url().$_SESSION['company']->empresa.'/edit-profile'; ?>">Perfil</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->empresa.'/pronosticos/fase'; ?>">Pronósticos</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->empresa.'/ranking'; ?>">Ranking</a></li>
				<li><a href="">Premios</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->empresa.'/trivias'; ?>">Trivias</a></li>
				<li><a href="">Bases y Condiciones</a></li>
			</ul>
		</div>
		<div class="logo-part">
			<img src="<?php echo base_url(); ?>assets/img/left-football-logo.png" alt="left-football-logo">
			<a href="<?php echo base_url().$_SESSION['company']->empresa.'/logout'; ?>">Cerrar Sesion</a>
		</div>
	</div>

	<div class="menu-overlay menuSLider"></div>
	<div class="menu-delta menu-small-delta">
		<div class="header-part">
			<?php if($imagen_perfil != ''){ ?>
				<img src="<?php echo base_url(); ?>img/empleadosPerfil/<?php echo $imagen_perfil; ?>" alt="profileMenu">
			<?php }else{ ?>
				<img src="<?php echo base_url(); ?>assets/img/profileMenu.png" alt="profileMenu">
			<?php } ?>
			<p><?php echo $nombre.' '.$apellido; ?></p>
			
		</div>
		<div class="main-part">
			<ul>
				<li><a href="<?php echo base_url().$_SESSION['company']->empresa.'/edit-profile'; ?>">Perfil</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->empresa.'/pronosticos/fase'; ?>">Pronósticos</a></li>
				<li><a href="<?php echo base_url().$_SESSION['company']->empresa.'/ranking'; ?>">Ranking</a></li>
				<li><a href="">Premios</a></li>
				<li><a href="">Trivias</a></li>
				<li><a href="">Bases y Condiciones</a></li>
			</ul>
			<div class="wrapper-session">
				<a href="<?php echo base_url().$_SESSION['company']->empresa.'/logout'; ?>">Cerrar Sesion</a>
			</div>
		</div>
	</div>
		

	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slick.min.js"></script> 
	<script src="<?php echo base_url(); ?>assets/js/js.js"></script>
	<input type="hidden" id="current-company" value="<?php echo $_SESSION['company']->empresa; ?>">
</body>

<script>
	var company  = $('#current-company').val();
</script>
</html>