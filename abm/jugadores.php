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
$title = "Jugadores";

include 'header.php';
$id_equipo = $_REQUEST["id_equipo"];
$options_equipos = getDropdownEquipos($id_equipo, $DB, true, true);

?>
<div class="row">
    <div class="col-lg-12">
        <!--button class="btn btn-sm btn-primary" type="button" onclick="location.href='jugadoresProcesar.php?a=agregar'"><i class="fa fa-plus"></i> Agregar</button-->
        <!--button class="btn btn-sm btn-primary" type="button" onclick="location.href='jugadoresData.php?a=cargar'"><i class="fa fa-upload"></i> Importar</button-->
        <div style="height: 10px;">&nbsp;</div>
        <form name="busq_jugadores" id="frmBuscarJugadores" action="jugadores.php?a=buscar" method="post">
            <div class=" table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <tr>
                        <td style="width:42%;vertical-align:middle;"><input type="text" name="jugador" id="tfJugador" style="width:60%;" placeholder="Jugador" /></td>
                        <td style="width:42%;vertical-align:middle;">
                            <select name="id_equipo" id="cboEquipos">
                                <?=$options_equipos?>
                            </select></td>
                        <td style="width:16%;text-align:right;"><button class="btn btn-md btn-info" type="submit"><i class="fa fa-search"></i> Buscar</button></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="height:1px;"></td>
                    </tr>
                </table>
            </div>
        </form>
        <form name="jugadores" id="frmJugadores" action="jugadoresProcesar.php?a=editar" method="post">
            <input type="hidden" name="equipo" id="hfEquipo" value="<?=$id_equipo?>">
            <div class=" table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <tbody>
                        <tr style="font-weight:bold;">
                            <td style="width:4%;">#</td>
                            <td style="width:20%;">Equipo</td>
                            <td style="width:30%;">Jugador</td>
                            <td style="width:13%;text-align:center;">Goles</td>
                            <td style="width:14%;text-align:center;">¿Goleador?</td>
                            <td style="width:19%;">&nbsp;</td>
                        </tr>
                        <?php
                        if (isset($_SESSION["access"]))
                        {
                            /*clase del paginador*/
                            include("class.pagina.php");
                            try
                            {
                                $and = "";
                                if (!empty($_REQUEST["jugador"]))
                                    $and .= "AND nombre_jugador LIKE '%{$_REQUEST['jugador']}%'";
                                if (!empty($_REQUEST["id_equipo"]))
                                    $and .= " AND id_equipo = {$_REQUEST['id_equipo']}";

                                $sql = "
                                        SELECT j.*, name AS equipo
                                        FROM jugadores j JOIN equipos e ON(id_equipo=team_id)
                                        WHERE 1=1 $and
                                        ORDER BY name, nombre_jugador
                                ";
                                $PAGINADOR=new PAGINADOR($sql);
                                $sql=$PAGINADOR->sql;

                                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                $stmt->execute();
                                $contador = ($PAGINADOR->pagina * $PAGINADOR->records) - ($PAGINADOR->records -1);

                                $max_goals = 0;
                                $max_scorer = "";
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
                                {
                                    if ($row["es_goleador"] == 1) {
                                        $max_goals = $row["goles"];
                                        $max_scorer = $row["nombre_jugador"];
                                    }
                        ?>
                        <tr>
                            <td style="vertical-align:middle;"><?=$contador?></td>
                            <td style="vertical-align:middle;"><?=$row["equipo"]; ?></td>
                            <td id="tdJugador" style="vertical-align:middle;"><?=$row["nombre_jugador"]?></td>
                            <td style="vertical-align:middle;text-align:center;">
                                <span id="spGoles"><?=$row["goles"]?></span>
                                <input type="number" name="goles" id="nfGoles" required="required" min="0" max="20" value="<?=$row['goles']?>" style="text-align:right;width:3em;display:none;" disabled="disabled">
                                <input type="hidden" name="id_jugador" id="hfJugador" value="<?=$row['id_jugador']?>" disabled="disabled">
                            </td>
                            <td style="vertical-align:middle;text-align:center;">
                                <span id="spGoleador"><?php echo $row["es_goleador"] == 1 ? "X" : ""; ?></span>
                                <input type="checkbox" name="goleador" id="chkGoleador" value="1"<?php if ($row["es_goleador"] == 1) echo 'checked="checked"'; ?> style="display:none;" disabled="disabled">
                            </td>
                            <td style="vertical-align:middle;text-align:right;">
                                <button class="btn btn-sm btn-info" type="button" id="btnEditar"<?php if ($row["es_goleador"] == 1) echo 'disabled="disabled"'; ?>><i class="fa fa-edit"></i> Editar</button>
                                <!--button class="btn btn-sm btn-danger" type="button" id="btnBorrar" onclick="location.href='jugadoresProcesar.php?a=eliminar&id=<?=$row['id_jugador']?>'"><i class="fa fa-trash-o"></i> Borrar</button-->
                                <button class="btn btn-sm btn-success" type="submit" id="btnAplicar" style="display:none;"><i class="fa fa-check"></i> Aplicar</button>
                                <button class="btn btn-sm btn-warning" type="button" id="btnCancelar" style="display:none;"><i class="fa fa-undo"></i> Cancelar</button>
                            </td>
                        </tr>
                        <?php
                                    $contador++;
                                }
                                $stmt = null;
                            }
                            catch (PDOException $e)
                            {
                                print $e->getMessage();
                            }
                        }
                    ?>
                        <tr>
                            <td colspan="8" style="height:1px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <input type="hidden" id="hfMaxGoles" value="<?=$max_goals?>">
        <input type="hidden" id="hfMaxGoleador" value="<?=$max_scorer?>">
        <div>
        <?php
        if (!empty($_POST["username"]) || !empty($_POST["id_empresa"]))
        {
            $params = "username=$_POST[username]";
            $params .= "&id_empresa=$_POST[id_empresa]";
        }
        if (!empty($_GET["username"]) || !empty($_GET["id_empresa"]))
        {
            $params = "username=$_GET[username]";
            $params .= "&id_empresa=$_GET[id_empresa]";
        }
        echo $PAGINADOR->ver_pagina("empleados.php", $params);
        ?>
        </div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='dashboard.php'"><i class="fa fa-backward"></i> Volver</button>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>