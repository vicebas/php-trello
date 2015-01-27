<?php

namespace Trello\Session;


interface SessionHandlerInterface
{
    public function set($key, $value);

    public function get($key, $default = null);
}
