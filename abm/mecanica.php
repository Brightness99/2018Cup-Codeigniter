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

$mecanica_rules = "";
$result = 0;
if (isset($_GET['a'])) {
    if($_GET['a'] == "editar") {

        $sql = "SELECT * FROM mecanica_juego";
        $rules = $_POST["rules"];
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $mecanica_obj = $stmt->fetch();
            $mecanica_id = $mecanica_obj["id"];
            $sql =
                "
                    UPDATE mecanica_juego
                    SET
                        rules = '$rules'
                    WHERE
                        id = '$mecanica_id'
                ";
        } else{
            $sql =
            "
                INSERT INTO mecanica_juego
                        (rules)
                    values
                        ('$rules')
            ";
        }

        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = 1;
            $mecanica_rules = $rules;
        }
        else {
            $result = -1;
            $mecanica_rules = $_POST["old_rules"];
        }
    }
} else {
    $sql = "SELECT * FROM mecanica_juego";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $mecanica_obj = $stmt->fetch();
        $mecanica_rules = $mecanica_obj["rules"];
    }
    
}

// set page title
$title = "Mecánica de Juego";

include 'header.php';
?>
<input type="hidden" name="result" id="hfResult" value="<?=$result?>">
<div class="row">
    <div class="col-lg-10">
        <form name="mecanicaForm" id="frmMecanica" action="mecanica.php?a=editar" method="post">
            <div style="height: 10px;">&nbsp;</div>
            <div class=" table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <tbody>
                        <tr>
                            <td><label>Reglas:</label></td>
                            <td>
                                <textarea name="rules" id="tfMecanica" required="required" rows="5" style="width:80%;"><?=$mecanica_rules?></textarea>
                                <input type="hidden" name="old_rules" value="<?=$mecanica_rules?>">
                            </td>                 
                        </tr>
                        <tr>
                            <td colspan="2" style="height:1px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-lg btn-info" type="button" onclick="location.href='dashboard.php'"><i class="fa fa-backward"></i> Volver</button>
            <button class="btn btn-lg btn-info" type="submit"><i class="fa fa-check-square-o"></i> Confirmar</button>
        </form>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>