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
$title = "Procesar Equipo";

include 'header.php';

$action = $_GET['a'];

$equipo_campeon = $_POST['campeon'];



?>
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>

        <div class=" table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
                    <tr>
                    <?php

                    if (isset($_SESSION["access"]))
                    {

                        try
                        {
                            switch($action)
                            {
                                case "campeon":                             
                                    if(!empty($equipo_campeon))
                                    {
                                        //Si marcaron un checkbox de equipo campeón, primero marcamos todos los equipos como NO CAMPEONES 
                                        $sql = "UPDATE equipos SET campeon = '0'";
                                        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                        $stmt->execute();

                                    
                                        //Luego marcamos sólo uno como campeón
                                        $sql =
                                        "
                                            UPDATE equipos
                                            SET
                                                campeon = '1'
                                            WHERE
                                                team_id = $equipo_campeon
                                        ";
                                        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                        $stmt->execute();
                                    } 
                                    else 
                                    {
                                        //Si desmarcaron el checkbox de campeón, pasamos todos los equipos a NULL COMO CAMPEON
                                        $sql = "UPDATE equipos SET campeon = '0'";
                                        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                        $stmt->execute();
                                    }

                                    $mensaje = "Campeón identificado";
                                    break;
                            }
                         }
                         catch (PDOException $e)
                         {
                            print $sql;
                            print $e->getMessage();
                         }

                         redirect("equipos.php");
                         
                    }
                    else
                    {
                        echo "Error de sesion" . isset($_SESSION["access"]);
                    }
                    ?>

                    </tr>

            </table>
        </div>

        <div style="height: 20px;">&nbsp;</div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='equipos.php'"><i class="fa fa-backward"></i> Volver</button>
    </div>

    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
