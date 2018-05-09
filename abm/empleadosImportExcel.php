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
	case "uploadExcel":
		$title = "Adjuntar Excel";
		$form = "<form name='empleado'  action='empleadosProcesar.php?a=uploadExcel' method='post' enctype='multipart/form-data'>";
		break;

}


include 'header.php';

$option_empresas = getDropdownEmpresas($id_empresa, $DB);


?>
<div class="row">
    <div class="col-lg-9">

        <?php /*if (authorize($_SESSION["access"]["CHECKOUT"]["SHIPPING"]["create"])) { */?>
        <!--  <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-plus"></i> Volver</button>   -->
        <?php /*} */?>
        <div style="height: 10px;">&nbsp;</div>

		<?php echo $form; ?>
		<!--  <form name="carga" action="<?php echo "empleadosProcesar.php?"."a=upload" ?>" method="post" enctype="multipart/form-data">-->
        <div class=" table-responsive">
            <table class="table table-striped table-hover" style="width: 1024px;">
            		<input type=hidden name=id_empleado value="<?php echo $id_empleado ?>">
					<tr>
                        <td><label>Empresa:</label></td>
						<td>
							<!-- <input type="text" name="id_empresa" value="<?php echo $id_empresa ?>" style="width:400px;" />  -->
							<select name="id_empresa">
								<?php echo $option_empresas;?>								
							</select>
						</td>
                    </tr>	
                    <tr>
						<td><label>Excel:  (Debe ser .csv, descargar un <a href=<?php echo $excelEjemplo; ?>>ejemplo aca</a>)</label></td>
						<td>
							<input type="file" required name="excel" onchange="validate(this)" id="csv_file" />   
						</td>
					</tr>
			</table>
        </div>


        <div style="height: 20px;">&nbsp;</div>
        <a href="#" onClick="history.go(-1)"> <button class="btn btn-lg btn-info" type="button"><< Volver</button></a>
        <!--  <button class="btn btn-lg btn-info" type="button" name="confirmar" value="agregar"><i class="fa fa-forward"></i> Confirmar</button>-->
        <input type=submit class="btn btn-lg btn-info" type="button" name="confirmar" value=">> Confirmar"></input>
	</form>

    </div>

    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>


<?php include 'footer.php'; ?>


<script type="text/javascript">
    var _validFileExtensions = [".csv"]; 
    function validate(obj) {
        var arrInputs = obj;

            var oInput = arrInputs;
            if (oInput.type == "file") {
                var sFileName = oInput.value;
                if (sFileName.length > 0) {
                    var blnValid = false;
                    for (var j = 0; j < _validFileExtensions.length; j++) {
                        var sCurExtension = _validFileExtensions[j];
                        if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                            blnValid = true;
                            break;
                        }
                    }
                    
                    if (!blnValid) {
                        obj.value= "";
                        alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                        return false;
                    }
                }
            }
    
      
        return true;
    }
</script>