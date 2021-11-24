<?php
namespace  Dev\Import;

use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\Model\Product;
use Bitrix\Main\Loader;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/vendor/autoload.php");
Loader::includeModule('iblock');

class Import{
    private $iterator;
    
    public function __construct($filePath){
        $xls = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $this->iterator = $sheet->getRowIterator();
    }
    
    public function execute(){
        foreach ($this->iterator as $iterator) {
            $row = array();
            $cellIterator = $iterator->getCellIterator();
            foreach ($cellIterator as $cell) {
                $row[] = $cell->getCalculatedValue();
            }
            if ($row[4] == 'Номенклатура' || !$row[4]) continue;
            if (self::isSection($row)){
                $section = \CIBlockSection::GetList([], [
                    'NAME' => $row[4]
                ])->Fetch();
                if (empty($section)){
                    $sectionObject = new \CIBlockSection();
                    $section_id = $sectionObject->Add([
                        'NAME' => $row[4],
                        'IBLOCK_ID' => 2,
                        'CODE' => \CUtil::translit($row[4], 'ru')
                    ]);
                } else{
                    $section_id = $section['ID'];
                }
                continue;
            }
            
            if (!$section_id) continue;
            
            $iblock = new \CIBlockElement();
            $product_id = $iblock->Add([
                'IBLOCK_ID' => 2,
                'NAME' => $row[4],
                'CODE' => $row[1],
                'IBLOCK_SECTION_ID' => $section_id
            ]);
            if (!$product_id) continue;
            
            $result0 = Product::add([
                'ID' => $product_id,
                'AVAILABLE' => 'Y',
                'QUANTITY_TRACE' => 'N',
                'CAN_BUY_ZERO' => 'Y'
            ]);
            
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
    }
    
    public static function isSection($row){
        if (
            is_numeric($row[0]) &&
            !$row[1] &&
            !$row[2] &&
            !$row[3] &&
            $row[4] &&
            !$row[5] &&
            !$row[6] &&
            !$row[7] &&
            !$row[8]
        ) return true;
        return false;
    }
    
}
