<?php

require_once 'config.php';

$result = $trello->lists->get('4d5ea62fd76aa1136000001d/actions', array('since' => '2013-07-30'));

if ($result === false) {
    echo "Test Failed: " . $trello->error();
} else {
    echo "Test succeeded";
}
