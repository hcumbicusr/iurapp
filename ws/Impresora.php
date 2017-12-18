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
	$pos = $data['pos_info']; 
	$imprimir = $data['data_print']; 
	$impresora = $pos['nombre']; // este es el nombre de la impresora, para esto es requisito que la impresora esté compartida
	$tmpdir = sys_get_temp_dir();
	$file =  tempnam($tmpdir, 'ctk');
	$connector = new FilePrintConnector($file);
	// $connector = new WindowsPrintConnector($impresora);
	$printer = new Printer($connector);
	$this->templateComprobante($data, $printer, $imprimir['datos_venta']['tipo_comprobante']);
	$printer -> feed(3); // para cortar en impresora Star SP500
	// $printer -> pulse();
	$printer -> close();
	copy($file, "print.txt");
	unlink($file);
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
		$items = $imprimir['items'];
		$totales = $imprimir['totales'];
		$datos_pago = $imprimir['datos_pago'];
		$datos_venta = $imprimir['datos_venta'];
		switch ($tipo) {
			case $this::BOLETA:
				$printer->text( pstring($empresa['razon_social'], $pos['columnas']) . $this::NL);
				$printer->text( pstring($empresa['ruc'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("TIENDA: " . $empresa['direccion_local'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("DOMICILIO FISCAL: " . $empresa['direccion_fiscal'], $pos['columnas']) . $this::NL . $this::NL);
				$printer->text( pstring($venta['tipo_comprobante'], $pos['columnas']) . $this::NL);
				$printer->text( pstring($venta['nro_comprobante'], $pos['columnas']) . $this::NL . $this::NL);
				$printer->text( pstring("Fecha Emisión: ". $venta['fecha_emision'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("Cliente: ". $cliente['nombre'], $pos['columnas']) . $this::NL);
				$printer->text( pstring($cliente['tipo_documento'] . ":" . $cliente['nro_documento'], $pos['columnas']) . $this::NL . $this::NL . $this::NL);
				$printer->text( pstring("________________________________________", $pos['columnas']) . $this::NL);				
				$printer -> setTextSize(1,1);				
				$printer->text( pstring("COD. DESCRIPCIÓN  CANT.  P.UNIT  DSCTO.  IMPORTE" , $pos['columnas']) . $this::NL);				
				$printer->text( pstring("________________________________________", $pos['columnas']) . $this::NL);
				$printer -> setTextSize(1,1);				
				foreach ($items as $key => $value) {
					$printer->text( pstring($value['codigo'] ."  ". $value['descripcion'] ."   ". $value['cantidad'] ."  ". $value['precio_unitario'] ." " . $value['descuento'] ." "  . $value['importe'], $pos['columnas']) . $this::NL);
				}
				$printer->text( pstring("----------------------------------------", $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 TOTAL A PAGAR: " . $totales['importe_total'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("----------------------------------------", $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 OP. GRAVADAS: " . $totales['operacion_gravada'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 " . $totales['impuesto_nombre'] .":    " . $totales['impuesto_valor'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 DSCTO:  "  . $totales['descuento'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 IMPORTE TOTAL: " . $totales['importe_total'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("SON: " . $totales['importe_total_texto'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("----------------------------------------", $pos['columnas']) . $this::NL);
				$printer->text( pstring("*************MEDIOS DE PAGOS************", $pos['columnas']) . $this::NL);
				$printer->text( pstring("PAGO: " . $datos_pago['medio_pago']. "   MONEDA: " . $datos_pago['moneda_pago'] ." (".$datos_pago['simbolo_moneda'].")", $pos['columnas']) . $this::NL);
				$printer->text( pstring("CAMBIO: " . $datos_pago['vuelto'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("VENDEDOR: " . $datos_venta['vendedor'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("CAJA: " . $datos_venta['caja'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("CAJERO: " . $datos_venta['cajero'], $pos['columnas']) . $this::NL);
				break;
			case $this::FACTURA:
				$printer->text( pstring($empresa['razon_social'], $pos['columnas']) . $this::NL);
				$printer->text( pstring($empresa['ruc'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("TIENDA: " . $empresa['direccion_local'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("DOMICILIO FISCAL: " . $empresa['direccion_fiscal'], $pos['columnas']) . $this::NL . $this::NL);
				$printer->text( pstring($venta['tipo_comprobante'], $pos['columnas']) . $this::NL);
				$printer->text( pstring($venta['nro_comprobante'], $pos['columnas']) . $this::NL . $this::NL);
				$printer->text( pstring("Fecha Emisión: ". $venta['fecha_emision'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("Cliente: ". $cliente['nombre'], $pos['columnas']) . $this::NL);
				$printer->text( pstring($cliente['tipo_documento'] . ":" . $cliente['nro_documento'], $pos['columnas']) . $this::NL . $this::NL . $this::NL);
				$printer->text( pstring("________________________________________", $pos['columnas']) . $this::NL);				
				$printer -> setTextSize(1,1);				
				$printer->text( pstring("COD. DESCRIPCIÓN  CANT.  P.UNIT  DSCTO.  IMPORTE" , $pos['columnas']) . $this::NL);				
				$printer->text( pstring("________________________________________", $pos['columnas']) . $this::NL);
				$printer -> setTextSize(1,1);				
				foreach ($items as $key => $value) {
					$printer->text( pstring($value['codigo'] ."  ". $value['descripcion'] ."   ". $value['cantidad'] ."  ". $value['precio_unitario'] ." " . $value['descuento'] ." "  . $value['importe'], $pos['columnas']) . $this::NL);
				}
				$printer->text( pstring("----------------------------------------", $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 TOTAL A PAGAR: " . $totales['importe_total'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("----------------------------------------", $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 OP. GRAVADAS: " . $totales['operacion_gravada'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 " . $totales['impuesto_nombre'] .":    " . $totales['impuesto_valor'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 DSCTO:  "  . $totales['descuento'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("->                 IMPORTE TOTAL: " . $totales['importe_total'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("SON: " . $totales['importe_total_texto'], $pos['columnas']) . $this::NL);	
				$printer->text( pstring("----------------------------------------", $pos['columnas']) . $this::NL);
				$printer->text( pstring("*************MEDIOS DE PAGOS************", $pos['columnas']) . $this::NL);
				$printer->text( pstring("PAGO: " . $datos_pago['medio_pago']. "   MONEDA: " . $datos_pago['moneda_pago'] ." (".$datos_pago['simbolo_moneda'].")", $pos['columnas']) . $this::NL);
				$printer->text( pstring("CAMBIO: " . $datos_pago['vuelto'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("VENDEDOR: " . $datos_venta['vendedor'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("CAJA: " . $datos_venta['caja'], $pos['columnas']) . $this::NL);
				$printer->text( pstring("CAJERO: " . $datos_venta['cajero'], $pos['columnas']) . $this::NL);
				break;
			default:
				break;
		}
		return $printer;
	}
}