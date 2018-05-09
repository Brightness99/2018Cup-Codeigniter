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
    case "cargar":
        $title = "Importar Empleados";
        break;
    case "descargar":
        $title = "Exportar Empleados";
        break;
}

include 'header.php';

$option_empresas = getDropdownEmpresas($id_empresa, $DB);
?>
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>
        <form name="empleado_import" id="frmEmpleadoImport" action="empleadosProcesar.php?a=<?=$action?>" method="post" enctype="multipart/form-data">
        <div class=" table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
                <tr>
                    <td width="20%"><label for="cboEmpresas">Empresa:</label></td>
                    <td width="80%">
                        <select name="id_empresa" id="cboEmpresas" required="required">
                            <?php echo $option_empresas;?>                              
                        </select>
                    </td>
                </tr>
                <?php if ($action == "cargar") { ?>
                <tr>
                    <td><label for="flImport">Archivo:</label></td>
                    <td>
                        <input type="file" name="import" id="flImport" accept=".csv" required="required" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size:0.9em;color:#8441A5;">
                        <span>Tenga en cuenta las siguientes premisas al momento de cargar el archivo:</span>
                        <ul>
                            <li>El formato del archivo debe ser <b>.csv</b>.</li>
                            <li>El caracter separador de columnas debe ser <b>;</b> (punto y coma).</li>
                            <li>La primera línea del archivo debe ser de títulos.</li>
                            <li>El nombre de usuario es único, por tanto, no puede repetirse entre las líneas.</li>
                            <li>Las líneas en blanco serán ignoradas.</li>
                            <li>Las líneas sin nombre de usuario, clave, nombre del empleado y apellido del empleado serán ignoradas.</li>
                            <li>El orden de las columnas en el archivo debe ser como sigue:</li>
                            <ol>
                                <li><b>Nombre de Usuario</b>: 4 a 100 caracteres (obligatorio).</li>
                                <li><b>Contraseña</b>: 6 a 15 caracteres (obligatorio).</li>
                                <li><b>Nombre(s)</b>: 2 a 30 caracteres (obligatorio).</li>
                                <li><b>Apellido(s)</b>: 2 a 30 caracteres (obligatorio).</li>
                                <li><b>Correo Electrónico</b>: 8 a 100 caracteres (no obligatorio).</li>
                                <li><b>Departamento</b>: 3 a 100 caracteres (no obligatorio).</li>
                                <li><b>Ubicación</b>: 3 a 100 caracteres (no obligatorio).</li>
                            </ol>
                            <li>Si no se cumple alguna de las condiciones indicadas el proceso de carga será interrumpido.</li>
                            <li>Si requiere un archivo guía puede descargar un <a href=<?=$excelEjemplo?>>ejemplo aquí</a>.</li>
                        </ul>
                    </td>
                </tr>
                <?php } elseif ($action == "descargar") { ?>
                <tr>
                    <td><label>Formato:</label></td>
                    <td>
                        <input type="radio" name="format" id="rbFormatoCSV" value="csv" checked="checked">
                        <label for="rbFormatoCSV" style="font-weight:normal;">CSV</label>
                        <input type="radio" name="format" id="rbFormatoXLS" value="xls" style="margin-left:10px;">
                        <label for="rbFormatoXLS" style="font-weight:normal;">XLS</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="height:1px;"></td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <div style="height: 20px;">&nbsp;</div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='empleados.php'"><i class="fa fa-backward"></i> Volver</button>
        <button class="btn btn-lg btn-info" type="submit"><i class="fa fa-check-square-o"></i> Confirmar</button>
    </form>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
