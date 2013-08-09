<?php

require_once 'config.php';

$result = $trello->put('cards/7uDI46kM/labels', array('value' => 'green'));

if ($result === false) {
    echo "Test Failed: " . $trello->error();
} else {
    echo "Test succeeded";
}
