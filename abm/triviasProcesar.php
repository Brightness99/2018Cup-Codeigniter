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
$title = "Procesar Trivia";

include 'header.php';

$action = $_GET['a'];

if (isset($_SESSION["access"]))
{
    $id_trivia     = $_POST["id_trivia"];
    $id_fase       = $_POST["id_fase"];
    $inicio        = $_POST["inicio"];
    $vencimiento   = $_POST["vencimiento"];      
    $preguntas     = $_POST["pregunta"];
    $id_preguntas  = $_POST["id_pregunta"];
    $respuestas    = $_POST["respuesta"];
    $id_respuestas = $_POST["id_respuesta"];
    $nombre_fase   = getNombreFase($id_fase, $DB, false);

    try
    {
        switch($action)
        {
            case "agregar":
                $mensaje = "Trivia Agregada";
                //OP => trivia
                $sql = "INSERT INTO trivias VALUES (NULL, '$inicio', '$vencimiento', $id_fase, '0')";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $id_trivia = $DB->lastInsertId();

                //OP => preguntas
                $sql = "INSERT INTO trivias_preguntas VALUES";
                $separator = " ";
                foreach ($preguntas as $key => $value) {
                    $order = $key + 1;
                    $sql .= "$separator(NULL, $id_trivia, $order, '$value')";
                    $separator = ",";
                }
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $sql = "SELECT id_pregunta FROM trivias_preguntas WHERE id_trivia = $id_trivia ORDER BY orden";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                foreach ($id_preguntas as $key => $value) {
                    $record = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                    $id_preguntas[$key] = $record[0];
                }

                //OP => respuestas
                $sql = "INSERT INTO trivias_respuestas VALUES";
                $separator = " ";
                foreach ($respuestas as $key1 => $data) {
                    $feed = rand();
                    $good = 1;
                    foreach ($data as $key2 => $value) {
                        $order = ($feed + $key1 + $key2) % 3 + 1;
                        $sql .= "$separator(NULL, $id_trivia, {$id_preguntas[$key1]}, $order, '$value', '$good')";
                        $separator = ",";
                        $good = 0;
                    }
                }
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                break;

            case "editar":
                $mensaje = "Trivia Modificada";
                //OP => trivia
                $sql = "UPDATE trivias SET inicio = '$inicio', vencimiento = '$vencimiento', id_fase = $id_fase WHERE id_trivia = $id_trivia";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();

                //OP => preguntas
                $sql = "";
                foreach ($preguntas as $key => $value)
                    $sql .= "UPDATE trivias_preguntas SET pregunta = '$value' WHERE id_pregunta = {$id_preguntas[$key]};";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();

                //OP => respuestas
                $sql = "";
                foreach ($respuestas as $key1 => $data)
                    foreach ($data as $key2 => $value)
                        $sql .= "UPDATE trivias_respuestas SET respuesta = '$value' WHERE id_respuesta = {$id_respuestas[$key1][$key2]};";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                break;

            case "cerrar":
                $mensaje = "Trivia Cerrada";
                $value = 1;
                $sql = "UPDATE trivias SET finalizada = '$value' WHERE id_trivia = {$_GET['id']}";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                break;

            case "eliminar":
                $mensaje = "Operación No Implementada";
        }
    }
    catch (PDOException $e)
    {
        print $sql;
        print $e->getMessage();
        $mensaje = "Error";
    }
}
else
{
    echo "Error de sesion" . isset($_SESSION["access"]);
}
?>
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>
        <div class=" table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
                <tr>
                    <td><label><?=$mensaje?></label></td>
                    <td><?=$nombre_fase?></td>
                </tr>
                <tr>
                    <td colspan="2" style="height:1px;"></td>
                </tr>
            </table>
        </div>
        <div style="height: 20px;">&nbsp;</div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='trivias.php'"><i class="fa fa-backward"></i> Volver</button>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
