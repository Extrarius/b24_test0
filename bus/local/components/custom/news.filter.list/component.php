<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;

if (!Loader::includeModule('iblock')) { ShowError('Нужен модуль iblock'); return; }

$iblockId = intval($arParams['IBLOCK_ID'] ?? 0);
if ($iblockId <= 0) { ShowError('Укажите IBLOCK_ID'); return; }

$request = Context::getCurrent()->getRequest();
$q      = trim((string)$request->get('q'));          // поиск по NAME
$cost   = trim((string)$request->get('cost'));       // точное совпадение COST
$period = (string)$request->get('period');
if (!in_array($period, ['all','month','week'], true)) $period = 'all';

$filter = [ 'IBLOCK_ID'=>$iblockId, 'ACTIVE'=>'Y' ];
//if ($q !== '') $filter['%NAME'] = $q;
if ($q !== '') {
    $filter[] = [
        'LOGIC' => 'OR',
        ['%NAME' => $q],     
        ['?PROPERTY_FEATURE' => $q],
    ];
}
if ($cost !== '' && is_numeric($cost)) $filter['=PROPERTY_COST'] = (int)$cost;

$format = 'Y-m-d H:i:s';
if ($period === 'month') {
  $from = new DateTime(date('Y-m-01 00:00:00'), $format);
  $filter['>=DATE_ACTIVE_FROM'] = $from;
} elseif ($period === 'week') {
  $monday = new \DateTime('monday this week');
  $from   = new DateTime($monday->format('Y-m-d 00:00:00'), $format);
  $filter['>=DATE_ACTIVE_FROM'] = $from;
}

$select = ['ID','NAME','DATE_ACTIVE_FROM','DETAIL_PAGE_URL','PROPERTY_COST','PROPERTY_FEATURE'];
$sort   = ['DATE_ACTIVE_FROM'=>'DESC','ID'=>'DESC'];

$arResult['FILTER'] = ['q'=>$q,'cost'=>$cost,'period'=>$period];
$arResult['ITEMS']  = [];

$res = CIBlockElement::GetList($sort, $filter, false, false, $select);
while ($el = $res->GetNext()) {
  $arResult['ITEMS'][] = [
    'ID'=>$el['ID'],
    'NAME'=>$el['~NAME'],
    'DATE_ACTIVE_FROM'=>$el['DATE_ACTIVE_FROM'],
    'DETAIL_PAGE_URL'=>$el['DETAIL_PAGE_URL'],
    'COST'=>$el['PROPERTY_COST_VALUE'],
    'FEATURE'=>$el['PROPERTY_FEATURE_VALUE'],
  ];
}

$this->IncludeComponentTemplate();
