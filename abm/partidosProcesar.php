<?php
/*
 * @author Roberto Murer
 * @website 
 * @twitter https://twitter.com/robertdm
 */

require_once("config.php");
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
    // not logged in send to login page
    redirect("index.php");
}

// set page title
$title = "Procesar Partido";

include 'header.php';

$action = $_GET['a'];
global $ONLINE;
?>
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>
            <div class=" table-responsive">
                <?php 
                if (isset($_SESSION["access"])) 
                {   
                    $id_partido = $_POST['id_partido'];
                    $sede = $_POST["sede"];
                    $inicio = $_POST["inicio"];
                    $fase = $_POST["fase"];
                    $id_equipo1 = $_POST["id_equipo1"];
                    $goles1 = $_POST["goles1"];
                    $id_equipo2 = $_POST["id_equipo2"];
                    $goles2 = $_POST["goles2"];
                    $status = $_POST["status"];

                    try 
                    {
                        switch($action) {
                            case "agregar":
                                $mensaje = "Partido Agregado";
                                $sql = ""; 
                                break;

                            case "editar":
                                $mensaje = "Partido Modificado";
                                if (!$ONLINE)
                                    $sql = "
                                        UPDATE partidos 
                                        SET
                                            venue_id     = $sede,
                                            stage_id     = $fase,
                                            home_team_id = '$id_equipo1', 
                                            away_team_id = '$id_equipo2',
                                            home_goals   = '$goles1',
                                            away_goals   = '$goles2',
                                            kickoff      = '$inicio',
                                            scored       = '$status',
                                            wwhen        = NOW()
                                        WHERE 
                                            match_id = '$id_partido'
                                    ";
                                else
                                    $sql = "
                                        UPDATE partidos 
                                        SET
                                            home_team_id = '$id_equipo1', 
                                            away_team_id = '$id_equipo2',
                                            home_goals   = '$goles1',
                                            away_goals   = '$goles2',
                                            scored       = '$status',
                                            wwhen        = NOW()
                                        WHERE 
                                            match_id = '$id_partido'
                                    ";
                                break;

                            case "eliminar":
                                    $mensaje = "Partido Eliminado";
                                    $sql = "";
                                    break;
                        }
                        
                        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                        $stmt->execute();
                ?>
                <table class="table table-striped table-hover" style="width:100%;">
                    <tr>
                        <td><label><?php echo $mensaje; ?></label></td>
                        <td><?php echo getNombreFase($_POST["fase"], $DB); ?></td>
                        <td><?php echo getNombreEquipo($_POST["id_equipo1"], $DB) . ": " . $_POST["goles1"]; ?></td>
                        <td><?php echo getNombreEquipo($_POST["id_equipo2"], $DB) . ": " . $_POST["goles2"]; ?></td>
                        <td><?php echo "Finalizado: " . ($_POST["status"] == 1 ? "Sí" : "No"); ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="height:1px;"></td>
                    </tr>
                </table>
                <?php
                    }
                    catch (PDOException $e)
                    {
                        print $sql;
                        print $e->getMessage();
                    }
                }
                else
                {
                    echo "Error de sesion" . isset($_SESSION["access"]);
                }
                ?>
        </div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='partidos.php'"><i class="fa fa-backward"></i> Volver</button>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>