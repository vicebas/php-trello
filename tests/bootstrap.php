<?php

if (!is_readable(__DIR__ . '/keys.json')
    || !($keys = json_decode(file_get_contents(__DIR__ . '/keys.json')))
    || empty($keys->key)
    || empty($keys->secret)) {
    echo "keys.json file must exist with a key and secret.\n";
    exit(1);
}

if (empty($keys->token)) {
    $keys->token = null;
}
if (empty($keys->oauth_secret)) {
    $keys->oauth_secret = null;
}

require_once __DIR__ . '/../src/Trello/Trello.php';
