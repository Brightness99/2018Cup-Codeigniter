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
$dir_images_company = "";
$preview_height = "";

$post_empresas = $_SESSION["empresas"]["post"];
unset($_SESSION["empresas"]);

$id_empresa = isset($post_empresas["id_empresa"]) ? $post_empresas["id_empresa"] : $_GET["id"];
$nombre_empresa = $post_empresas["empresa"];
$descripcionEmpresa = $post_empresas["descripcion"];
$url = $post_empresas["url"];
$bases_condiciones = $post_empresas["bases_condiciones"];
$is_trivia = isset($post_empresas["trivia"]) ? $post_empresas["trivia"] : 1;

switch($action){
    case "agregar":
        $title = "Agregar Empresa";
        break;
    case "editar" || "eliminar":
        if($action == "editar")
          $title = "Editar Empresa";
        if($action == "eliminar")
            $title = "Eliminar Empresa";
        if (!isset($_SESSION["empresas"])) {
            try {
                $sql = "SELECT id_empresa, empresa, descripcion, url, bases_condiciones, is_trivia FROM empresas WHERE id_empresa = $id_empresa";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
                    $id_empresa         = $row[0];
                    $nombre_empresa     = $row[1];
                    $descripcionEmpresa = $row[2];
                    $url                = $row[3];
                    $bases_condiciones  = $row[4];
                    $is_trivia          = $row[5];
                }

                $sql = "SELECT tipo_imagen, nombre_archivo FROM empresas_imagenes WHERE id_empresa = $id_empresa ORDER BY 1";
                $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                while ($images[] = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT));
                $preview_height = " style=\"height:60px;\"";
                $stmt = null;

            }
            catch (PDOException $e) {
                print $e->getMessage();
            }
        }
        global $DIR_IMAGES_COMPANIES;
        $dir_images_company = $DIR_IMAGES_COMPANIES . $url . "/";
        break;
}

include 'header.php';
$mandatory = '<span style="color:red;vertical-align:top;"> *</span>';
$message_upload = "<i class='fa fa-upload'></i> GIF JPG JPEG PNG";

$features_logo = getFeaturesImagesLogo($images);
$features_prize = getFeaturesImagesPrize($images);
$features_banner = getFeaturesImagesBanner($images);
$features_sliders = getFeaturesImagesSliders($images);
?>
<input type="hidden" id="hfMensajeImagen" value="<?=$message_upload?>">
<div class="row">
    <div class="col-lg-10">
        <div style="height: 10px;">&nbsp;</div>
        <form name="empresa" id="frmEmpresa" action="empresasProcesar.php?a=<?=$action?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_empresa" value="<?php echo $id_empresa ?>">
            <input type="hidden" name="old_url" value="<?=$url?>">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="width:100%;">
                    <tr>
                        <td width="25%"><label for="tfEmpresa">Nombre:</label></td>
                        <td><input type="text" name="empresa" id="tfEmpresa" value="<?php echo $nombre_empresa ?>" style="width:85%;" maxlength="150" required="required" placeholder="Ingrese el nombre de la empresa" /><?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td><label for="tfURL">URL:</label></td>
                        <td><input type="text" name="url" id="tfURL" value="<?php echo $url ?>" style="width:45%;" maxlength="15" required="required" placeholder="Escriba un identificador para la empresa" /><?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td><label for="tfDescripcion">Descripción:</label></td>
                        <td><textarea name="descripcion" id="tfDescripcion" rows="3" style="width:85%;" maxlength="255" required="required" placeholder="Escriba una breve descripción sobre la empresa" ><?php echo $descripcionEmpresa ?></textarea><?=$mandatory?></td>
                    </tr>
                    <tr>
                        <td><label>Logo:</label></td>
                        <td>
                            <div class="block-images">
                                <span class="block-images-computer">
                                    <?php
                                    $src = "";
                                    $required = " required=\"required\"";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_logo['filenames'][0]}")) {
                                        $src = "$dir_images_company{$features_logo['filenames'][0]}?" . mt_rand();
                                        $required = "";
                                    }
                                    ?>
                                    <div>
                                        <?=$mandatory?>&nbsp;<span>Computadoras (<?=$features_logo["dimensions"][0]?>):</span>
                                        <br /><label id="lbLogoPC" for="flLogoPC"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imLogoPC" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImageLogoPC" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImageLogoPC" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsLogoPC" value="<?=$features_logo['dimensions'][0]?>">
                                    <input type="hidden" id="hfSizeLogoPC" value="<?=$features_logo['sizes'][0]?>">
                                    <input name="source_logo_pc" type="hidden" id="hfSourceLogoPC" value="<?=($action == 'editar' ? $src : '')?>">
                                </span>
                                <span class="block-images-mobile" style="visibility:hidden;"><!--OP => without logo for mobile-->
                                    <?php
                                    /*$src = "";
                                    $required = " required=\"required\"";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_logo['filenames'][1]}")) {
                                        $src = "$dir_images_company{$features_logo['filenames'][1]}?" . mt_rand();
                                        $required = "";
                                    }*/
                                    ?>
                                    <div>
                                        <?=$mandatory?>&nbsp;<span>Dispositivos Móviles (<?=$features_logo["dimensions"][1]?>):</span>
                                        <br /><label id="lbLogoDM" for="flLogoDM"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imLogoDM" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImageLogoDM" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImageLogoDM" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsLogoDM" value="<?=$features_logo['dimensions'][1]?>">
                                    <input type="hidden" id="hfSizeLogoDM" value="<?=$features_logo['sizes'][1]?>">
                                    <input name="source_logo_dm" type="hidden" id="hfSourceLogoDM" value="<?=($action == 'editar' ? $src : '')?>">
                                </span>
                            </div>
                            <input type="file" name="logo_pc" id="flLogoPC" accept=".gif, .jpg, .jpeg, .png"<?=$required?> />
                            <div class="no-click">&nbsp;</div>
                            <input type="file" name="logo_dm" id="flLogoDM" accept=".gif, .jpg, .jpeg, .png" disabled="disabled" /><!--OP => without logo for mobile-->
                            <div class="no-click">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Premio:</label></td>
                        <td>
                            <div class="block-images">
                                <span class="block-images-computer">
                                    <?php
                                    $src = "";
                                    $required = " required=\"required\"";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_prize['filenames'][0]}")) {
                                        $src = "$dir_images_company{$features_prize['filenames'][0]}?" . mt_rand();
                                        $required = "";
                                    }
                                    ?>
                                    <div>
                                        <?=$mandatory?>&nbsp;<span>Computadoras (<?=$features_prize["dimensions"][0]?>):</span>
                                        <br /><label id="lbPremioPC" for="flPremioPC"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imPremioPC" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImagePremioPC" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImagePremioPC" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsPremioPC" value="<?=$features_prize['dimensions'][0]?>">
                                    <input type="hidden" id="hfSizePremioPC" value="<?=$features_prize['sizes'][0]?>">
                                    <input name="source_premio_pc" type="hidden" id="hfSourcePremioPC" value="<?=($action == 'editar' ? $src : '')?>">
                                </span>
                                <span class="block-images-mobile">
                                    <?php
                                    $src = "";
                                    $required = " required=\"required\"";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_prize['filenames'][1]}")) {
                                        $src = "$dir_images_company{$features_prize['filenames'][1]}?" . mt_rand();
                                        $required = "";
                                    }
                                    ?>
                                    <div>
                                        <?=$mandatory?>&nbsp;<span>Dispositivos Móviles (<?=$features_prize["dimensions"][1]?>):</span>
                                        <br /><label id="lbPremioDM" for="flPremioDM"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imPremioDM" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImagePremioDM" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImagePremioDM" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsPremioDM" value="<?=$features_prize['dimensions'][1]?>">
                                    <input type="hidden" id="hfSizePremioDM" value="<?=$features_prize['sizes'][1]?>">
                                    <input name="source_premio_dm" type="hidden" id="hfSourcePremioDM" value="<?=($action == 'editar' ? $src : '')?>">
                                </span>
                            </div>
                            <input type="file" name="premio_pc" id="flPremioPC" accept=".gif, .jpg, .jpeg, .png"<?=$required?> />
                            <div class="no-click">&nbsp;</div>
                            <input type="file" name="premio_dm" id="flPremioDM" accept=".gif, .jpg, .jpeg, .png"<?=$required?> />
                            <div class="no-click">&nbsp;</div>
                        </td>
                    </tr>
                    <!--OP => se comenta por petición-->
                    <!--tr>
                        <td><label>Encabezado (<i>banner</i>):</label></td>
                        <td>
                            <div class="block-images">
                                <span class="block-images-computer">
                                    <?php
                                    $src = "";
                                    $required = " required=\"required\"";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_banner['filenames'][0]}")) {
                                        $src = "$dir_images_company{$features_banner['filenames'][0]}?" . mt_rand();
                                        $required = "";
                                    }
                                    ?>
                                    <div>
                                        <?=$mandatory?>&nbsp;<span>Computadoras (<?=$features_banner["dimensions"][0]?>):</span>
                                        <br /><label id="lbBannerPC" for="flBannerPC"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imBannerPC" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImageBannerPC" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImageBannerPC" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsBannerPC" value="<?=$features_banner['dimensions'][0]?>">
                                    <input type="hidden" id="hfSizeBannerPC" value="<?=$features_banner['sizes'][0]?>">
                                    <input name="source_banner_pc" type="hidden" id="hfSourceBannerPC" value="<?=($action == 'editar' ? $src : '')?>">
                                </span>
                                <span class="block-images-mobile">
                                    <?php
                                    $src = "";
                                    $required = " required=\"required\"";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_banner['filenames'][1]}")) {
                                        $src = "$dir_images_company{$features_banner['filenames'][1]}?" . mt_rand();
                                        $required = "";
                                    }
                                    ?>
                                    <div>
                                        <?=$mandatory?>&nbsp;<span>Dispositivos Móviles (<?=$features_banner["dimensions"][1]?>):</span>
                                        <br /><label id="lbBannerDM" for="flBannerDM"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imBannerDM" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImageBannerDM" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImageBannerDM" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsBannerDM" value="<?=$features_banner['dimensions'][1]?>">
                                    <input type="hidden" id="hfSizeBannerDM" value="<?=$features_banner['sizes'][1]?>">
                                    <input name="source_banner_dm" type="hidden" id="hfSourceBannerDM" value="<?=($action == 'editar' ? $src : '')?>">
                                </span>
                            </div>
                            <input type="file" name="banner_pc" id="flBannerPC" accept=".gif, .jpg, .jpeg, .png"<?=$required?> />
                            <div class="no-click">&nbsp;</div>
                            <input type="file" name="banner_dm" id="flBannerDM" accept=".gif, .jpg, .jpeg, .png"<?=$required?> />
                            <div class="no-click">&nbsp;</div>
                        </td>
                    </tr-->
                    <tr>
                        <td><label>Bienvenida (<i>slider</i>):</label></td>
                        <td>
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                            ?>
                            <label>Imagen #<?=$i?>:</label>
                            <div class="block-images">
                                <span class="block-images-computer">
                                    <?php
                                    $src = "";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_sliders[$i]['filenames'][0]}"))
                                        $src = "$dir_images_company{$features_sliders[$i]['filenames'][0]}?" . mt_rand();
                                    ?>
                                    <div>
                                        <span>Computadoras (<?=$features_sliders[$i]["dimensions"][0]?>):</span>
                                        <br /><label id="lbSlider<?=$i?>PC" for="flSlider<?=$i?>PC"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imSlider<?=$i?>PC" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImageSlider<?=$i?>PC" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImageSlider<?=$i?>PC" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                        <div id="dvDeleteImageSlider<?=$i?>PC" class="image-container-icon image-delete" title="Eliminar"><i class="fa fa-times fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsSlider<?=$i?>PC" value="<?=$features_sliders[$i]['dimensions'][0]?>">
                                    <input type="hidden" id="hfSizeSlider<?=$i?>PC" value="<?=$features_sliders[$i]['sizes'][0]?>">
                                    <input name="source_slider_pc<?=$i?>" type="hidden" id="hfSourceSlider<?=$i?>PC" value="<?=($action == 'editar' ? $src : '')?>">
                                    <input name="code_slider_pc<?=$i?>" type="hidden" id="hfCodeSlider<?=$i?>PC" value="<?=$features_sliders[$i]['types'][0]?>">
                                    <input name="delete_slider_pc<?=$i?>" type="hidden" id="hfDeleteSlider<?=$i?>PC" value="0">
                                </span>
                                <span class="block-images-mobile">
                                    <?php
                                    $src = "";
                                    if ($action != "agregar" && file_exists("$dir_images_company{$features_sliders[$i]['filenames'][1]}"))
                                        $src = "$dir_images_company{$features_sliders[$i]['filenames'][1]}?" . mt_rand();
                                    ?>
                                    <div>
                                        <span>Dispositivos Móviles (<?=$features_sliders[$i]["dimensions"][1]?>):</span>
                                        <br /><label id="lbSlider<?=$i?>DM" for="flSlider<?=$i?>DM"><?=$message_upload?></label>
                                    </div>
                                    <div class="image-container">
                                        <img id="imSlider<?=$i?>DM" src="<?=$src?>"<?=($src != "" ? $preview_height : "")?>>
                                        <div id="dvViewImageSlider<?=$i?>DM" class="image-container-icon image-view" title="Ver"><i class="fa fa-search-plus fa-lg"></i></div>
                                        <div id="dvDownloadImageSlider<?=$i?>DM" class="image-container-icon image-download" title="Descargar"><i class="fa fa-cloud-download fa-lg"></i></div>
                                        <div id="dvDeleteImageSlider<?=$i?>DM" class="image-container-icon image-delete" title="Eliminar"><i class="fa fa-times fa-lg"></i></div>
                                    </div>
                                    <input type="hidden" id="hfDimensionsSlider<?=$i?>DM" value="<?=$features_sliders[$i]['dimensions'][1]?>">
                                    <input type="hidden" id="hfSizeSlider<?=$i?>DM" value="<?=$features_sliders[$i]['sizes'][1]?>">
                                    <input name="source_slider_dm<?=$i?>" type="hidden" id="hfSourceSlider<?=$i?>DM" value="<?=($action == 'editar' ? $src : '')?>">
                                    <input name="code_slider_dm<?=$i?>" type="hidden" id="hfTypeSlider<?=$i?>DM" value="<?=$features_sliders[$i]['types'][1]?>">
                                    <input name="delete_slider_dm<?=$i?>" type="hidden" id="hfDeleteSlider<?=$i?>DM" value="0">
                                </span>
                            </div>
                            <input type="file" name="slider<?=$i?>_pc" id="flSlider<?=$i?>PC" accept=".gif, .jpg, .jpeg, .png" />
                            <div class="no-click">&nbsp;</div>
                            <input type="file" name="slider<?=$i?>_dm" id="flSlider<?=$i?>DM" accept=".gif, .jpg, .jpeg, .png" />
                            <div class="no-click">&nbsp;</div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="tfBasesCondiciones">Bases y Condiciones:</label></td>
                        <td><textarea name="bases_condiciones" id="tfBasesCondiciones" rows="8" style="width:85%;" placeholder="Describa las bases y condiciones del concurso para la empresa"><?php echo $bases_condiciones ?></textarea></td>
                    </tr>
                    <tr>
                        <td><label>¿Participa en Trivias?</label></td>
                        <td>
                            <input type="radio" name="trivia" id="rbTriviaSi"<?php if ($is_trivia) echo 'checked="checked"'; ?> value="1">
                            <label for="rbTriviaSi" style="font-weight:normal;">Sí</label>
                            <input type="radio" name="trivia" id="rbTriviaNo"<?php if (!$is_trivia) echo 'checked="checked"'; ?> value="0" style="margin-left:20px;">
                            <label for="rbTriviaNo" style="font-weight:normal;">No</label></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:red;"><?=$mandatory?> Campos Obligatorios</td>
                    </tr>
                </table>
            </div>
            <button class="btn btn-lg btn-info" type="button" onclick="location.href='empresas.php'"><i class="fa fa-backward"></i> Volver</button>
            <button class="btn btn-lg btn-info" type="submit"><i class="fa fa-check-square-o"></i> Confirmar</button>
        </form>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>