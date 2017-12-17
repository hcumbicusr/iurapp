<?php
/**
 * IurApp @2017 
 * Fecha: 2017-12-16
 * @author Henry Cumbicus <hcumbicusr@gmail.com>
 * @package iurapp
 * Este archivo contiene las variables de configuracion de la aplicación
*/
header('Content-Type: text/html; charset=UTF-8');

ini_set("error_log", "log.txt");

/**
 * @var charset
 */
$config['charset']= 'UTF-8';
/**
 * @var language
 */
$config['lang']= 'es-ES';

/**
 * @var entorno : D-> Desarrollo; P-> Produccion
 */
$config['entorno']= 'D';

if ($config['entorno'] == 'D')
{
    ini_set("display_errors", true);
    error_reporting(-1);
}elseif($config['entorno'] == 'P')
{
    ini_set("display_errors", false);
    error_reporting(0);
}

//Configuracion de la fecha segun la region
date_default_timezone_set('America/Lima');
setlocale(LC_ALL,"es_ES");

/**
*@var name
*/
$config['titleApp']="IurApp Print";
/**
*@var version
*/
$config['version']="v1";
/**
*@var name
*/
$config['nameApp']="iurapp_print";
/**
 * @var email developer
 */
$config['emailDeveloper']="hcumbicusr@gmail.com";
/**
 * @var emails group developers
 */
$config['teamDeveloper'] = array(
    "hcumbicusr" => "hcumbicusr"
);

/**
* @var accessBD n1
*/
$config['accessBD'] = array(
   "DB_HOST" => "",
   "DB_PORT" => "",
   "DB_USER" => "",
   "DB_PASS" => "",
   "DB_NAME" => ""
);
$db = file_get_contents(".env");
$db = explode("\n", $db);
foreach ($db as $key => $value) {
  @list($k,$v) = @explode("=", $value);
  $config['accessBD'][$k] = trim($v);
}
require_once 'core/MySqliClass.php';