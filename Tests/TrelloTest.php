<?php

use \Trello\Trello;

class TrelloTest extends \PHPUnit_Framework_TestCase {

    protected static $trello;

    public static function setUpBeforeClass() {
        global $keys;

        self::$trello = new Trello($keys->key, $keys->secret, $keys->token, $keys->oauth_secret);

        if (self::$trello->authorized()) {
            // Verify the token isn't expired
            $me = self::$trello->members->get('me', array('fields' => ''));
            if ($me === false) {
                self::$trello->setToken('');
                self::$trello->setOauthSecret('');
            }
        }

        // Get an authorize URL (or true if we're already auth'd)
        $authorizeUrl = self::$trello->authorize(array(
            'scope' => array(
                'read' => true,
                'write' => true,
                'account' => true,
            ),
            'redirect_uri' => 'http://127.0.0.1:23456',
            'name' => 'php-trello PHPUnit Tests',
            'expiration' => '1hour',
        ), true);

        // Need to get authorized
        if ($authorizeUrl !== true) {
            $server = stream_socket_server('tcp://127.0.0.1:23456', $errno, $errmsg);
            if (!$server) {
                echo "Could not create a socket at 127.0.0.1:23456: $errmsg\n";
                exit(1);
            }

            // Fire off the browser
            `xdg-open "$authorizeUrl" >/dev/null 2>&1 &`;

            echo "Waiting for authorization from Trello...\n";
            $client = stream_socket_accept($server, 120);
            $query = fgets($client, 1024);

            // Received a response, let's send back a message
            $msg = "Please close this browser window and return to the test to continue.";
            fputs($client, "HTTP/1.1 200 OK\r\n");
            fputs($client, "Connection: close\r\n");
            fputs($client, "Content-Type: text/html; charset=UTF-8\r\n");
            fputs($client, "Content-Length: " . strlen($msg) . "\r\n\r\n");
            fputs($client, $msg);

            // Wait to continue the test until the browser returns.
            readline("Press [Enter] to continue...");

            // Lets parse the query and pull out the oauth stuff
            if (!preg_match('~GET (.*?) HTTP~', $query, $match)) {
                echo "Could not read response from Trello\n";
                exit(1);
            }

            parse_str(parse_url($match[1], PHP_URL_QUERY), $_GET);

            if (self::$trello->authorize(array(), true) !== true) {
                echo "Did not receive proper authorization from Trello\n";
                exit(1);
            }

            // Save what we got
            $keys->token = self::$trello->token();
            $keys->oauth_secret = self::$trello->oauthSecret();
            file_put_contents('keys.json', json_encode($keys));
        }
    }

    /**
     * https://bitbucket.org/mattzuba/php-trello/issue/2/passing-params-while-using-oauth-causes
     */
    public function testIssue2() {
        $actions = self::$trello->lists->get('4d5ea62fd76aa1136000001d/actions', array('since' => '2013-07-30'));
        $this->assertInternalType('array', $actions, self::$trello->error());
    }

    /**
     * https://bitbucket.org/mattzuba/php-trello/issue/4/put-requests-failing
     */
    public function testIssue4() {
        $card = '7uDI46kM';
        $result = self::$trello->put("cards/$card/labels", array('value' => 'green,red'));
        $this->assertInternalType('object', $result, self::$trello->error());
    }

    /**
     * https://bitbucket.org/mattzuba/php-trello/issue/5/expand-collection-class
     */
    public function testIssue5() {
        $card = '7uDI46kM';
        $result = self::$trello->cards->put("$card/labels", array('value' => 'blue'));
        $this->assertInternalType('object', $result, self::$trello->error());
    }
}
