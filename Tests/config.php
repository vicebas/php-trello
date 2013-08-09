<?php

if (!session_id()) {
    session_start();
}

require_once '../Trello/Trello.php';

use \Trello\Trello;

$key = '';
$secret = '';
$trello = new Trello(
    $key, 
    $secret, 
    !empty($_SESSION['oauth_token']) ? $_SESSION['oauth_token'] : null,
    !empty($_SESSION['oauth_secret']) ? $_SESSION['oauth_secret'] : null);

$trello->authorize(array(
    'name' => 'php-trello Test',
    'expiration' => '1hour',
    'scope' => array(
        'read' => true,
        'write' => true,
        'account' => true
    )
));

$_SESSION['oauth_token'] = $trello->token();
$_SESSION['oauth_secret'] = $trello->oauthSecret();

