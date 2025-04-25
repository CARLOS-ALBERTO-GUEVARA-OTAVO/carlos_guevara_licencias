<?php
require '../vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorPNG;

$codigo_barras = '7703336004959'; // The barcode value you inserted via SQL

// Generate barcode image
$generator = new BarcodeGeneratorPNG();
$barcodeImage = $generator->getBarcode($codigo_barras, $generator::TYPE_CODE_128);

// Save barcode image to repuestos/barcodes/
$barcodeDir = "barcodes";
$barcodeFilePath = "$barcodeDir/{$codigo_barras}.png";
if (!is_dir($barcodeDir)) {
    mkdir($barcodeDir, 0755, true);
}
file_put_contents($barcodeFilePath, $barcodeImage);

echo "Barcode generated for $codigo_barras at $barcodeFilePath";
?>