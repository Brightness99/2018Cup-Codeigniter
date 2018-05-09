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
global $TRIVIA_QUESTIONS;
global $TRIVIA_QUESTIONS_ANSWERS;
// set page title
$action = $_GET['a'];
$INICIO_MUNDIAL = 1528992000;//OP => 14/06/2018 12:00
$FIN_MUNDIAL = 1531670400;//OP => 15/07/2018 12:00

switch($action){
    case "agregar":
        $title = "Agregar Trivia";
        $inicio = $INICIO_MUNDIAL;
        $vencimiento = $FIN_MUNDIAL;
        $count_questions = $TRIVIA_QUESTIONS;
        $count_answers = $TRIVIA_QUESTIONS_ANSWERS;
        break;
    case "editar" || "eliminar":
        if($action == "editar")
            $title = "Editar Trivia";
        if($action == "eliminar")
            $title = "Eliminar Trivia";
        $id_trivia = $_GET['id'];
        try
        {
            $sql = "
                SELECT
                    id_trivia,
                    unix_timestamp(MIN(kickoff)),
                    unix_timestamp(MAX(kickoff)),
                    id_fase,
                    if(is_group = 1, 'Grupos', stage_name) AS fase
                FROM trivias t
                    JOIN fases f ON(id_fase = f.stage_id)
                    JOIN partidos p ON(if(id_fase = 1, p.stage_id BETWEEN 1 AND 8, if(id_fase = 13, p.stage_id IN(12, 13), p.stage_id = f.stage_id)))
                WHERE id_trivia = $id_trivia
                GROUP BY 1, 4, 5
            ";
            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
            {
                $id_trivia   = $row[0];
                $inicio      = $row[1];
                $vencimiento = $row[2];   
                $id_fase     = $row[3];
                $nombre_fase = $row[4];
            }

            //OP => preguntas
            $sql = "SELECT * FROM trivias_preguntas WHERE id_trivia = $id_trivia ORDER BY orden";
            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $stmt->execute();
            $questions = array();
            while ($questions[] = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT));
            $count_questions = count($questions) - 1;

            //OP => respuestas
            $sql = "
                SELECT r.*
                FROM trivias_respuestas r JOIN trivias_preguntas p USING(id_trivia,id_pregunta)
                WHERE id_trivia = $id_trivia
                ORDER BY p.orden ASC,respuesta_correcta DESC,id_respuesta
            ";
            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $stmt->execute();
            $answers = array();
            while ($answers[] = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT));
            $count_answers = (count($answers) - 1) / $count_questions;
        }
        catch (PDOException $e)
        {
            print $e->getMessage();
        }
        break;
}
include 'header.php';

$option_fases = getDropdownFasesTrivias($id_fase, $DB, $id_trivia);
$mandatory = '<span style="color:red;vertical-align:top;"> *</span>';
?>
<input type="hidden" id="hfInicioMundial" value="<?=$INICIO_MUNDIAL?>">
<input type="hidden" id="hfFinMundial" value="<?=$FIN_MUNDIAL?>">
<input type="hidden" id="hfFechasFases" value="<?=getDatesToStages($DB, true)?>">
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>
        <form name="trivia" id="frmTrivia" action="triviasProcesar.php?a=<?=$action?>" method="post">
            <input type=hidden name=id_trivia value="<?=$id_trivia?>">
            <div class=" table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <?php
                    for ($i = 0; $i < $count_questions; $i++) { 
                    ?>
                    <tr>
                        <td style="width:20%;"><label for="tfPregunta<?=$i?>">Pregunta #<?=$i+1?>:</label></td>
                        <td>
                            <input type="text" name="pregunta[<?=$i?>]" id="tfPregunta<?=$i?>" style="width:85%;" required="required" maxlength="150" value="<?=$questions[$i]['pregunta']?>" placeholder="Ingrese una pregunta" /><?=$mandatory?>
                            <input type="hidden" name="id_pregunta[<?=$i?>]" id="hfPregunta<?=$i?>" value="<?=$questions[$i]['id_pregunta']?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><label>Respuestas:</label></td>
                        <td>
                            <?php
                            $icon = '<span class="btn-success" style="padding:1px 2px;" title="Respuesta Correcta"><i class="fa fa-thumbs-up"></i></span>';
                            $placeholder = "Respuesta Correcta";
                            for ($j = 0, $k = $i * $count_answers; $j < $count_answers; $j++, $k++) { 
                            ?>
                            <?=$icon?>
                            <input type="text" name="respuesta[<?=$i?>][<?=$j?>]" id="tfRespuesta<?=$j?>" style="width:60%;margin-bottom:4px;" required="required" maxlength="60" value="<?=$answers[$k]['respuesta']?>" placeholder="<?=$placeholder?>" /><?=$mandatory?>
                            <input type="hidden" name="id_respuesta[<?=$i?>][<?=$j?>]" id="hfRespuesta<?=$j?>" value="<?=$answers[$k]['id_respuesta']?>"><br />
                            <?php
                                $icon = '<span class="btn-warning" style="padding:1px 2px;" title="Respuesta Incorrecta"><i class="fa fa-thumbs-down"></i></span>';
                                $placeholder = "Respuesta Incorrecta";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td><label for="cboFases">Fase:</label></td>
                        <td>
                            <select name="id_fase" id="cboFases" required="required">
                                <?php echo $option_fases;?>
                            </select><?=$mandatory?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="dtInicio">Inicio:</label></td>
                        <td><input type="datetime-local" name="inicio" id="dtInicio" readonly="readonly" value="<?=date("Y-m-d\TH:i",$inicio)?>"><?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td><label for="dtVencimiento">Vencimiento:</label></td>
                        <td><input type="datetime-local" name="vencimiento" id="dtVencimiento" readonly="readonly" value="<?=date("Y-m-d\TH:i",$vencimiento)?>"><?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height:1px;"></td>
                    </tr>
            </table>
        </div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='trivias.php'"><i class="fa fa-backward"></i> Volver</button>
        <button class="btn btn-lg btn-info" type="submit"><i class="fa fa-check-square-o"></i> Confirmar</button>
    </form>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
