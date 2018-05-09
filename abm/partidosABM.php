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
$action = $_GET['a'];
switch($action){
    case "agregar":
        $title = "Agregar Partido";
        $checked_status = "checked";        
        break;
    case "editar" || "eliminar":
        if($action == "editar"){
        $title = "Editar Partido";
        }
        if($action == "eliminar"){
            $title = "Eliminar Partido";
        }
        $id_partido = $_GET['id'];
        try
        {
            $sql = "
                    SELECT 
                        match_id, unix_timestamp(kickoff), home_team_id, away_team_id,
                        home_goals, away_goals, home_penalties, away_penalties, venue_id, stage_id, scored, match_no
                    FROM partidos
                    WHERE match_id = '$id_partido'
            ";
            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
            {
                $id_partido         = $row[0];
                $comienzo_partido   = $row[1];
                $equipo1            = $row[2];
                $equipo2            = $row[3];
                $equipo1_goles      = $row[4];
                $equipo2_goles      = $row[5];
                $equipo1_penales    = $row[6];
                $equipo2_penales    = $row[7];
                $estadio_partido    = $row[8];
                $fase_partido       = $row[9];          
                $status             = $row[10];
                $number_game        = $row[11];
            }                   
            $stmt = null;
        }
        catch (PDOException $e) 
        {
            print $e->getMessage();
        }       
        break;
}

include 'header.php';

global $ONLINE;
$required = $style_edition = $style_no_edition = "";
if (!$ONLINE) {
    $required = ' required="required"';
    $style_no_edition = ' style="display:none;"';
    $options_sedes = getDropdownSedes($estadio_partido, $DB);
    $options_fases = getDropdownFases($fase_partido, $DB);
}
else
    $style_edition = ' style="display:none;"';

$option_equipos_1 = getDropdownEquipos($equipo1, $DB);
$option_equipos_2 = getDropdownEquipos($equipo2, $DB);

$disabled = $status ? ' disabled="disabled"' : '';
?>
<input type="hidden" id="hfFinalizado" value="<?=$status?>">
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>
        <form name="partido" id="frmPartido" action="partidosProcesar.php?a=<?=$action?>" method="post">
            <input type=hidden name=id_partido value="<?=$id_partido?>">
            <div class=" table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <tr>
                        <td><label>Encuentro:</label></td>
                        <td><div>#<?=$number_game?></div></td>
                    </tr>
                    <tr>
                        <td><label for="cboSedes">Sede:</label></td>
                        <td>
                            <select name="sede" id="cboSedes"<?php echo $required . $style_edition . $disabled; ?>>
                                <?=$options_sedes?>                             
                            </select>
                            <div<?=$style_no_edition?>><?=getNombreSede($estadio_partido, $DB)?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="dtInicio">Inicio:</label></td>
                        <td>
                            <input type="datetime-local" name="inicio" id="dtInicio" min="2018-06-14T12:00" max="2018-07-15T12:00" value="<?=date("Y-m-d\TH:i",$comienzo_partido)?>"<?php echo $required . $style_edition . $disabled; ?>>
                            <div<?=$style_no_edition?>><?=date("d/m/Y H:i",$comienzo_partido)?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="cboFases">Grupo/Fase:</label></td>
                        <td>
                            <select name="fase" id="cboFases"<?php echo $required . $style_edition . $disabled; ?>>
                                <?=$options_fases?>                             
                            </select>
                            <div<?=$style_no_edition?>><?=getNombreFase($fase_partido, $DB)?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="cboEquipos1">Equipo 1:</label></td>
                        <td>
                            <select name="id_equipo1" id="cboEquipos1" required="required"<?=$disabled?>>
                                <?=$option_equipos_1?>                             
                            </select>
                            &mdash;
                            <input type="number" name="goles1" id="nfGoles1" required="required" min="0" max="20" style="text-align:right;" value="<?=$equipo1_goles?>"<?=$disabled?>>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="cboEquipos2">Equipo 2:</label></td>
                        <td>
                            <select name="id_equipo2" id="cboEquipos2" required="required"<?=$disabled?>>
                                <?=$option_equipos_2?>                             
                            </select>
                            &mdash;
                            <input type="number" name="goles2" id="nfGoles2" required="required" min="0" max="20" style="text-align:right;" value="<?=$equipo2_goles?>"<?=$disabled?>>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Finalizado:</label></td>
                        <td>
                            <input type="radio" name="status" id="rbFinalizadoSi"<?php if ($status) echo 'checked="checked"'; ?> value="1"<?=$disabled?>>
                            <label for="rbFinalizadoSi" style="font-weight:normal;">Sí</label>
                            <input type="radio" name="status" id="rbFinalizadoNo"<?php if (!$status) echo 'checked="checked"'; ?> value="0" style="margin-left:20px;"<?=$disabled?>>
                            <label for="rbFinalizadoNo" style="font-weight:normal;">No</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height:1px;"></td>
                    </tr>
                </table>
            </div>
            <button class="btn btn-lg btn-info" type="button" onclick="location.href='partidos.php'"><i class="fa fa-backward"></i> Volver</button>
            <button class="btn btn-lg btn-info" type="submit"><i class="fa fa-check-square-o"></i> Confirmar</button>
    </form>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>