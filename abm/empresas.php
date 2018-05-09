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
$title = "Empresas";

include 'header.php';

global $DIR_IMAGES_COMPANIES;
?>
<div class="row">
    <div class="col-lg-12">
        <button class="btn btn-sm btn-primary" type="button" onclick="location.href='empresasABM.php?a=agregar'"><i class="fa fa-plus"></i> Agregar</button>
        <div style="height: 10px;">&nbsp;</div>

        <div class=" table-responsive">
            <table class="table table-striped table-hover" style="width:100%;">
                <tbody>
                    <tr style="font-weight:bold;">
                        <td style="width:4%;">#</td>
                        <td style="width:24%;">Nombre</td>
                        <td style="width:12%;">URL</td>
                        <td style="width:34%;">Descripción</td>
                        <td style="width:16%;text-align:center;">Logo</td>
                        <td style="width:10%;">&nbsp;</td>
                    </tr>
                    <?php
                    if (isset($_SESSION["access"]))
                    {
                        try
                        {
                            $sql = "
                                SELECT
                                    e.id_empresa,
                                    empresa,
                                    descripcion,
                                    url,
                                    nombre_archivo
                                FROM empresas e
                                    LEFT JOIN empresas_imagenes ei ON(ei.id_empresa=e.id_empresa AND tipo_imagen = '11') # '11' = logo-pc
                                ORDER BY empresa ASC
                            ";
                            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                            $stmt->execute();
                            $record = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
                            {
                                $filename = "$DIR_IMAGES_COMPANIES{$row[3]}/{$row[4]}";
                                $src = $row[4] != "" && file_exists($filename) ? "$filename?" . mt_rand() : "";
                            ?>
                                <tr>
                                    <td style="vertical-align:middle;"><?php echo $record++; ?></td>
                                    <td style="vertical-align:middle;"><?php echo $row[1]; ?></td>
                                    <td style="vertical-align:middle;"><?php echo $row[3]; ?></td>
                                    <td style="vertical-align:middle;"><?php echo $row[2]; ?></td>
                                    <td style="text-align:center;"><img src="<?=$src?>" style="height:60px;"></td>
                                    <td style="vertical-align:middle;text-align:right;">
                                        <button class="btn btn-sm btn-info" type="button" onclick="location.href='empresasABM.php?a=editar&id=<?=$row[0]?>'"><i class="fa fa-edit"></i> Editar</button>
                                    </td>
                                </tr>
                            <?php
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
                        <td colspan="6" style="height:1px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="btn btn-lg btn-info" type="button" onclick="location.href='dashboard.php'"><i class="fa fa-backward"></i> Volver</button>
    </div>
    <div class="col-lg-3">
        <?php include 'sidebar.php'; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
