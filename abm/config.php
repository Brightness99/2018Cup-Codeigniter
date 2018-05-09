<?php
/*
 * @author Shahrukh Khan
 * @website http://www.thesoftwareguy.in
 * @facebbok https://www.facebook.com/Thesoftwareguy7
 * @twitter https://twitter.com/thesoftwareguy7
 * @googleplus https://plus.google.com/+thesoftwareguyIn
 */

error_reporting( E_ALL & ~E_DEPRECATED & ~E_NOTICE );
ob_start();
session_start();

define('DB_DRIVER', 'mysql');
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'todosalacancha');
define('DB_SERVER_PASSWORD', 'todos2018');
define('DB_DATABASE', 'todosalacancha');


define('PROJECT_NAME', 'Rusia 2018 - Pronóstico del Mundial');
$dboptions = array(
              PDO::ATTR_PERSISTENT => FALSE,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
              PDO::MYSQL_ATTR_LOCAL_INFILE => true
            );

try {
  $DB = new PDO(DB_DRIVER.':host='.DB_SERVER.';dbname='.DB_DATABASE, DB_SERVER_USERNAME, DB_SERVER_PASSWORD , $dboptions);
} catch (Exception $ex) {
  echo $ex->getMessage();
  die;
}

require_once 'functions.php';

//get error/success messages
if ($_SESSION["errorType"] != "" && $_SESSION["errorMsg"] != "" ) {
    $ERROR_TYPE = $_SESSION["errorType"];
    $ERROR_MSG = $_SESSION["errorMsg"];
    $_SESSION["errorType"] = "";
    $_SESSION["errorMsg"] = "";
}

$imgPerfil  = '../img/empleadosPerfil/';
$imgPathEquipo = '../img/equiposBanderas/';
$pathExcelEmpleados = 'excelUpload/';
$excelEjemplo = 'empleados_ejemplo.csv';

$DIR_IMAGES_COMPANIES = "../img/";

$TRIVIA_QUESTIONS = 3;
$TRIVIA_QUESTIONS_ANSWERS = 3;

$ONLINE = true;

//OP => images' features (companies)
$IMAGE_LOGO_INDEX = 1;
$IMAGE_PRIZE_INDEX = 2;
$IMAGE_BANNER_INDEX = 3;
$IMAGE_SLIDER_INDEXES = array(4, 5, 6, 7, 8);

$IMAGE_LOGO_NAME = "logo";
$IMAGE_PRIZE_NAME = "premio";
$IMAGE_BANNER_NAME = "banner";
$IMAGE_SLIDER_NAMES = array("slider1", "slider2", "slider3", "slider4", "slider5");

$IMAGE_COMPUTER_INDEX = 1;
$IMAGE_MOBILE_INDEX = 2;

$IMAGE_COMPUTER_PREFIX = "pc_";
$IMAGE_MOBILE_PREFIX = "m_";

$IMAGES_FEATURES = array();

//OP => syntax
/*
$IMAGES_FEATURES[index-image][index-device] = setFeaturesImages(name-image-by-default, max-width-image, max-height-image, max-size-image);

index-image: image's index by type (see comment on field "empresas_imagenes.tipo_imagen")
index-device: image's index by device, computer or mobile (see comment on field "empresas_imagenes.tipo_imagen")
name-image-by-default: image's name assigned by type and device (pc_logo, m_banner, etc.)
max-width-image: image's width (-1 unlimited)
max-height-image: image's height (-1 unlimited)
max-size-image: maximum image's size permitted, in bytes (-1 = unlimited, value by default)
*/

//OP => logo
$IMAGES_FEATURES[$IMAGE_LOGO_INDEX][$IMAGE_COMPUTER_INDEX] = setFeaturesImages("$IMAGE_COMPUTER_PREFIX$IMAGE_LOGO_NAME", 600, 360);//pc
$IMAGES_FEATURES[$IMAGE_LOGO_INDEX][$IMAGE_MOBILE_INDEX] = setFeaturesImages("$IMAGE_MOBILE_PREFIX$IMAGE_LOGO_NAME", 600, 360);//mobile

//OP => prize
$IMAGES_FEATURES[$IMAGE_PRIZE_INDEX][$IMAGE_COMPUTER_INDEX] = setFeaturesImages("$IMAGE_COMPUTER_PREFIX$IMAGE_PRIZE_NAME", 1920, 1080);//pc
$IMAGES_FEATURES[$IMAGE_PRIZE_INDEX][$IMAGE_MOBILE_INDEX] = setFeaturesImages("$IMAGE_MOBILE_PREFIX$IMAGE_PRIZE_NAME", 500, 800);//mobile

//OP => banner
$IMAGES_FEATURES[$IMAGE_BANNER_INDEX][$IMAGE_COMPUTER_INDEX] = setFeaturesImages("$IMAGE_COMPUTER_PREFIX$IMAGE_BANNER_NAME", 600, 360);//pc
$IMAGES_FEATURES[$IMAGE_BANNER_INDEX][$IMAGE_MOBILE_INDEX] = setFeaturesImages("$IMAGE_MOBILE_PREFIX$IMAGE_BANNER_NAME", 600, 360);//mobile

//OP => slider
for ($i = 0; $i < count($IMAGE_SLIDER_INDEXES); $i++) { 
    $IMAGES_FEATURES[$IMAGE_SLIDER_INDEXES[$i]][$IMAGE_COMPUTER_INDEX] = setFeaturesImages("$IMAGE_COMPUTER_PREFIX{$IMAGE_SLIDER_NAMES[$i]}", 1600, 770, 512000);//pc
    $IMAGES_FEATURES[$IMAGE_SLIDER_INDEXES[$i]][$IMAGE_MOBILE_INDEX] = setFeaturesImages("$IMAGE_MOBILE_PREFIX{$IMAGE_SLIDER_NAMES[$i]}", 354, 600, 512000);//mobile
}

//OP => images' features (employees)
$IMAGE_PROFILE_INDEX = 10;

$IMAGES_FEATURES[$IMAGE_PROFILE_INDEX][$IMAGE_COMPUTER_INDEX] = setFeaturesImages("", 200, 200);//pc
$IMAGES_FEATURES[$IMAGE_PROFILE_INDEX][$IMAGE_MOBILE_INDEX] = setFeaturesImages("", 200, 200);//mobile
?>
