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
$images = array();
$preview_height = "";
switch($action){
    case "agregar":
        $title = "Agregar Empleado";
        $id_empresa             = $_REQUEST["id_empresa"];
        $id_empleado            = $_REQUEST["id_empleado"];
        $username_empleado      = $_REQUEST["username_empleado"];
        $nombre_empleado        = $_REQUEST["nombre_empleado"];
        $apellido_empleado      = $_REQUEST["apellido_empleado"];
        $correo_e_empleado      = $_REQUEST["correo_e_empleado"];
        $departamento_empleado  = $_REQUEST["departamento_empleado"];
        $ubicacion_empleado     = $_REQUEST["ubicacion_empleado"];
        $status_empleado        = isset($_REQUEST["status_empleado"]) ? $_REQUEST["status_empleado"] : 1;

        $msg_username           = $_REQUEST["msg_username"];
        if (!empty($msg_username)) {
          $msg_username = "<br /><span style='color:#8441A5;font-weight:bold;'>$msg_username</span>";
        }
        break;

    case "editar" || "eliminar":
        if($action == "editar")
          $title = "Editar Empleado";
        if($action == "eliminar")
            $title = "Eliminar Empleado";
        $id_empleado = $_GET['id'];
        try
        {
            $sql =
                "
                    SELECT empleados.*, empresa
                    FROM empleados JOIN empresas USING(id_empresa)
                    WHERE id_empleado = $id_empleado
                ";
            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
            {
                $id_empleado            = $row["id_empleado"];
                $id_empresa             = $row["id_empresa"];
                $username_empleado      = $row["user"];
                $nombre_empleado        = $row["nombre"];
                $apellido_empleado      = $row["apellido"];
                $correo_e_empleado      = $row["correo_e"];
                $departamento_empleado  = $row["departamento"];
                $ubicacion_empleado     = $row["ubicacion"];
                $imagen_perfil          = $row["imagen_perfil"];
                $status_empleado        = $row["state"];
                $nombre_empresa         = $row["empresa"];
            }
            $images[] = array("tipo_imagen" => "$IMAGE_PROFILE_INDEX$IMAGE_COMPUTER_INDEX", "nombre_archivo" => $imagen_perfil);//pc
            $images[] = array("tipo_imagen" => "$IMAGE_PROFILE_INDEX$IMAGE_MOBILE_INDEX", "nombre_archivo" => "");//mobile

            $preview_height = " style=\"height:60px;\"";
            $stmt = null;
        }
        catch (PDOException $e)
        {
            print $e->getMessage();
        }
        break;

}

include 'header.php';

global $imgPerfil;

$option_empresas = getDropdownEmpresas($id_empresa, $DB);

$mandatory = '<span style="color:red;vertical-align:top;"> *</span>';
$message_upload = "<i class='fa fa-upload'></i> GIF JPG JPEG PNG";

$features_profile = getFeaturesImagesProfile($images);
?>
<input type="hidden" id="hfAccion" value="<?=$action?>">
<input type="hidden" id="hfMensajeImagen" value="<?=$message_upload?>">
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>
        <form name="empleado" id="frmEmpleado" action="empleadosProcesar.php?a=<?=$action?>" method="post" enctype="multipart/form-data">
            <input type=hidden name=id_empleado value="<?php echo $id_empleado ?>">
            <div class=" table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <tr>
                        <td width="25%"><label>Empresa:</label></td>
                        <td>
                            <select name="id_empresa" id="cboEmpresas" required="required">
                                <?php echo $option_empresas;?>
                            </select><?=$mandatory?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="tfUsuario">Usuario:</label></td>
                        <td><input type="text" name="username_empleado" id="tfUsuario" value="<?php echo $username_empleado ?>" style="width:70%;" maxlength="100" required="required" placeholder="Ingrese un nombre de usuario" /><?=$mandatory?><?php echo $msg_username ?></td>
                    </tr>
                    <tr>
                        <td><label for="tfClave">Clave:</label></td>
                        <td><input type="password" name="password_empleado" id="tfClave" value="<?php echo $password_empleado ?>" style="width:45%;" maxlength="15"<?php if ($action == "agregar") echo ' required="required"'; ?> placeholder="Escriba una contraseña" /> <?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td><label for="tfNombre">Nombre:</label></td>
                        <td><input type="text" name="nombre_empleado" id="tfNombre" value="<?php echo $nombre_empleado ?>" style="width:55%;" required="required" maxlength="30" placeholder="Ingrese el(los) nombre(s)" /><?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td><label for="tfApellido">Apellido:</label></td>
                        <td><input type="text" name="apellido_empleado" id="tfApellido" value="<?php echo $apellido_empleado ?>" style="width:55%;" required="required" maxlength="30" placeholder="Ingrese el(los) apellido(s)" /><?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td><label for="tfCorreoE">Correo Electrónico:</label></td>
                        <td><input type="text" name="correo_e_empleado" id="tfCorreoE" value="<?php echo $correo_e_empleado ?>" style="width:70%;" maxlength="100" placeholder="Escriba una dirección de correo-e" /></td>
                    </tr>
                    <tr>
                        <td><label for="tfDepartamento">Departamento:</label></td>
                        <td><input type="text" name="departamento_empleado" id="tfDepartamento" value="<?php echo $departamento_empleado ?>" style="width:70%;" maxlength="100" placeholder="Indique el área donde labora el empleado" /></td>
                    </tr>
                    <tr>
                        <td><label for="tfUbicacion">Ubicación:</label></td>
                        <td><input type="text" name="ubicacion_empleado" id="tfUbicacion" value="<?php echo $ubicacion_empleado ?>" style="width:70%;" maxlength="100" placeholder="Indique la ubicación del empleado (país o provincia)" /></td>
                    </tr>
                    <tr>
                        <td><label>Imagen Perfil (<?=$features_profile["dimensions"][0]?>):</label></td>
                        <td>
                            <div class="block-images">
                                <?php
                                $src = "";
                                if ($action != "agregar" && $features_profile["filenames"][0] != "" && file_exists("$imgPerfil{$features_profile['filenames'][0]}"))
                                        $src = "$imgPerfil{$features_profile['filenames'][0]}?" . mt_rand();
                                ?>
                                <div>
                                    <label id="lbImagenPerfil" for="flImagenPerfil"><?=$message_upload?></label>
                                </div>
                                <input type="hidden" id="hfDimensionsImagenPerfil" value="<?=$features_profile['dimensions'][0]?>">
                                <input type="hidden" id="hfSizeImagenPerfil" value="<?=$features_profile['sizes'][0]?>">
                                <img id="imImagenPerfil" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                            </div>
                            <input type="file" name="imagen_perfil" id="flImagenPerfil" accept=".gif, .jpg, .jpeg, .png" />
                            <div class="no-click">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Estatus:</label></td>
                        <td>
                            <input type="checkbox" name="status_empleado" id="chkEstatus" value="1"<?php if (!empty($status_empleado)) echo ' checked="checked"'; ?> />
                            <label for="chkEstatus" style="font-weight:normal;">Empleado Activo</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:red;"><?=$mandatory?> Campos Obligatorios</td>
                    </tr>
                </table>
            </div>

            <button class="btn btn-lg btn-info" type="button" onclick="location.href='empleados.php'"><i class="fa fa-backward"></i> Volver</button>
            <button class="btn btn-lg btn-info" type="submit"><i class="fa fa-check-square-o"></i> Confirmar</button>
        </form>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
