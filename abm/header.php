<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/" type="image/x-icon" />
        <meta name="author" content="Shahrukh Khan">
        <meta name="keywords" content="multi admin, mysql, php, demo, role, rights, user">

        <!-- <title><?php echo PROJECT_NAME ?> - www.thesoftwareguy.in</title>-->
        <!-- agregado para el paginador -->
        <style type="text/css">

        ul.paginador
        {

        }
        ul.paginador li
        {
            float:left;
        }
        ul.paginador li a
        {
            float:left;
        }
        ul.paginador li.paginador-active a, ul.paginador li a:hover
        {
            background-color: #337ab7;
            border-color: #337ab7;
            color:#FFFFFF;
        }
        ul.paginador li.paginador-disabled a, ul.paginador li.paginador-disabled a:hover
        {
            cursor:default;
        }
        ul.paginador li.paginador-current
        {
        }

        </style>

        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap-social.css" rel="stylesheet">
        <link href="bootstrap/css/font-awesome.css" rel="stylesheet">
        <link href="bootstrap/css/social-buttons.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">

<style media="screen">
tr.rowhighlight
{
  background-color:#f0f8ff;
}
</style>
    </head>
    <body>
        <div class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!--<a class="navbar-brand" href="http://www.thesoftwareguy.in" target="_blank"><span class="fa fa-home"></span> todos a la cancha</a>-->
                    <a class="navbar-brand" href="index.php" target="_self"><span class="fa fa-home"></span> todos a la cancha</a>
                </div>
                <?php
                if (!empty($_SESSION["user_id"])) {
                ?>
                <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1" >
                    <ul class="nav navbar-nav">
                        <li><a href="empresas.php">Empresas</a></li>
                        <li><a href="empleados.php">Empleados</a></li>
                        <li><a href="equipos.php">Equipos</a></li>
                        <li><a href="partidos.php">Partidos</a></li>
                        <li><a href="jugadores.php">Jugadores</a></li>
                        <li><a href="mecanica.php">Mecánica de Juego</a></li>
                        <li><a href="trivias.php">Trivias</a></li>
                    </ul>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="container mainbody">
            <div class="page-header">
                <h1 id="h1TituloOperacion"><?php echo $title; ?></h1>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="container-fluid">
                <?php if ($ERROR_MSG <> "") { ?>
                    <div class="col-lg-12">
                        <div class="alert alert-dismissable alert-<?php echo $ERROR_TYPE ?>">
                            <button data-dismiss="alert" class="close" type="button">x</button>
                            <p><?php echo utf8_encode($ERROR_MSG); ?></p>
                        </div>
                        <div style="height: 10px;">&nbsp;</div>
                    </div>
                <?php } ?>
