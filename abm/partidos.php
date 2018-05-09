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
$title = "Partidos";

include 'header.php';
?>
<div class="row">
    <div class="col-lg-12">
        <div style="height: 10px;">&nbsp;</div>
        <div class=" table-responsive">
            <table class="table table-striped table-hover " style="width:100%;">
                <tbody>
                    <tr style="font-weight:bold;">
                        <td style="width:4%;">#</td>
                        <td style="width:22%;">Sede</td>
                        <td style="width:10%;text-align:center;">Inicio</td>
                        <td style="width:14%;text-align:center;">Grupo/Fase</td>
                        <td colspan="2" style="text-align:center;">Encuentro</td>
                        <td style="width:8%;text-align:center;">Estatus</td>
                        <td style="width:10%;">&nbsp;</td>
                    </tr>
                    <?php                               
                    if (isset($_SESSION["access"])) 
                    {   
                        try 
                        {
                            $sql = "
                                SELECT match_id,match_no,venue_name,unix_timestamp(kickoff),stage_name,e1.name,home_goals,e2.name,away_goals,scored,(now()<kickoff)
                                FROM partidos p
                                    JOIN sedes s USING(venue_id)
                                    JOIN fases f USING(stage_id)
                                    JOIN equipos e1 ON(e1.team_id=home_team_id)
                                    JOIN equipos e2 ON(e2.team_id=away_team_id)
                                ORDER BY 1
                            ";
                            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) 
                            {   
                                $status = "Finalizado";
                                if ($row[9] == 0 && $row[10] == 1)
                                    $status = "Por Jugar";
                                elseif ($row[9] == 0)
                                    $status = "Jugado";
                    ?>
                    <tr>
                        <td style="vertical-align:middle;"><?php echo $row[1]; ?></td>
                        <td style="vertical-align:middle;"><?php echo $row[2]; ?></td>
                        <td style="vertical-align:middle;text-align:center;"><?php echo date("d/m H:i", $row[3]); ?></td>
                        <td style="vertical-align:middle;text-align:center;"><?php echo $row[4]; ?></td>
                        <td style="vertical-align:middle;text-align:right;"><?php echo "{$row[5]}&nbsp;&nbsp;{$row[6]}"; ?></td>
                        <td style="vertical-align:middle;"><?php echo "{$row[8]}&nbsp;&nbsp;{$row[7]}"; ?></td>
                        <td style="vertical-align:middle;text-align:center;"><?php echo $status; ?></td>
                        <td style="vertical-align:middle;text-align:right;">
                            <button class="btn btn-sm btn-info" type="button" onclick="location.href='partidosABM.php?a=editar&id=<?=$row[0]?>'"><i class="fa fa-edit"></i>Editar</button>
                        </td>
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
                        <td colspan="8" style="height:1px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='dashboard.php'"><i class="fa fa-backward"></i> Volver</button>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>