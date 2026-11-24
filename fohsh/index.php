<?php

const DEVELOPER = '@ali_ceder';

function respond(array $payload, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

$text = $_POST['text'] ?? $_GET['text'] ?? '';
$text = is_string($text) ? trim($text) : '';

if ($text === '') {
    respond([
        'error' => 'متن ارسال نشده است',
        'success' => false,
        'developer_id' => null,
        'keywords_found' => false,
    ], 400);
}

$words = json_decode(file_get_contents(__DIR__ . '/words.json'), true) ?: [];
$normalized = strtr($text, ['ي' => 'ی', 'ك' => 'ک']);

$found = array_values(array_filter($words, function ($word) use ($normalized) {
    return mb_stripos($normalized, $word) !== false;
}));

respond([
    'success' => true,
    'developer_id' => DEVELOPER,
    'keywords_found' => !empty($found),
    'input_text' => $text,
    'found_keywords' => $found,
]);
