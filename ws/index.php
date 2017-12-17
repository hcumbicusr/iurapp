<?php
/**
 * IurApp @2017 
 * Fecha: 2017-12-16
 * @author Henry Cumbicus <hcumbicusr@gmail.com>
 * @package iurapp
 * Router de Peticiones
*/
//- Recordar habilitar ["mbstring"] en PHP.ini

header('Access-Control-Allow-Origin: *');

if (empty($_POST)) {
	$json = file_get_contents("php://input");
	$request = json_decode($json, true);
} else {
	$request = $_POST;
}
$http_code = 200;
if ( !empty($request) ) {
	error_log(print_r($request, true));
	$accion = $request['accion'];
	switch ($accion) {
		case 'imprimir':
			$response = ["status" => "success", "message" => $request];
			break;
		
		default:
			# code...
			break;
	}
} else {
	$response = ["status" => "error", "message" => "Acción no encontrada."];
	$http_code = 404;
}
// echo json_encode($response);
// http_response_code($http_code);
// exit;
require 'Impresora.php';
$o = new Iurapp\Impresora();
$o->imprimir(1);
// $o->test();