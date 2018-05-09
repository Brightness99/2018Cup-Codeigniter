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
$title = "Equipos";

include 'header.php';

?>
<div class="row">
    <div class="col-lg-12">
        <div style="height: 10px;">&nbsp;</div>
        <form name="equipos" id="frmEquipos" action="equiposProcesar.php?a=campeon" method="post">

        <div class=" table-responsive">
            <table class="table table-striped table-hover " style="width:100%;">
                <tbody>
                    <tr style="font-weight:bold;">
                        <td style="width:4%;">#</td>
                        <td style="width:24%;">Equipo</td>
                        <td style="width:50%;">URL FIFA</td>
                        <td style="width:10%;text-align:center;">Grupo</td>
                        <td style="width:12%;text-align:center;">¿Campeón?</td>
                    </tr>
                    <?php                               
    
                    if (isset($_SESSION["access"])) 
                    {   
                        try 
                        {
                            $sql = 
                            "
                                SELECT team_id, name, country, team_url, f.stage_name , campeon
                                FROM equipos e, fases f
                                WHERE e.group_order = f.stage_id
                                ORDER BY campeon DESC, 1 ASC
                            ";
                            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                            $stmt->execute();
                            $contador = 1;
                            $hay_campeon = false;
                            while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) 
                            {
                                if ($contador == 1 && $row[5] == 1)
                                    $hay_campeon = true;
                                $descripcionEmpresa = substr($row[3],0,40);
                                $codigoPais = strtolower($row[2]);
                                
                                $checkedCampeon = ($row[5] == 1)?"checked":"unchecked";
                                $nombreCheckbox = "campeon".$row[0];
                                $chk_disabled = $hay_campeon ? "disabled" : "";
                                
                                $esCampeon = "<input type='checkbox' name='campeon' id='chCampeon' value='$row[0]' $checkedCampeon $chk_disabled />";
                                
                                ?>
                                <tr>
                                    <td style="vertical-align:middle;"><?php echo $contador++; ?></td>
                                    <td id="tdEquipo" style="vertical-align:middle;"><img src='<?php echo $imgPathEquipo.$codigoPais.".png"; ?>' style="width:40px;margin-right:10px;"><?=$row[1]?></td>
                                    <td style="vertical-align:middle;"><a href='<?php echo $row[3]; ?>' target="_blank"><?php echo $row[3]; ?></a></td>
                                    <td style="vertical-align:middle;text-align:center;"><?php echo $row[4]; ?></td>
                                    <td style="vertical-align:middle;text-align:center;"><?php echo $esCampeon; ?></td>
                                    <!-- <td> -->
                                            <!-- <button class="btn btn-sm btn-info" type="button"><i class="fa fa-edit"></i>Editar</button> -->
                                            <!-- <button class="btn btn-sm btn-danger" type="button"><i class="fa fa-trash-o"></i>Borrar</button> -->
                                    <!-- </td> -->
                                </tr>
                                <?php 
                            }                           
                        $stmt = null;
                        }
                        catch (PDOException $e) 
                        {
                            print $e->getMessage();
                        }                     
                    }
                    else
                    {
                        echo "hola" . isset($_SESSION["access"]);
                    }
                    ?>
                    <tr>
                        <td colspan="5" style="height:1px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='dashboard.php'"><i class="fa fa-backward"></i> Volver</button>
        </form>

    </div>

    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>