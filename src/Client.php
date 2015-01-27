<?php

namespace Trello;

use Trello\Authorization\AuthInterface;
use Trello\Session\PhpSessionHandler;
use Trello\Session\SessionHandlerInterface;

class Client
{
    /** @var string */
    protected $apiEndpoint = 'https://api.trello.com';

    /** @var string */
    protected $authEndpoint = 'https://trello.com';

    /** @var string */
    protected $lastError;

    /** @var SessionHandlerInterface */
    protected $sessions;

    /** @var AuthInterface */
    protected $auth;

    /**
     * @param array $config
     * @throws ClientException
     */
    public function __construct(array $config = [])
    {
        // CURL is required in order for this extension to work
        if (!function_exists('curl_init')) {
            throw new ClientException('CURL is required for php-trello');
        }

        if (isset($config['session'])) {
            $this->sessions = $config['session'];
        }

        if (isset($config['auth'])) {
            $this->auth = $config['auth'];

            if ($this->auth instanceof Authorization\OAuth && !isset($this->sessions)) {
                $this->sessions = new PhpSessionHandler;
            }
        }
    }

    public function authorize()
    {

    }

    public function processResponse()
    {

    }

    public function isAuthorized()
    {

    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->lastError;
    }

    /**
     * @return SessionHandlerInterface
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * @param SessionHandlerInterface $sessions
     */
    public function setSessions(SessionHandlerInterface $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * @return AuthInterface
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param AuthInterface $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }
}
