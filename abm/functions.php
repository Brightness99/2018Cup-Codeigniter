<?php
/*
 * @author Shahrukh Khan
 * @website http://www.thesoftwareguy.in
 * @facebbok https://www.facebook.com/Thesoftwareguy7
 * @twitter https://twitter.com/thesoftwareguy7
 * @googleplus https://plus.google.com/+thesoftwareguyIn
 */


function redirect($url) {

    echo "<script language=\"JavaScript\">\n";
    echo "<!-- hide from old browser\n\n";

    echo "window.location = \"" . $url . "\";\n";

    echo "-->\n";
    echo "</script>\n";

    return true;
}

function set_rights($menus, $menuRights, $topmenu) {
    $data = array();

    for ($i = 0, $c = count($menus); $i < $c; $i++) {


        $row = array();
        for ($j = 0, $c2 = count($menuRights); $j < $c2; $j++) {
            if ($menuRights[$j]["rr_modulecode"] == $menus[$i]["mod_modulecode"]) {
                if (authorize($menuRights[$j]["rr_create"]) || authorize($menuRights[$j]["rr_edit"]) ||
                        authorize($menuRights[$j]["rr_delete"]) || authorize($menuRights[$j]["rr_view"])
                ) {

                    $row["menu"] = $menus[$i]["mod_modulegroupcode"];
                    $row["menu_name"] = $menus[$i]["mod_modulename"];
                    $row["page_name"] = $menus[$i]["mod_modulepagename"];
                    $row["create"] = $menuRights[$j]["rr_create"];
                    $row["edit"] = $menuRights[$j]["rr_edit"];
                    $row["delete"] = $menuRights[$j]["rr_delete"];
                    $row["view"] = $menuRights[$j]["rr_view"];

                    $data[$menus[$i]["mod_modulegroupcode"]][$menuRights[$j]["rr_modulecode"]] = $row;
                    $data[$menus[$i]["mod_modulegroupcode"]]["top_menu_name"] = $menus[$i]["mod_modulegroupname"];
                }
            }
        }
    }

    return $data;
}

function authorize($module) {
    return $module == "yes" ? TRUE : FALSE;
}


function getNombreEmpresa($id_empresa, $DB) {
    $sql =
    "
        SELECT empresa
        FROM empresas
        WHERE id_empresa = $id_empresa
    ";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
    {
        $nombre_empresa = $row[0];
    }
    return $nombre_empresa;
}

function getNombreFase($id_fase, $DB, $all_groups = true) {
    $stage_name = empty($all_groups) ? "if(is_group=1,'Grupos',stage_name)" : "stage_name";
    $sql =
    "
    SELECT $stage_name
    FROM fases
    WHERE stage_id = '$id_fase'     
    ";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
    {
        $nombre_fase = $row[0];
    }
    return $nombre_fase;
}

function getDropdownEmpresas($id_empresa, $DB, $identify = null) {
    $empresas_drop_down = sprintf("<option value=''>&mdash; %s &mdash;</option>", (empty($identify) ? "SELECCIONE" : "EMPRESAS"));
    try
    {
        $sql =
        "
            SELECT id_empresa, empresa
            FROM empresas
            ORDER BY 2
        ";
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
        {

            if ($id_empresa == $row[0])
            {
                $selected = 'selected="selected"';
            }
            else
            {

                $selected = '';
            }
            $empresas_drop_down .= "<option value='$row[0]' $selected>$row[1]</option>";
        }
    }
    catch (PDOException $e)
    {
        print $e->getMessage();
    }
    return $empresas_drop_down;
}

function getDropdownEquipos($id_equipo, $DB, $identify = null, $unique_teams = null) {
    $equipos_drop_down = sprintf("<option value=''>&mdash; %s &mdash;</option>", (empty($identify) ? "SELECCIONE" : "EQUIPOS"));
    $condition_unique = !empty($unique_teams) ? "AND team_id <= 32" : "";
    try
    {
        $sql =
        "
            SELECT team_id, name
            FROM equipos
            WHERE 1=1 $condition_unique
            ORDER BY 2
        ";
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
        {

            if ($id_equipo == $row[0])
            {
                $selected = 'selected="selected"';
            }
            else
            {

                $selected = '';
            }
            $equipos_drop_down .= "<option value='$row[0]' $selected>$row[1]</option>";
        }
    }
    catch (PDOException $e)
    {
        print $e->getMessage();
    }
    return $equipos_drop_down;
}

function getDropdownFases($id_fase, $DB, $identify = null) {
    $fases_drop_down = sprintf("<option value=''>&mdash; %s &mdash;</option>", (empty($identify) ? "SELECCIONE" : "FASES"));
    try
    {
        $sql =
        "
            SELECT stage_id, stage_name
            FROM fases
            ORDER BY sort_order
        ";
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
        {

            if ($id_fase == $row[0])
            {
                $selected = 'selected="selected"';
            }
            else
            {

                $selected = '';
            }
            $fases_drop_down .= "<option value='$row[0]' $selected>$row[1]</option>";
        }
    }
    catch (PDOException $e)
    {
        print $e->getMessage();
    }
    return $fases_drop_down;
}

function getDropdownFasesSinGrupos($id_fase, $DB, $identify = null) {
    $fases_drop_down = sprintf("<option value=''>&mdash; %s &mdash;</option>", (empty($identify) ? "SELECCIONE" : "FASES"));
    try
    {
        $sql =
        "
            SELECT DISTINCT if(is_group=1,1,stage_id), if(is_group=1,'Grupos',stage_name)
            FROM fases
            ORDER BY sort_order
        ";
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
        {

            if ($id_fase == $row[0])
            {
                $selected = 'selected="selected"';
            }
            else
            {

                $selected = '';
            }
            $fases_drop_down .= "<option value='$row[0]' $selected>$row[1]</option>";
        }
    }
    catch (PDOException $e)
    {
        print $e->getMessage();
    }
    return $fases_drop_down;
}

function getDropdownFasesTrivias($id_fase, $DB, $trivia) {
    $fases_drop_down = "<option value=''>&mdash; SELECCIONE &mdash;</option>";
    $condition_trivia = !empty($trivia) ? "id_trivia=$trivia" : "1=1";
    try
    {
        if (!empty($trivia))
            $sql = "
                SELECT
                    if(is_group = 1, 1, stage_id),
                    if(is_group = 1, 'Grupos', stage_name),
                    sort_order
                FROM fases f
                    JOIN trivias t ON(stage_id = id_fase)
                WHERE id_trivia = $trivia
                    AND stage_id != 12 # Tercer Puesto
                UNION ALL
                SELECT DISTINCT
                    if(is_group = 1, 1, stage_id),
                    if(is_group = 1, 'Grupos', stage_name),
                    sort_order
                FROM fases f
                    LEFT JOIN trivias t ON(stage_id = id_fase)
                WHERE stage_id != 12 # Tercer Puesto
                GROUP BY 1, 2
                HAVING COUNT(t.id_trivia) = 0
                ORDER BY sort_order
            ";
        else
            $sql = "
                SELECT DISTINCT
                    if(is_group = 1, 1, stage_id),
                    if(is_group = 1, 'Grupos', stage_name)
                FROM fases f
                    LEFT JOIN trivias t ON(stage_id = id_fase)
                WHERE stage_id != 12 # Tercer Puesto
                GROUP BY 1, 2
                HAVING COUNT(t.id_trivia) = 0
                ORDER BY sort_order
            ";
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
        {

            if ($id_fase == $row[0])
            {
                $selected = 'selected="selected"';
            }
            else
            {

                $selected = '';
            }
            $fases_drop_down .= "<option value='$row[0]' $selected>$row[1]</option>";
        }
    }
    catch (PDOException $e)
    {
        print $e->getMessage();
    }
    return $fases_drop_down;
}

function getDropdownSedes($id_sede, $DB, $identify = null) {
    $sedes_drop_down = sprintf("<option value=''>&mdash; %s &mdash;</option>", (empty($identify) ? "SELECCIONE" : "SEDES"));
    try
    {
        $sql = "
            SELECT venue_id, venue_name
            FROM sedes
            ORDER BY 2
        ";
        $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
        {
            if ($id_sede == $row[0])
            {
                $selected = 'selected="selected"';
            }
            else
            {
                $selected = '';
            }
            $sedes_drop_down .= "<option value='$row[0]' $selected>$row[1]</option>";
        }
    }
    catch (PDOException $e)
    {
        print $e->getMessage();
    }
    return $sedes_drop_down;
}

function getNombreEquipo($id_equipo, $DB) {
    $sql = "SELECT name FROM equipos WHERE team_id = $id_equipo";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
    return $row[0];
}

function handleErrorDB(PDOException $pdo_exception, $sql)
{
    $list_fields = array(
        "empresa" => "Nombre de Empresa",
        "url" => "Identificador de Empresa (URL)"
    );
    global $ONLINE;
    if (!$ONLINE) {
        echo $sql . "<br />";
        echo $pdo_exception->getMessage() . "<br />";
    }
    switch ($pdo_exception->getCode()) {
        case "23000":
            list($value, $field) = explode(",", (str_replace(" for key ", ",", str_replace("Duplicate entry ", "", str_replace("'", "", $pdo_exception->errorInfo[2])))));
            echo "{$list_fields[$field]} <b>\"$value\"</b> ya existe en la base de datos.";
            break;
    }
}

function getNombreSede($id_sede, $DB) {
    $sql = "SELECT venue_name FROM sedes WHERE venue_id = $id_sede";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
    return $row[0];
}

function getDatesToStages($DB, $as_string = false)
{
    $sql = "
        SELECT
            if(is_group = 1, 1, f.stage_id) AS id,
            if(is_group = 1, 'Grupos', stage_name) AS fase,
            unix_timestamp(MIN(kickoff)) AS inicio,
            unix_timestamp(MAX(kickoff)) AS vencimiento
        FROM fases f
            JOIN partidos p ON(IF(f.stage_id BETWEEN 1 AND 8, p.stage_id BETWEEN 1 AND 8, if(f.stage_id = 13, p.stage_id IN(12, 13), p.stage_id = f.stage_id)))
        WHERE f.stage_id != 12 # Tercer Puesto
        GROUP BY 1, 2
        ORDER BY 1
    ";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($as_string) {
        $string = "";
        $separator = "";
        while ($record = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
            $record[2] = date("Y-m-d\TH:i",$record[2]);
            $record[3] = date("Y-m-d\TH:i",$record[3]);
            $string .= $separator . implode(",", $record);
            $separator = ";";
        }
        return $string;
    }
    else {
        $data = array();
        while ($data[] = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT));
        return $data;
    }
}

function processDataImport($DB, $sql, $prefix = "")
{
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    $string = "<br />";
    $count = 0;
    while ($data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
        $string .= "<span style=\"margin-left:10px;color:gray;\">$prefix{$data[0]}</span>";
        $count++;
    }
    return array($count, $string);
}

function getRecordsImport($DB)
{
    $sql = "SELECT COUNT(*) FROM empleados_import";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
    return $data[0];
}

function getIgnoredRecordsImport($DB)
{
    $sql = "SELECT linea FROM empleados_import WHERE '' IN(user, pass, nombre, apellido) ORDER BY linea";
    $data = processDataImport($DB, $sql, "#");
    $sql = "DELETE FROM empleados_import WHERE '' IN(user, pass, nombre, apellido)";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    return $data;
}

function getRepeatedUsersImport($DB)
{
    $sql = "SELECT DISTINCT user FROM empleados_import GROUP BY 1 HAVING COUNT(user) > 1 ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getInvalidUsersImport($DB)
{
    $sql = "SELECT DISTINCT user FROM empleados_import WHERE user NOT RLIKE '^[[:alnum:]|[.underscore.]]+[[:alnum:]|[.underscore.]|[.period.]|[.hyphen.]]+$' ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getInvalidLengthUsersImport($DB)
{
    $sql = "SELECT DISTINCT user FROM empleados_import WHERE length(user) < 4 OR length(user) > 100 ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getInvalidPasswordsImport($DB)
{
    $sql = "SELECT linea FROM empleados_import WHERE length(pass) < 6 OR length(pass) > 15 ORDER BY linea";
    $data = processDataImport($DB, $sql, "#");
    if ($data[0] > 0)
        $data[1] = str_replace("<br />", "<br /><i>líneas</i> =>", $data[1]);
    return $data;
}

function getInvalidFullNamesImport($DB, $part)
{
    $field = $part == 1 ? "nombre" : "apellido";
    $sql = "SELECT DISTINCT $field FROM empleados_import WHERE $field NOT RLIKE '^[a-zñáéíóú|0-9]+[a-zñáéíóú|0-9|[.space.]|[.period.]|[.hyphen.]|[.apostrophe.]]+$' ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getInvalidLengthFullNamesImport($DB, $part)
{
    $field = $part == 1 ? "nombre" : "apellido";
    $sql = "SELECT $field FROM empleados_import WHERE length($field) < 2 OR length($field) > 30 ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getRepeatedEmailsImport($DB)
{
    $sql = "SELECT DISTINCT correo_e FROM empleados_import GROUP BY 1 HAVING COUNT(correo_e) > 1 ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getInvalidEmailsImport($DB)
{
    $sql = "SELECT DISTINCT correo_e FROM empleados_import WHERE correo_e != '' AND correo_e NOT RLIKE '^[[:alnum:]|[.underscore.]]+([[.period.]][[:alnum:]|[.underscore.]]+)*@{1}[[:alnum:]|[.underscore.]]+([[.hyphen.]][[:alnum:]|[.underscore.]]+)*[[.period.]][a-z]{2,4}([[.period.]][a-z]{2})?$' ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getInvalidLengthEmailsImport($DB)
{
    $sql = "SELECT DISTINCT correo_e FROM empleados_import WHERE correo_e != '' AND length(correo_e) < 8 OR length(correo_e) > 100 ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getInvalidDepartmentsImport($DB)
{
    $sql = "SELECT linea FROM empleados_import WHERE departamento != '' AND (length(departamento) < 3 OR length(departamento) > 100) ORDER BY linea";
    $data = processDataImport($DB, $sql, "#");
    if ($data[0] > 0)
        $data[1] = str_replace("<br />", "<br /><i>líneas</i> =>", $data[1]);
    return $data;
}

function getInvalidUbicationsImport($DB)
{
    $sql = "SELECT linea FROM empleados_import WHERE ubicacion != '' AND (length(ubicacion) < 3 OR length(ubicacion) > 100) ORDER BY linea";
    $data = processDataImport($DB, $sql, "#");
    if ($data[0] > 0)
        $data[1] = str_replace("<br />", "<br /><i>líneas</i> =>", $data[1]);
    return $data;
}

function getRepeatedUsersCompanyImport($DB)
{
    $sql = "SELECT DISTINCT user FROM empleados_import JOIN empleados USING(id_empresa, user) ORDER BY linea";
    return processDataImport($DB, $sql);
}

function getRepeatedEmailsCompanyImport($DB)
{
    $sql = "SELECT DISTINCT correo_e FROM empleados_import JOIN empleados USING(id_empresa, correo_e) ORDER BY linea";
    return processDataImport($DB, $sql);
}

function insertEmployees($DB)
{
    $sql = "
        INSERT INTO empleados (
            id_empresa, user, pass, nombre, apellido, correo_e, departamento, ubicacion, state)
        SELECT id_empresa, user, md5(pass), nombre, apellido, correo_e, departamento, ubicacion, '1'
        FROM empleados_import
    ";
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    return $stmt->rowCount();
}

function setFeaturesImages($name, $width, $height, $size = -1)
{
    return array("name" => $name, "width" => $width, "height" => $height, "size" => $size);
}

function getDimensionsImage($type, $device)
{
    global $IMAGES_FEATURES;
    return "{$IMAGES_FEATURES[$type][$device]['width']}x{$IMAGES_FEATURES[$type][$device]['height']}";
}

function getDimensionsImageLogo($device)
{
    global $IMAGE_LOGO_INDEX;
    return getDimensionsImage($IMAGE_LOGO_INDEX, $device);
}

function getDimensionsImagePrize($device)
{
    global $IMAGE_PRIZE_INDEX;
    return getDimensionsImage($IMAGE_PRIZE_INDEX, $device);
}

function getDimensionsImageBanner($device)
{
    global $IMAGE_BANNER_INDEX;
    return getDimensionsImage($IMAGE_BANNER_INDEX, $device);
}

function getDimensionsImageSlider($device)
{
    global $IMAGE_SLIDER_INDEXES;
    return getDimensionsImage($IMAGE_SLIDER_INDEXES[0], $device);
}

function getNameImageDB($images, $type, $device)
{
    if (empty($images) || empty($type) || empty($device))
        return null;
    foreach ($images as $index => $record) {
        $key = "$type$device";
        if ($record["tipo_imagen"] == $key)
            return $record["nombre_archivo"];
    }
    return null;
}

function getNameImage($images, $type, $device)
{
    $filename = getNameImageDB($images, $type, $device);
    if (!empty($filename))
        return $filename;
    global $IMAGES_FEATURES;
    return $IMAGES_FEATURES[$type][$device]["name"];
}

function getNameImageLogo($images, $device)
{
    global $IMAGE_LOGO_INDEX;
    return getNameImage($images, $IMAGE_LOGO_INDEX, $device);
}

function getNameImagePrize($images, $device)
{
    global $IMAGE_PRIZE_INDEX;
    return getNameImage($images, $IMAGE_PRIZE_INDEX, $device);
}

function getNameImageBanner($images, $device)
{
    global $IMAGE_BANNER_INDEX;
    return getNameImage($images, $IMAGE_BANNER_INDEX, $device);
}

function getNameImageSlider($images, $device, $index)
{
    global $IMAGE_SLIDER_INDEXES;
    return getNameImage($images, $IMAGE_SLIDER_INDEXES[$index - 1], $device);
}

function getSizeImage($type, $device)
{
    global $IMAGES_FEATURES;
    return $IMAGES_FEATURES[$type][$device]["size"];
}

function getSizeImageLogo($device)
{
    global $IMAGE_LOGO_INDEX;
    return getSizeImage($IMAGE_LOGO_INDEX, $device);
}

function getSizeImagePrize($device)
{
    global $IMAGE_PRIZE_INDEX;
    return getSizeImage($IMAGE_PRIZE_INDEX, $device);
}

function getSizeImageBanner($device)
{
    global $IMAGE_BANNER_INDEX;
    return getSizeImage($IMAGE_BANNER_INDEX, $device);
}

function getSizeImageSlider($device)
{
    global $IMAGE_SLIDER_INDEXES;
    return getSizeImage($IMAGE_SLIDER_INDEXES[0], $device);
}

function getFeaturesImages($images, $type)
{
    global $IMAGE_COMPUTER_INDEX;
    global $IMAGE_MOBILE_INDEX;
    return array(
        "dimensions" => array(getDimensionsImage($type, $IMAGE_COMPUTER_INDEX), getDimensionsImage($type, $IMAGE_MOBILE_INDEX)),
        "filenames" => array(getNameImage($images, $type, $IMAGE_COMPUTER_INDEX), getNameImage($images, $type, $IMAGE_MOBILE_INDEX)),
        "sizes" => array(getSizeImage($type, $IMAGE_COMPUTER_INDEX), getSizeImage($type, $IMAGE_MOBILE_INDEX)),
        "types" => array("$type$IMAGE_COMPUTER_INDEX", "$type$IMAGE_MOBILE_INDEX")
    );
}

function getFeaturesImagesLogo($images)
{
    global $IMAGE_LOGO_INDEX;
    return getFeaturesImages($images, $IMAGE_LOGO_INDEX);
}

function getFeaturesImagesPrize($images)
{
    global $IMAGE_PRIZE_INDEX;
    return getFeaturesImages($images, $IMAGE_PRIZE_INDEX);
}

function getFeaturesImagesBanner($images)
{
    global $IMAGE_BANNER_INDEX;
    return getFeaturesImages($images, $IMAGE_BANNER_INDEX);
}

function getFeaturesImagesSlider($images, $index)
{
    global $IMAGE_SLIDER_INDEXES;
    return getFeaturesImages($images, $IMAGE_SLIDER_INDEXES[$index - 1]);
}

function getFeaturesImagesSliders($images)
{
    global $IMAGE_SLIDER_INDEXES;
    $data = array();
    for ($i = 1; $i <= 5; $i++)
        $data[$i] = getFeaturesImages($images, $IMAGE_SLIDER_INDEXES[$i - 1]);
    return $data;
}

function executeSQL($DB, $sql)
{
    $stmt = $DB->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    return ($stmt->rowCount() == 1);
}

function insertImageCompany($DB, $company_id, $type, $filename)
{
    $sql = "INSERT INTO empresas_imagenes VALUES ($company_id, '$type', '$filename')";
    return executeSQL($DB, $sql);
}

function updateImageCompany($DB, $company_id, $type, $filename)
{
    $sql = "SELECT * FROM empresas_imagenes WHERE id_empresa = $company_id AND nombre_archivo = '$filename'";
    if (executeSQL($DB, $sql))
        return true;
    $sql = "UPDATE empresas_imagenes SET nombre_archivo = '$filename' WHERE id_empresa = $company_id AND tipo_imagen = '$type'";
    return executeSQL($DB, $sql);
}

function getSizeScale($size_bytes)
{
    if ($size_bytes < 1024)
        return round($size_bytes, 0) . " B";
    elseif ($size_bytes < 1024 * 1024)
        return round($size_bytes / 1024, 1) . " KB";
    elseif ($size_bytes < 1024 * 1024 * 1024)
        return round($size_bytes / (1024 * 1024), 1) . " MB";
    else
        return round($size_bytes / (1024 * 1024 * 1024), 1) . " GB";
}

function setImageCompany($DB, $company_id, $type_index, $device_index, $directory, $file, &$message)
{
    if (empty($file["size"]))
        return true;

    global $IMAGES_FEATURES;
    $image_features = $IMAGES_FEATURES[$type_index][$device_index];
    list($width, $height) = getimagesize($file["tmp_name"]);
    if (($image_features["width"] != -1 && $image_features["width"] < $width) || ($image_features["height"] != -1 && $image_features["height"] < $height)) {
        $message = "Dimensiones ({$width}x{$height}) no válidas para" . $message;
        return false;
    }

    if ($image_features["size"] != -1 && $image_features["size"] < $file["size"]) {
        $message = "Tamaño (" . getSizeScale($file["size"]) . ") excede el valor permitido para" . $message;
        return false;
    }

    $filename = $image_features["name"] . "." . strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (move_uploaded_file($file["tmp_name"], "$directory$filename")) {
        $type = "$type_index$device_index";
        if (!updateImageCompany($DB, $company_id, $type, $filename)) {
            if (!insertImageCompany($DB, $company_id, $type, $filename)) {
                $message = "No se pudo registrar/actualizar" . $message;
                return false;
            }
        }
        return true;
    }
    else {
        $message = "No se pudo cargar" . $message;
        return false;
    }
}

function setImageCompanyLogoComputer($DB, $company_id, $directory, $file, &$message)
{
    $message = " logo en computadoras. <b>Archivo ignorado.</b><br />";
    global $IMAGE_LOGO_INDEX;
    global $IMAGE_COMPUTER_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_LOGO_INDEX, $IMAGE_COMPUTER_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function setImageCompanyLogoMobile($DB, $company_id, $directory, $file, &$message)
{
    $message = " logo en dispositivos móviles. <b>Archivo ignorado.</b><br />";
    global $IMAGE_LOGO_INDEX;
    global $IMAGE_MOBILE_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_LOGO_INDEX, $IMAGE_MOBILE_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function setImageCompanyPrizeComputer($DB, $company_id, $directory, $file, &$message)
{
    $message = " premio en computadoras. <b>Archivo ignorado.</b><br />";
    global $IMAGE_PRIZE_INDEX;
    global $IMAGE_COMPUTER_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_PRIZE_INDEX, $IMAGE_COMPUTER_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function setImageCompanyPrizeMobile($DB, $company_id, $directory, $file, &$message)
{
    $message = " premio en dispositivos móviles. <b>Archivo ignorado.</b><br />";
    global $IMAGE_PRIZE_INDEX;
    global $IMAGE_MOBILE_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_PRIZE_INDEX, $IMAGE_MOBILE_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function setImageCompanyBannerComputer($DB, $company_id, $directory, $file, &$message)
{
    $message = " banner en computadoras. <b>Archivo ignorado.</b><br />";
    global $IMAGE_BANNER_INDEX;
    global $IMAGE_COMPUTER_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_BANNER_INDEX, $IMAGE_COMPUTER_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function setImageCompanyBannerMobile($DB, $company_id, $directory, $file, &$message)
{
    $message = " banner en dispositivos móviles. <b>Archivo ignorado.</b><br />";
    global $IMAGE_BANNER_INDEX;
    global $IMAGE_MOBILE_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_BANNER_INDEX, $IMAGE_MOBILE_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function setImageCompanySliderComputer($DB, $company_id, $directory, $file, $index, &$message)
{
    $message = " slider #$index en computadoras. <b>Archivo ignorado.</b><br />";
    $index--;
    global $IMAGE_SLIDER_INDEXES;
    global $IMAGE_COMPUTER_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_SLIDER_INDEXES[$index], $IMAGE_COMPUTER_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function setImageCompanySliderMobile($DB, $company_id, $directory, $file, $index, &$message)
{
    $message = " slider #$index en dispositivos móviles. <b>Archivo ignorado.</b><br />";
    $index--;
    global $IMAGE_SLIDER_INDEXES;
    global $IMAGE_MOBILE_INDEX;
    $result = setImageCompany($DB, $company_id, $IMAGE_SLIDER_INDEXES[$index], $IMAGE_MOBILE_INDEX, $directory, $file, $message);
    if ($result)
        $message = "";
    return $result;
}

function getFeaturesImagesProfile($images)
{
    global $IMAGE_PROFILE_INDEX;
    return getFeaturesImages($images, $IMAGE_PROFILE_INDEX);
}

function setImageProfile($DB, $company_id, $username, $type_index, $device_index, $directory, $file, &$message, &$filename)
{
    if (empty($file["size"]))
        return true;

    global $IMAGES_FEATURES;
    $image_features = $IMAGES_FEATURES[$type_index][$device_index];
    list($width, $height) = getimagesize($file["tmp_name"]);
    if (($image_features["width"] != -1 && $image_features["width"] < $width) || ($image_features["height"] != -1 && $image_features["height"] < $height)) {
        $message = "Dimensiones ({$width}x{$height}) no válidas para" . $message;
        return false;
    }

    if ($image_features["size"] != -1 && $image_features["size"] < $file["size"]) {
        $message = "Tamaño (" . getSizeScale($file["size"]) . ") excede el valor permitido para" . $message;
        return false;
    }

    $filename = md5("$company_id$username$device_index") . "." . strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (!move_uploaded_file($file["tmp_name"], "$directory$filename")) {
        $message = "No se pudo cargar" . $message;
        return false;
    }
    else
        return true;
}

function setImageProfileComputer($DB, $company_id, $username, $directory, $file, &$message)
{
    $message = " la imagen de perfil en computadoras. <b>Archivo ignorado.</b><br />";
    $filename = "";
    global $IMAGE_PROFILE_INDEX;
    global $IMAGE_COMPUTER_INDEX;
    $result = setImageProfile($DB, $company_id, $username, $IMAGE_PROFILE_INDEX, $IMAGE_COMPUTER_INDEX, $directory, $file, $message, $filename);
    if ($result)
        $message = "";
    return $filename;
}
?>