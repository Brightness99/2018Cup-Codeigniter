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
$title = "Trivias";

include 'header.php';
?>
<div class="row">
    <div class="col-lg-12">
        <button class="btn btn-sm btn-primary" type="button" onclick="location.href='triviasABM.php?a=agregar'"><i class="fa fa-plus"></i> Agregar</button>
        <div style="height: 10px;">&nbsp;</div>
        <div class=" table-responsive">
            <table class="table table-striped table-hover " style="width:100%;">
                <tbody>
                    <tr style="font-weight:bold;">
                        <td style="width:4%;">#</td>
                        <td style="width:12%;">Fase</td>
                        <td style="width:36%;">Pregunta</td>
                        <td style="width:11%;text-align:center;">Inicio</td>
                        <td style="width:11%;text-align:center;">Vencimiento</td>                        
                        <td style="width:10%;text-align:center;">Estatus</td>                        
                        <td style="width:16%;">&nbsp;</td>                        
                    </tr>
                    <?php
                    if (isset($_SESSION["access"]))
                    {
                        try
                        {
                            $sql = "
                                SELECT 
                                    id_trivia,
                                    concat(substr(pregunta, 1, 50), ' ...') AS pregunta,
                                    unix_timestamp(inicio) AS inicio,
                                    unix_timestamp(vencimiento) AS vencimiento,
                                    if(is_group = 1, 'Grupos', stage_name) AS fase,
                                    finalizada,
                                    (now() > vencimiento) AS vencida,
                                    (now() BETWEEN inicio AND vencimiento) AS activa
                                FROM trivias
                                    JOIN fases ON(id_fase = stage_id)
                                    JOIN trivias_preguntas USING(id_trivia)
                                WHERE orden = 1
                                ORDER BY id_fase
                            ";
                            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                            $stmt->execute();
                            $counter = 0;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
                            {
                                $status = $color = $bold = $button = "";
                                if ($row["finalizada"] != 1 && $row["vencida"] == 1) {
                                    $status = "Vencida";
                                    $color = "red";
                                    $bold = "bold";
                                    $button = '<button class="btn btn-sm btn-success" type="button" id="btnCerrar"><i class="fa fa-lock"></i> Cerrar</button>';
                                }
                                elseif ($row["finalizada"] == 1)
                                    $status = "Cerrada";
                                elseif ($row["activa"] == 1)
                                    $status = "Activa";
                                else
                                    $status = "Por Iniciar";
                            ?>
                    <tr style="color:<?=$color?>;">
                        <td style="vertical-align:middle;"><?=++$counter?><input type="hidden" id="hfTrivia" value="<?=$row['id_trivia']?>"></td>
                        <td id="tdFase" style="vertical-align:middle;"><?=$row["fase"]?></td>
                        <td style="vertical-align:middle;"><?=$row["pregunta"]?></td>
                        <td style="vertical-align:middle;text-align:center;"><?=date("d/m H:i", $row["inicio"])?></td>
                        <td style="vertical-align:middle;text-align:center;font-weight:<?=$bold?>;"><?=date("d/m H:i", $row["vencimiento"])?></td>
                        <td style="vertical-align:middle;text-align:center;"><?=$status?></td>
                        <td style="vertical-align:middle;text-align:right;">
                            <?=$button?>
                            <button class="btn btn-sm btn-info" type="button" onclick="location.href='triviasABM.php?a=editar&id=<?=$row['id_trivia']?>'"><i class="fa fa-edit"></i> Editar</button>
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
                        echo "Error de sesion" . isset($_SESSION["access"]);
                    }
                    ?>
                    <tr>
                        <td colspan="7" style="height:1px;"></td>
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