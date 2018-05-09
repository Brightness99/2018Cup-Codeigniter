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

if (!empty($_GET["download"])) {
    $file = explode("?", $_POST[$_GET["download"]])[0];
    header("Content-Type: image/" . pathinfo($file, PATHINFO_EXTENSION));
    header("Content-Disposition: attachment; filename=" . basename($file));
    exit(file_get_contents($file));
}

$_SESSION["empresas"]["post"] = $_POST;

// set page title
$title = "Procesar Empresa";

include 'header.php';

$action = $_GET['a'];
switch($action){
    case "agregar":
        $mensaje = "Empresa Agregada";
        break;
    case "editar":
        $mensaje = "Empresa Modificada";
        break;
}
$url_result = "location.href='empresas.php'";
?>
<div class="row">
    <div class="col-lg-12">
        <div style="height: 10px;">&nbsp;</div>
        <div class=" table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
                    <?php 
                    $is_ok = false;
                    $message_files = $message_files_aux = "";
                    if (isset($_SESSION["access"])) 
                    {
                        global $DIR_IMAGES_COMPANIES;
                        
                        $id_empresa = $_POST['id_empresa'];
                        $empresa_nombre =   $_POST['empresa'];
                        $old_url = $_POST['old_url'];
                        $url = $_POST['url'];
                        $descripcion = $_POST['descripcion'];
                        $is_trivia = $_POST['trivia'];
                        $bases_condiciones = $_POST['bases_condiciones'];

                        try
                        {
                            switch($action){
                                case "agregar":
                                    $mensaje = "Empresa Agregada";
                                    $sql = "
                                        INSERT INTO empresas (empresa, descripcion, url, bases_condiciones, is_trivia)
                                        VALUES ('$empresa_nombre', '$descripcion', '$url', '$bases_condiciones', '$is_trivia')
                                    ";
                                    break;

                                case "editar":
                                    $mensaje = "Empresa Modificada";
                                    $sql =
                                    "
                                        UPDATE empresas 
                                        SET 
                                            empresa = '$empresa_nombre', 
                                            descripcion = '$descripcion',
                                            url = '$url',
                                            is_trivia = '$is_trivia',
                                            bases_condiciones = '$bases_condiciones'
                                        WHERE 
                                            id_empresa = '$id_empresa'
                                    ";
                                    break;

                                case "eliminar":
                                    $mensaje = "Empresa Eliminada";
                                    $sql = "DELETE FROM empresas WHERE id_empresa = '$id_empresa'";
                                    break;
                            }
                            $sql = str_replace("''", "null", $sql);
                            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                            $stmt->execute();
                            if ($action == "agregar")
                                $id_empresa = $DB->lastInsertId();
                            $is_ok = true;
                        }
                        catch (PDOException $e)
                        {
                            handleErrorDB($e, $sql);
                            $url_result = "window.history.back(-1)";
                        }

                        try {
                            $message_files .= "<br />";
                            for ($i = 1; $i <= 5; $i++) {
                                if (!empty($_POST["delete_slider_pc{$i}"]) && !empty($_POST["source_slider_pc{$i}"])) {
                                    $sql = "DELETE FROM empresas_imagenes WHERE id_empresa = '$id_empresa' AND tipo_imagen = '{$_POST["code_slider_pc$i"]}'";
                                    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                    $stmt->execute();
                                    if (!unlink(explode("?", $_POST["source_slider_pc{$i}"])[0]))
                                        $message_files .= "No se pudo eliminar el archivo para <em>Slider</em> #$i en computadoras.<br />";
                                }
                                if (!empty($_POST["delete_slider_dm{$i}"]) && !empty($_POST["source_slider_dm{$i}"])) {
                                    $sql = "DELETE FROM empresas_imagenes WHERE id_empresa = '$id_empresa' AND tipo_imagen = '{$_POST["code_slider_dm$i"]}'";
                                    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                    $stmt->execute();
                                    if (!unlink(explode("?", $_POST["source_slider_dm{$i}"])[0]))
                                        $message_files .= "No se pudo eliminar el archivo para <em>Slider</em> #$i en dispositivos móviles.<br />";
                                }
                            }
                        }
                        catch (Exception $e) {
                            echo $e->getMessage() . $message_files;
                            $url_result = "window.history.back(-1)";
                        }

                        try {
                            $message_files .= "<br />";
                            $result_dir = false;
                            $dir_images_company = $DIR_IMAGES_COMPANIES . $url;
                            $file_exists_old_url = ($old_url != "" && file_exists($DIR_IMAGES_COMPANIES . $old_url));
                            $file_exists_new_url = ($url != "" && file_exists($dir_images_company));

                            if ($action == "agregar" && !$file_exists_old_url && !$file_exists_new_url)
                                $result_dir = mkdir($dir_images_company);
                            if ($action == "editar" && !$file_exists_old_url && !$file_exists_new_url)
                                $result_dir = mkdir($dir_images_company);
                            elseif ($action == "editar" && $file_exists_old_url && !$file_exists_new_url)
                                $result_dir = rename($DIR_IMAGES_COMPANIES . $old_url, $dir_images_company);
                            elseif ($action == "editar" && $old_url == $url)
                                $result_dir = true;

                            if ($result_dir) {
                                $dir_images_company .= "/";
                                setImageCompanyLogoComputer($DB, $id_empresa, $dir_images_company, $_FILES["logo_pc"], $message_files_aux);
                                $message_files .= $message_files_aux;
                                setImageCompanyLogoMobile($DB, $id_empresa, $dir_images_company, $_FILES["logo_dm"], $message_files_aux);
                                $message_files .= $message_files_aux;
                                setImageCompanyPrizeComputer($DB, $id_empresa, $dir_images_company, $_FILES["premio_pc"], $message_files_aux);
                                $message_files .= $message_files_aux;
                                setImageCompanyPrizeMobile($DB, $id_empresa, $dir_images_company, $_FILES["premio_dm"], $message_files_aux);
                                $message_files .= $message_files_aux;
                                setImageCompanyBannerComputer($DB, $id_empresa, $dir_images_company, $_FILES["banner_pc"], $message_files_aux);
                                $message_files .= $message_files_aux;
                                setImageCompanyBannerMobile($DB, $id_empresa, $dir_images_company, $_FILES["banner_dm"], $message_files_aux);
                                $message_files .= $message_files_aux;
                                for ($i = 1; $i <= 5; $i++) {
                                    setImageCompanySliderComputer($DB, $id_empresa, $dir_images_company, $_FILES["slider{$i}_pc"], $i, $message_files_aux);
                                    $message_files .= $message_files_aux;
                                    setImageCompanySliderMobile($DB, $id_empresa, $dir_images_company, $_FILES["slider{$i}_dm"], $i, $message_files_aux);
                                    $message_files .= $message_files_aux;
                                }
                            }
                            else
                                $message_files .= "No se pudo crear/renombrar el directorio para la empresa.";
                        }
                        catch (Exception $e) {
                            echo $e->getMessage() . $message_files;
                            $url_result = "window.history.back(-1)";
                        }
                    }
                    else
                    {
                        echo "Error de sesion" . isset($_SESSION["access"]);
                    }
                    if ($is_ok) {
                        unset($_SESSION["empresas"]);
                    ?>
                    <tr>
                            <td>
                                <label><?=$mensaje?></label>
                                <span style="color:#8441A5;"><?="<br />$message_files"?></span>
                            </td>
                            <td width="25%"><?php echo $_POST['empresa']; ?></td>
                            <td width="15%"><?php echo $_POST['url']; ?></td> 
                    </tr>
                    <?php
                    }
                    ?>
            </table>
        </div>
        <div style="height: 20px;">&nbsp;</div>
        <button class="btn btn-lg btn-info" type="button" onclick="<?=$url_result?>"><i class="fa fa-backward"></i> Volver</button>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>