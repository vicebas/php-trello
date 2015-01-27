<?php

namespace Trello\Session;

class PhpSessionHandler implements SessionHandlerInterface
{
    public function __construct()
    {
        if (session_id() == '' && !headers_sent()) {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
}
