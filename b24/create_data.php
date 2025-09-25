<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/config.php';
require __DIR__ . '/helper.php';
b24_require_token();
try {
    $contactIds = [];
    for ($i = 1; $i <= 5; $i++) {
        $fields = [
            'NAME' => 'Test',
            'LAST_NAME' => 'Contact ' . $i,
            'OPENED' => 'Y',
            'TYPE_ID' => 'CLIENT',
            'SOURCE_ID' => 'SELF',
            'PHONE' => [['VALUE' => rand_phone(), 'VALUE_TYPE' => 'WORK']],
            'EMAIL' => [['VALUE' => rand_email($i), 'VALUE_TYPE' => 'WORK']],
        ];
        $id = b24_call('crm.contact.add', ['fields' => $fields]);
        $contactIds[] = $id;
    }
    $dealIds = [];
    for ($j = 1; $j <= 15; $j++) {
        $cid = $contactIds[array_rand($contactIds)];
        $fields = [
            'TITLE' => 'Test Deal #' . $j,
            'CATEGORY_ID' => 0,
            'STAGE_ID' => 'NEW',
            'OPENED' => 'Y',
            'CONTACT_ID' => $cid,
        ];
        $dealIds[] = b24_call('crm.deal.add', ['fields' => $fields]);
    }
    echo json_encode(['ok'=>true,'contacts_created'=>$contactIds,'deals_created'=>$dealIds], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
}
