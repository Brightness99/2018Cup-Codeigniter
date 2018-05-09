<?php
/*
 * @author Shahrukh Khan
 * @website http://www.thesoftwareguy.in
 * @facebbok https://www.facebook.com/Thesoftwareguy7
 * @twitter https://twitter.com/thesoftwareguy7
 * @googleplus https://plus.google.com/+thesoftwareguyIn
 */
require_once("config.php");
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
    // not logged in send to login page
    redirect("index.php");
}

// set page title
$title = "Dashboard";

/*
// if the rights are not set then add them in the current session
if (!isset($_SESSION["access"])) {

    try {

        $sql = "SELECT mod_modulegroupcode, mod_modulegroupname FROM module "
                . " WHERE 1 GROUP BY `mod_modulegroupcode` "
                . " ORDER BY `mod_modulegrouporder` ASC, `mod_moduleorder` ASC  ";


        $stmt = $DB->prepare($sql);
        $stmt->execute();
        $commonModules = $stmt->fetchAll();

        $sql = "SELECT mod_modulegroupcode, mod_modulegroupname, mod_modulepagename,  mod_modulecode, mod_modulename FROM module "
                . " WHERE 1 "
                . " ORDER BY `mod_modulegrouporder` ASC, `mod_moduleorder` ASC  ";

        $stmt = $DB->prepare($sql);
        $stmt->execute();
        $allModules = $stmt->fetchAll();

        $sql = "SELECT rr_modulecode, rr_create,  rr_edit, rr_delete, rr_view FROM role_rights "
                . " WHERE  rr_rolecode = :rc "
                . " ORDER BY `rr_modulecode` ASC  ";

        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":rc", $_SESSION["rolecode"]);
        
        
        $stmt->execute();
        $userRights = $stmt->fetchAll();

        $_SESSION["access"] = set_rights($allModules, $userRights, $commonModules);

    } catch (Exception $ex) {

        echo $ex->getMessage();
    }
}
*/

$_SESSION["access"] = true;

include 'header.php';
?>
<div class="row">
    <div class="col-lg-9">
        <h3>Menu</h3>
        <div class="well well-sm">
			<ul>
                    <li>Manejo de Accesos
						<ul><a href="empresas.php">Empresas</a></ul>
						<ul><a href="empleados.php">Empleados</a></ul>
                    </li>
            </ul>
			<ul>
                    <li>Datos de Partidos
						<!--ul><a href="fases.php">Fases</a></ul-->
						<ul><a href="equipos.php">Equipos</a></ul>
						<ul><a href="partidos.php">Partidos</a></ul>
						<ul><a href="jugadores.php">Jugadores</a></ul>
						<!-- <ul><a href="goleadores.php">Goleadores</a></ul>  -->
						<!--  <ul><a href="equipoCampeon.php">Campeón</a></ul> -->
                    </li>
            </ul>
			<ul>
                    <li>Comunicación
						<ul><a href="mecanica.php">Mecánica de Juego</a></ul>
						<!-- <ul><a href="puntos.php">Puntos a otorgar</a></ul>  -->
                    </li>
            </ul>
			<ul>
                    <li><a href="trivias.php">Trivias</a></li>
            </ul>
			<!-- 
			<ul>
                    <li><a href="pronosticos.php">Pronósticos</a></li>
            </ul>
			<ul>
                    <li><a href="rankings.php">Ránkings</a></li>
            </ul>
            -->
            
        </div>

        <div style="height: 10px;">&nbsp;</div>
<!--
        <h3>Displaying menu as an individual button</h3>
        <div class="well well-sm">
            <?php foreach ($_SESSION["access"] as $key => $access) { ?>
                <div class="btn-group">
                    <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
                            <?php echo $access["top_menu_name"]; ?>
                            <span class="caret"></span>
                        </button>
                        <?php
                        echo '<ul class="dropdown-menu">';
                        foreach ($access as $k => $val) {
                            if ($k != "top_menu_name") {
                                echo '<li><a href="' . ($val["page_name"]) . '"><i class="fa fa-forward"></i> ' . $val["menu_name"] . '</a></li>';
                                ?>
                                <?php
                            }
                        }
                        echo '</ul>';
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
		
-->		
        <div style="height: 20px;">&nbsp;</div>
        <a href="logout.php"><button class="btn btn-lg btn-danger" type="button"><i class="fa fa-sign-out"></i>Logout</button></a>

    </div>

    <div class="col-lg-3">
       <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>