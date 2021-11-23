<?php
use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\Model\Product;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/vendor/autoload.php");

$xls = \PhpOffice\PhpSpreadsheet\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/upload/price.xls');
$xls->setActiveSheetIndex(0);
$sheet = $xls->getActiveSheet();
$rowIterator = $sheet->getRowIterator();
foreach ($rowIterator as $iterator) {
    $row = array();
    $cellIterator = $iterator->getCellIterator();
    foreach ($cellIterator as $cell) {
        $row[] = $cell->getCalculatedValue();
    }
    $stringNumber++;
    if (!$row[6] && !$row[7] && !$row[8]) continue;
    if ($row[4] == 'Номенклатура' || !$row[4]) continue;
    $iblock = new CIBlockElement();
    $product_id = $iblock->Add([
        'IBLOCK_ID' => 2,
        'NAME' => $row[4],
        'CODE' => $row[1]
    ]);
    if (!$product_id) continue;
    
    $result1 = Price::add([
        'PRODUCT_ID' => $product_id,
        'CATALOG_GROUP_ID' => 1,
        'PRICE' => $row[7],
        'CURRENCY' => 'RUB'
    ]);
    $result2 = Price::add([
        'PRODUCT_ID' => $product_id,
        'CATALOG_GROUP_ID' => 2,
        'PRICE' => $row[6],
        'CURRENCY' => 'RUB'
    ]);
    $result3 = Price::add([
        'PRODUCT_ID' => $product_id,
        'CATALOG_GROUP_ID' => 3,
        'PRICE' => $row[8],
        'CURRENCY' => 'RUB'
    ]);
}