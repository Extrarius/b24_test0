<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Фильтр новостей");

// Подключаем наш компонент
$APPLICATION->IncludeComponent('custom:news.filter.list', '', [
  'IBLOCK_ID' => 1,
  'AJAX_MODE' => 'Y',         // применяет фильтр без полной перезагрузки
  'AJAX_OPTION_HISTORY' => 'Y',
  'AJAX_OPTION_STYLE'   => 'Y',
  'AJAX_OPTION_JUMP'    => 'N',
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
