<?php

const DEVELOPER = '@ali_ceder';

function respond(array $payload, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

function fail($message, $status = 500)
{
    respond(['error' => ['text' => $message, 'developer' => DEVELOPER]], $status);
}

function loadJson($file)
{
    if (!is_file($file) || filesize($file) === 0) {
        fail('Data file not found', 404);
    }

    $data = json_decode(file_get_contents($file), true);

    if (!is_array($data) || !$data) {
        fail('Data file is invalid or empty', 500);
    }

    return $data;
}

function pick(array $items)
{
    return $items[array_rand($items)];
}

function respondText($key, $text)
{
    respond([$key => ['text' => trim($text), 'developer' => DEVELOPER]]);
}

function respondRandomText($key, $file)
{
    respondText($key, pick(loadJson($file)));
}

respondRandomText('dialog', __DIR__ . '/dialog.json');
