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
$title = "Fases";

include 'header.php';
?>
<div class="row">
    <div class="col-lg-12">
        <div style="height: 10px;">&nbsp;</div>
        <div class=" table-responsive">
            <table class="table table-striped table-hover " style="width:100%;">
                <tbody>
                    <tr style="font-weight:bold;">
                        <td style="width:10%;">#</td>
                        <td style="width:90%;">Fase</td>
                    </tr>
                    <?php                               
    
                    if (isset($_SESSION["access"])) 
                    {   
                        try 
                        {
                            $sql = 
                            "
                                SELECT stage_id, stage_name, is_group, sort_order, wwhen 
                                FROM fases
                                ORDER BY 4 ASC
                            ";
                            $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) 
                            {   
                                
                                ?>
                                <tr>
                                    <td><?php echo $row[3]; ?></td>
                                    <td><?php echo $row[1]; ?></td>
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
                    else
                    {
                        echo "hola" . isset($_SESSION["access"]);
                    }
                    ?>
                    <tr>
                        <td colspan="2" style="height:1px;"></td>
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