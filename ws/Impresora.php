<?php
namespace Iurapp;

require __DIR__ . '/../vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

require "Functions.php";

class Impresora {
	const NL = "\n";
	const BOLETA = "BOLETA";
	const FACTURA = "FACTURA";

	public function __construct(){}

	public function imp() {
		var_dump( printer_list(PRINTER_ENUM_LOCAL | PRINTER_ENUM_SHARED) );
	}

	public function imprimir($data) {
		// $connector = new FilePrintConnector("php://stdout");
		// var_dump(pstring("hsgdhagshdgashdgahsgdhasgdhasgdhaskdhasjkdashdgashgdahsgashdgashdgahsdgas"));die();
		$pos = $data['pos_info']; 
		$imprimir = $data['data_print']; 
		$impresora = $pos['nombre']; // este es el nombre de la impresora, para esto es requisito que la impresora esté compartida
		$connector = new WindowsPrintConnector($impresora);
		$printer = new Printer($connector);
		$this->templateComprobante($data, $printer, $imprimir['datos_venta']['tipo_comprobante']);
		$printer -> feed(3); // para cortar en impresora Star SP500
		// $printer -> pulse();
		$printer -> close();
		return $printer;
	}

	public function templateComprobante( $data, &$printer, $tipo = Impresora::BOLETA ) {
		$tipo = (strpos($tipo, $this::BOLETA)!==false)? $this::BOLETA : trim(strtoupper($tipo));
		$tipo = (strpos($tipo, $this::FACTURA)!==false)? $this::FACTURA : trim(strtoupper($tipo));
		$pos = $data['pos_info']; 
		$imprimir = $data['data_print']; 
		$empresa = $imprimir['datos_empresa'];
		$venta = $imprimir['datos_venta'];
		$cliente = $imprimir['datos_cliente'];
		switch ($tipo) {
			case $this::BOLETA:
				$printer->text( pstring($empresa['razon_social'], $pos['columnas']) . $this::NL);
				$printer->text( pstring($empresa['ruc'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("TIENDA: " . $empresa['direccion_local'], $pos['columnas']) . $this::NL);
				// $printer->text( pstring("DOMICILIO FISCAL: " . $empresa['direccion_fiscal']) . $this::NL . $this::NL);
				// $printer->text( pstring($venta['tipo_comprobante']) . $this::NL);
				// $printer->text( pstring($venta['nro_comprobante']) . $this::NL . $this::NL);
				// $printer->text( pstring("Fecha Emisión: ". $venta['fecha_emision']) . $this::NL);
				// $printer->text( pstring("Cliente: ". $cliente['nombre']) . $this::NL);
				// $printer->text( pstring($cliente['tipo_documento'] . ":" . $cliente['nro_documento']) . $this::NL);
				break;
			case $this::FACTURA:
				# code...
				break;
			default:
				break;
		}
		return $printer;
	}
}