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
$title = "Actualizar Jugador";

include 'header.php';
$action = $_GET['a'];

if (isset($_SESSION["access"]))
{
    $id_equipo = $_REQUEST["equipo"];
    $var_url = !empty($id_equipo) ? "?id_equipo=$id_equipo" : "";
    $id_jugador = $_REQUEST["id_jugador"];
    $goles = $_REQUEST["goles"];
    $goleador = (int)!empty($_REQUEST["goleador"]);

    try
    {
        switch($action)
        {
            case "agregar":
                $mensaje = "Operación No Implementada";
                $sql = "";
                break;

            case "editar":
                $mensaje = "Jugador Modificado";
                $sql = "
                    UPDATE jugadores
                    SET
                        goles = $goles,
                        es_goleador = '$goleador'
                    WHERE
                        id_jugador = $id_jugador
                ";
                break;

            case "eliminar":
                $mensaje = "Operación No Implementada";
                $sql = "";
                break;

            case "cargar":
                $mensaje = "Importación Fallida";
                $result = true;
                move_uploaded_file($_FILES['import']['tmp_name'], "{$pathExcelEmpleados}importar.csv");
                $path = pathinfo("{$pathExcelEmpleados}importar.csv");
                $new_filename = $path["dirname"] . "/" . $path["basename"];
                $sql = "
                    LOAD DATA LOCAL INFILE '$new_filename'
                    INTO TABLE empleados_import
                    FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"'
                    IGNORE 1 LINES
                    (user,nombre,apellido,correo_e,departamento,ubicacion)
                    SET id_empresa='$id_empresa',user=trim(user),pass=md5(user),nombre=trim(nombre),apellido=trim(apellido),correo_e=trim(correo_e),departamento=trim(departamento),ubicacion=trim(replace(ubicacion,'\\r',''))
                ";
                $stmt = $DB->prepare($sql, array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));
                $stmt->execute();
                $message_process = "<div style='color:#8441A5;'>";
                //OP => obteniendo cantidad de registros copiados
                $sql = "
                    SELECT COUNT(*) FROM empleados_import
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $message_process .= "Se han cargado <b>{$data[0]}</b> registros...<br />";
                //OP => evaluando nombres de usuario
                $sql = "
                    SELECT COUNT(*) FROM empleados_import
                    WHERE user IN(
                        SELECT user FROM empleados_import WHERE user != '' GROUP BY 1 HAVING COUNT(user) > 1)
                    UNION ALL
                    SELECT COUNT(*) FROM empleados_import WHERE length(user) < 4 OR length(user) > 15
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Nombres de usuario iguales: <b>{$data[0]}</b><br />";
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Nombres de usuario con longitud inválida: <b>{$data[0]}</b><br />";
                //OP => evaluando nombres
                $sql = "
                    SELECT COUNT(*) FROM empleados_import WHERE length(nombre) < 2 OR length(nombre) > 30
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Nombres con longitud inválida: <b>{$data[0]}</b><br />";
                //OP => evaluando apellidos
                $sql = "
                    SELECT COUNT(*) FROM empleados_import WHERE length(apellido) < 2 OR length(apellido) > 30
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Apellidos con longitud inválida: <b>{$data[0]}</b><br />";
                //OP => evaluando correos electrónicos
                $sql = "
                    SELECT COUNT(*) FROM empleados_import
                    WHERE correo_e IN(
                        SELECT correo_e FROM empleados_import WHERE correo_e != '' GROUP BY 1 HAVING COUNT(correo_e) > 1)
                    UNION ALL
                    SELECT COUNT(*) FROM empleados_import WHERE correo_e != '' AND (length(correo_e) < 8 OR length(correo_e) > 100)
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Correos electrónicos iguales: <b>{$data[0]}</b><br />";
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Correos electrónicos con longitud inválida: <b>{$data[0]}</b><br />";
                //OP => evaluando departamentos
                $sql = "
                    SELECT COUNT(*) FROM empleados_import WHERE departamento != '' AND (length(departamento) < 3 OR length(departamento) > 100)
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Departamentos con longitud inválida: <b>{$data[0]}</b><br />";
                //OP => evaluando ubicaciones
                $sql = "
                    SELECT COUNT(*) FROM empleados_import WHERE ubicacion != '' AND (length(ubicacion) < 3 OR length(ubicacion) > 100)
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                $result = $result && ($data[0] == 0);
                $message_process .= "- Ubicaciones con longitud inválida: <b>{$data[0]}</b><br />";
                //OP => evaluando registros a ignorar
                $sql = "
                    DELETE FROM empleados_import WHERE '' IN(user, nombre, apellido)
                ";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                $data[0] = $stmt->rowCount();
                $message_process .= "- Registros ignorados: <b>{$data[0]}</b><br />";
                //OP => validando nombres de usuario y correos electrónicos contra empleados ya registrados
                if ($result) {
                    $sql = "
                        SELECT COUNT(*) FROM empleados_import JOIN empleados USING(id_empresa,user)
                        UNION ALL
                        SELECT COUNT(*) FROM empleados_import JOIN empleados USING(id_empresa,correo_e)
                    ";
                    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                    $stmt->execute();
                    $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                    $result = $result && ($data[0] == 0);
                    $message_process .= "- Nombres de usuario ya existentes en la empresa elegida: <b>{$data[0]}</b><br />";
                    $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
                    $result = $result && ($data[0] == 0);
                    $message_process .= "- Correos electrónicos ya existentes en la empresa elegida: <b>{$data[0]}</b><br />";
                    if ($result) {
                        $sql = "
                            INSERT INTO empleados (
                                id_empresa,user,pass,nombre,apellido,correo_e,departamento,ubicacion,state
                            )
                            SELECT id_empresa,user,pass,nombre,apellido,correo_e,departamento,ubicacion,'1'
                            FROM empleados_import
                        ";
                        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                        $stmt->execute();
                        $data[0] = $stmt->rowCount();
                        $result = $result && ($data[0] > 0);
                    }
                }
                if ($result) {
                    $mensaje = "Importación Exitosa";
                    $message_process .= "<br /><b>* Registros procesados: {$data[0]}</b>";
                }
                else
                    $message_process .= "<br />¡OPERACIÓN ABORTADA!";
                $message_process .= "</div>";
                $sql = "TRUNCATE empleados_import";
                break;
        }
        if ($sql != "") {
            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $stmt->execute();
        }
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
                    <td><label><?php echo $mensaje; ?></label><br /><?=$message_process?></td>
                <tr>
            </table>
        </div>
        <div style="height: 20px;">&nbsp;</div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='jugadores.php<?=$var_url?>'"><< Volver</button>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>