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
$title = "Empleados";

include 'header.php';

global $imgPerfil;

// setea $optionEmpresas. Son las option del select empresas del form de buscar
try
{
  $sql = "SELECT id_empresa, empresa FROM empresas ORDER BY empresa ASC";

  $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $stmt->execute();

  while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
  {
    $optionEmpresas .= "<option value=$row[0] >$row[1]</option>";
  }
  $stmt = null;
}
catch (PDOException $e)
{
  print $e->getMessage();
}

?>
<div class="row">
    <div class="col-lg-12">
        <button class="btn btn-sm btn-primary" type="button" onclick="location.href='empleadosABM.php?a=agregar'"><i class="fa fa-plus"></i> Agregar</button>
        <button class="btn btn-sm btn-primary" type="button" onclick="location.href='empleadosData.php?a=cargar'"><i class="fa fa-upload"></i> Importar</button>
        <button class="btn btn-sm btn-primary" type="button" onclick="location.href='empleadosData.php?a=descargar'"><i class="fa fa-download"></i> Exportar</button>

        <div style="height: 10px;">&nbsp;</div>
            <form name="busq_empleados" id="frmBuscarEmpleados" action="empleados.php?a=buscar" method="post">
                <div class=" table-responsive">
                    <table class="table table-striped table-hover" style="width:100%;">
                        <tr>
                            <td style="width:42%;vertical-align:middle;"><input type="text" name="username" id="tfUsuario" style="width:60%;" placeholder="Usuario" /></td>
                            <td style="width:42%;vertical-align:middle;">
                                <select name="id_empresa" id="cboEmpresas">
                                    <option value="" selected="selected">&mdash; EMPRESA &mdash;</option>
                                    <?=$optionEmpresas?>
                                </select></td>

                            <td style="width:16%;text-align:right;"><button class="btn btn-md btn-info" type="submit"><i class="fa fa-search"></i> Buscar</button></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height:1px;"></td>
                        </tr>
                    </table>
                </div>
            </form>

            <div class=" table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <tbody>
                        <tr style="font-weight:bold;">
                            <td style="width:4%;">#</td>
                            <td style="width:20%;">Empresa</td>
                            <td style="width:16%;">Nombre</td>
                            <td style="width:16%;">Apellido</td>
                            <td style="width:12%;">Usuario</td>
                            <td style="width:8%;text-align:center;">Imagen</td>
                            <td style="width:8%;text-align:center;">Estado</td>
                            <td style="width:16%;">&nbsp;</td>
                        </tr>
                        <?php
                        if (isset($_SESSION["access"]))
                        {
                            /*clase del paginador*/
                            include("class.pagina.php");
                            try
                            {
                                $and = "";
                                if ((!empty($_REQUEST['username']) || !empty($_REQUEST['id_empresa'])))
                                {
                                    if (!empty($_REQUEST['username'])) {
                                        $and .= "AND empl.user = \"".$_REQUEST['username']."\"";
                                    }
                                    if (!empty($_REQUEST['id_empresa'])) {
                                        $and .= " AND empl.id_empresa = ".$_REQUEST['id_empresa'];
                                    }
                                }
                                $sql = "
                                    SELECT id_empleado,
                                        empr.empresa,
                                        nombre,
                                        apellido,
                                        user,
                                        imagen_perfil,
                                        state
                                    FROM empleados empl
                                        JOIN empresas empr USING(id_empresa)
                                    WHERE 1 = 1 $and
                                    ORDER BY empresa, nombre, apellido
                                ";

                                $PAGINADOR=new PAGINADOR($sql);
                                $sql=$PAGINADOR->sql;

                                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                $stmt->execute();
                                $contador = ($PAGINADOR->pagina * $PAGINADOR->records) - ($PAGINADOR->records -1);
                                while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
                                {
                                    $status = $row[6];
                                    $status_show = $status == 1 ? "Activo" : "Inactivo";
                                    $filename = "$imgPerfil{$row[5]}";
                                    $src = $row[5] != "" && file_exists($filename) ? "$filename?" . mt_rand() : "";
                        ?>
                        <tr>
                            <td style="vertical-align:middle;"><?php echo $contador; ?></td>
                            <td style="vertical-align:middle;"><?php echo $row[1]; ?></td>
                            <td style="vertical-align:middle;"><?php echo $row[2]; ?></td>
                            <td style="vertical-align:middle;"><?php echo $row[3]; ?></td>
                            <td style="vertical-align:middle;"><?php echo $row[4]; ?></td>
                            <td style="text-align:center;"><img src="<?=$src?>" style="height:60px;"></td>
                            <td style="vertical-align:middle;text-align:center;"><?php echo $status_show; ?></td>
                            <td style="vertical-align:middle;text-align:right;">
                                <button class="btn btn-sm btn-info" type="button" onclick="location.href='empleadosABM.php?a=editar&id=<?=$row[0]?>'"><i class="fa fa-edit"></i> Editar</button>
                                <button class="btn btn-sm btn-danger" type="button" onclick="location.href='empleadosABM.php?a=eliminar&id=<?=$row[0]?>'"><i class="fa fa-trash-o"></i> Borrar</button>
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
