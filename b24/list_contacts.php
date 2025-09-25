<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/config.php';
require __DIR__ . '/helper.php';
b24_require_token();
try {
    $namePrefix = isset($_GET['name_prefix']) ? trim($_GET['name_prefix']) : 'Test';
    $contacts = b24_call('crm.contact.list', [
        'filter' => ['%NAME' => $namePrefix, '%LAST_NAME' => 'Contact'],
        'select' => ['ID','NAME','LAST_NAME','PHONE','EMAIL']
    ]);
    $out = [];
    foreach ($contacts as $c) {
        $cid = (int)$c['ID'];
        $deals = b24_call('crm.deal.list', [
            'filter' => ['CONTACT_ID' => $cid],
            'select' => ['ID'],
        ]);
        $dealIds = array_map(fn($d) => (int)$d['ID'], $deals);
        $phone = (!empty($c['PHONE']) && is_array($c['PHONE'])) ? ($c['PHONE'][0]['VALUE'] ?? null) : null;
        $email = (!empty($c['EMAIL']) && is_array($c['EMAIL'])) ? ($c['EMAIL'][0]['VALUE'] ?? null) : null;
        $out[] = [
            'id' => $cid,
            'fio' => trim(($c['NAME'] ?? '') . ' ' . ($c['LAST_NAME'] ?? '')),
            'phone' => $phone,
            'email' => $email,
            'deal_count' => count($dealIds),
            'deal_ids' => $dealIds,
        ];
    }
    echo json_encode(['ok'=>true,'contacts'=>$out], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
}
