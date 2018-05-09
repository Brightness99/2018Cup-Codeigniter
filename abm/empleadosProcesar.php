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
$title = "Procesar Empleados";

$action = $_GET['a'];
if ($action != "descargar") {
    include 'header.php';
    global $imgPerfil;
    global $pathExcelEmpleados;
}
if (isset($_SESSION["access"]))
{
    $id_empresa = $_REQUEST['id_empresa'];
    $id_empleado = $_REQUEST['id_empleado'];
    $username_empleado = $_REQUEST['username_empleado'];
    $password_empleado = $_REQUEST['password_empleado'];
    $nombre_empleado = $_REQUEST['nombre_empleado'];
    $apellido_empleado = $_REQUEST['apellido_empleado'];
    $correo_e_empleado = $_REQUEST['correo_e_empleado'];
    $departamento_empleado = $_REQUEST['departamento_empleado'];
    $ubicacion_empleado = $_REQUEST['ubicacion_empleado'];
    $status_empleado = (int)!empty($_REQUEST['status_empleado']);

    $message_file = $message_file_aux = "";
    try
    {
        switch($action)
        {
            case "agregar":
                $mensaje = "Empleado Agregado";
                $sql = "SELECT id_empleado FROM empleados WHERE id_empresa = $id_empresa AND user = '$username_empleado'";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                if ($stmt->rowCount() > 0)
                {
                    $msg_username = " El nombre de usuario ya existe";
                    redirect("empleadosABM.php?a=agregar&id_empresa=$id_empresa&id_empleado=$id_empleado&username_empleado=$username_empleado&nombre_empleado=$nombre_empleado&apellido_empleado=$apellido_empleado&correo_e_empleado=$correo_e_empleado&departamento_empleado=$departamento_empleado&ubicacion_empleado=$ubicacion_empleado&status_empleado=$status_empleado&msg_username=$msg_username");
                    exit;
                }
                $imagen_perfil = setImageProfileComputer($DB, $id_empresa, $username_empleado, $imgPerfil, $_FILES["imagen_perfil"], $message_file_aux);
                $message_file .= $message_file_aux;
                $stmt = null;
                $sql = "
                    INSERT INTO empleados
                        (id_empresa,user,pass,nombre,apellido,correo_e,departamento,ubicacion,imagen_perfil,state)
                    VALUES
                        ('$id_empresa','$username_empleado',md5('$password_empleado'),'$nombre_empleado','$apellido_empleado','$correo_e_empleado','$departamento_empleado','$ubicacion_empleado','$imagen_perfil','$status_empleado')
                ";
                break;

            case "editar":
                $mensaje = "Empleado Modificado";
                $actualizarPassword = !empty($password_empleado) ? "pass = md5('$password_empleado')," : "";

                $imagen_perfil = setImageProfileComputer($DB, $id_empresa, $username_empleado, $imgPerfil, $_FILES["imagen_perfil"], $message_file_aux);
                $message_file .= $message_file_aux;
                $updateQueryImgPerfil = $imagen_perfil != "" ? "imagen_perfil = '$imagen_perfil'," : "";
                $sql = "
                    UPDATE empleados
                    SET
                        id_empresa = '$id_empresa',
                        user = '$username_empleado',
                        $actualizarPassword
                        nombre = '$nombre_empleado',
                        apellido = '$apellido_empleado',
                        correo_e = '$correo_e_empleado',
                        departamento = '$departamento_empleado',
                        ubicacion = '$ubicacion_empleado',
                        $updateQueryImgPerfil
                        state = '$status_empleado'
                    WHERE
                        id_empleado = $id_empleado
                ";
                break;

            case "eliminar":
                $mensaje = "Empleado Eliminado";
                $sql = "DELETE FROM empleados WHERE id_empleado = $id_empleado";
                break;

            case "cargar":
                $mensaje = "Importación Fallida";
                $result = true;
                move_uploaded_file($_FILES['import']['tmp_name'], "{$pathExcelEmpleados}importar.csv");
                $path = pathinfo("{$pathExcelEmpleados}importar.csv");
                $new_filename = $path["dirname"] . "/" . $path["basename"];
                $sql = "
                    SET @x:=1;
                    LOAD DATA LOCAL INFILE '$new_filename'
                    INTO TABLE empleados_import
                    FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"'
                    IGNORE 1 LINES
                    (user,pass,nombre,apellido,correo_e,departamento,ubicacion)
                    SET id_empresa='$id_empresa',linea=@x:=@x+1,user=trim(user),pass=trim(pass),nombre=trim(nombre),apellido=trim(apellido),correo_e=trim(correo_e),departamento=trim(departamento),ubicacion=trim(replace(ubicacion,'\\r',''));
                ";
                $stmt = $DB->prepare($sql, array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));
                $stmt->execute();
                $stmt = null;
                $message_process = "<div style='color:#8441A5;'>";

                //OP => obteniendo cantidad de registros leídos
                $count = getRecordsImport($DB);
                $message_process .= "Se han leído <b>$count</b> registros...<br /><ul>";

                //OP => evaluando registros a ignorar
                list($count, $lines) = getIgnoredRecordsImport($DB);
                $message_process .= "<li>Registros ignorados: <b>$count</b>$lines</li>";

                //OP => evaluando nombres de usuario
                list($count, $repetitions) = getRepeatedUsersImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Nombres de usuario iguales: <b>$count</b>$repetitions</li>";
                //26.04.2018 => OP: por petición se permite cualquier caracter...
                /*list($count, $invalids) = getInvalidUsersImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Nombres de usuario inválidos: <b>$count</b>$invalids</li>";*/
                list($count, $invalids) = getInvalidLengthUsersImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Nombres de usuario con longitud inválida: <b>$count</b>$invalids</li>";

                //OP => evaluando contraseñas
                list($count, $invalids) = getInvalidPasswordsImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Contraseñas con longitud inválida: <b>$count</b>$invalids</li>";

                //OP => evaluando nombres
                list($count, $invalids) = getInvalidFullNamesImport($DB, 1);
                $result = $result && ($count == 0);
                $message_process .= "<li>Nombres inválidos: <b>$count</b>$invalids</li>";
                list($count, $invalids) = getInvalidLengthFullNamesImport($DB, 1);
                $result = $result && ($count == 0);
                $message_process .= "<li>Nombres con longitud inválida: <b>$count</b>$invalids</li>";

                //OP => evaluando apellidos
                list($count, $invalids) = getInvalidFullNamesImport($DB, 2);
                $result = $result && ($count == 0);
                $message_process .= "<li>Apellidos inválidos: <b>$count</b>$invalids</li>";
                list($count, $invalids) = getInvalidLengthFullNamesImport($DB, 2);
                $result = $result && ($count == 0);
                $message_process .= "<li>Apellidos con longitud inválida: <b>$count</b>$invalids</li>";

                //OP => evaluando correos electrónicos (se elimina validación de repetición por petición)
                /*list($count, $repetitions) = getRepeatedEmailsImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Correos electrónicos iguales: <b>$count</b>$repetitions</li>";*/
                list($count, $invalids) = getInvalidEmailsImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Correos electrónicos inválidos: <b>$count</b>$invalids</li>";
                list($count, $invalids) = getInvalidLengthEmailsImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Correos electrónicos con longitud inválida: <b>$count</b>$invalids</li>";

                //OP => evaluando departamentos
                list($count, $invalids) = getInvalidDepartmentsImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Departamentos con longitud inválida: <b>$count</b>$invalids</li>";

                //OP => evaluando ubicaciones
                list($count, $invalids) = getInvalidUbicationsImport($DB);
                $result = $result && ($count == 0);
                $message_process .= "<li>Ubicaciones con longitud inválida: <b>$count</b>$invalids</li>";

                //OP => validando nombres de usuario y correos electrónicos contra empleados ya registrados (empresa seleccionada)
                if ($result) {
                    list($count, $repetitions) = getRepeatedUsersCompanyImport($DB);
                    $result = $result && ($count == 0);
                    $message_process .= "<li>Nombres de usuario ya existentes en la empresa elegida: <b>$count</b>$repetitions</li>";
                    //OP => se elimina validación de repetición por petición
                    /*list($count, $repetitions) = getRepeatedEmailsCompanyImport($DB);
                    $result = $result && ($count == 0);
                    $message_process .= "<li>Correos electrónicos ya existentes en la empresa elegida: <b>$count</b>$repetitions</li>";*/
                    if ($result) {
                        $count = insertEmployees($DB);
                        $result = $result && ($count > 0);
                    }
                }
                $message_process .= "</ul><br />";
                if ($result) {
                    $mensaje = "Importación Exitosa";
                    $message_process .= "<b>* Registros procesados: $count</b>";
                }
                else
                    $message_process .= "¡OPERACIÓN ABORTADA!";
                $message_process .= "</div>";
                $sql = "TRUNCATE empleados_import";
                break;

            case "descargar":
                $sql = "
                    SELECT user, nombre, apellido, correo_e, departamento, ubicacion, SUM(coalesce(puntos_empleado_valor, 0)) AS puntos
                    FROM empleados LEFT JOIN puntos_empleados ON(empleado_id = id_empleado)
                    WHERE id_empresa = $id_empresa
                    GROUP BY 1, 2, 3, 4, 5, 6
                    ORDER BY 1
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = array();
                $data[] = array_map(utf8_decode, array("USUARIO", "NOMBRE", "APELLIDO", "CORREO-E", "DEPARTAMENTO", "UBICACIÓN", "PUNTOS"));
                while ($record = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                    $aux = array($record["user"], $record["nombre"], $record["apellido"], $record["correo_e"], $record["departamento"], $record["ubicacion"], $record["puntos"]);
                    $data[] = array_map(utf8_decode, $aux);
                }
                if ($_REQUEST["format"] == "csv") {
                    header("Content-Type: text/csv");
                    header("Content-Disposition: attachment; filename=empleados.csv");
                    foreach ($data as $record)
                        echo implode(";", $record) . "\n";
                }
                elseif ($_REQUEST["format"] == "xls") {
                    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                    header("Content-Disposition: attachment; filename=empleados.xls");
                    foreach ($data as $record)
                        echo implode("\t", $record) . "\n";
                }
                exit;
        }
        $sql = str_replace("''", "null", $sql);
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
    }
    catch (PDOException $e)
    {
        print $sql;
        print $e->getMessage();
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
        <div class="table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
                <tr>
                    <td>
                        <label><?=$mensaje?></label>
                        <span style="color:#8441A5;"><?="<br />$message_file"?></span>
                        <?="<br />$message_process"?>
                    </td>
                    <td width="15%"><?=$_POST['username_empleado']?></td>
                    <td width="25%"><?="{$_POST['nombre_empleado']} {$_POST['apellido_empleado']}"?></td>
                <tr>
                <tr>
                    <td colspan="4" style="height:1px;"></td>
                </tr>
            </table>
        </div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='empleados.php'"><i class="fa fa-backward"></i> Volver</button>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>