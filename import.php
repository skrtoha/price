<?php
use Bitrix\Catalog\Model\Price;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('dev.import');

$importObject = new \Dev\Import\Import($_SERVER['DOCUMENT_ROOT'].'/upload/price.xls');
$importObject->execute();