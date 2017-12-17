<?php
namespace Iurapp;

require __DIR__ . '/../vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class Impresora {
	public function __construct(){}

	public function imprimir($data) {
		$connector = new FilePrintConnector("php://stdout");
		// $connector = "SP_500";
		// $connector = new WindowsPrintConnector($connector);
		$printer = new Printer($connector);
		$printer -> text("Hello World!\n");
		$printer -> cut();
		$printer -> close();
	}

	public function test() {
		/* Open file */
		$tmpdir = sys_get_temp_dir();
		$file =  tempnam($tmpdir, 'ctk');
		/* Do some printing */
		$connector = new FilePrintConnector($file);
		$printer = new Printer($connector);
		$printer -> text("Hello World!\n");
		$printer -> cut();
		$printer -> close();

		/* Copy it over to the printer */
		// var_dump($file);die();
		$date = date("YmdHis");
		copy($file, "xs_$date.txt");
		unlink($file);
	}
}