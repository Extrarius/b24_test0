<?php
if (!defined('B24_WEBHOOK_BASE')) {
    http_response_code(500);
    die(json_encode(['ok'=>false,'error'=>'Please create config.php with B24_WEBHOOK_BASE']));
}
function b24_call($method, $params = []) {
    $url = rtrim(B24_WEBHOOK_BASE, '/') . '/' . $method . '.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $resp = curl_exec($ch);
    if ($resp === false) { $err = curl_error($ch); curl_close($ch); throw new Exception('cURL error: ' . $err); }
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = json_decode($resp, true);
    if ($code >= 400 || (isset($data['error']) && $data['error'])) {
        throw new Exception('B24 error: ' . ($data['error_description'] ?? $data['error'] ?? 'Unknown'));
    }
    return $data['result'] ?? $data;
}
function b24_require_token() {
    if (defined('B24_SHARED_TOKEN') && B24_SHARED_TOKEN !== '') {
        $got = $_GET['token'] ?? '';
        if (!hash_equals(B24_SHARED_TOKEN, $got)) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok'=>false,'error'=>'Forbidden: token mismatch'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
function rand_phone() { return '+79' . str_pad((string)random_int(100000000, 999999999), 9, '0', STR_PAD_LEFT); }
function rand_email($i) { return 'test' . $i . '.' . random_int(100,999) . '@example.org'; }
