<?php

require_once __DIR__ . '/../utils/StringUtils.php';

require_once __DIR__ . '/../session/User.php';

class Request {

    const HTTP_HEADER_PREFIX = 'HTTP_';

    const METHOD_GET = 'GET';

    const METHOD_POST = 'POST';

    /**
     * @var array
     */
    private $httpHeaders = array();

    /**
     * @var User
     */
    private $user;

    /**
     * @var Temp cookies
     */
    private $tmpCookies = array();

    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new Request();
        }

        return $instance;
    }

    private function __construct() {
        $this->dispatchHttpHeaders();
        $this->dispatchSession();
    }

    public function __destruct() {
        $this->user->flush($_SESSION);
    }

    private function dispatchHttpHeaders() {
        foreach ($_SERVER as $key => $value) {
            if (StringUtils::startsWith($key, self::HTTP_HEADER_PREFIX)) {
                $this->httpHeaders[strtolower(str_replace(self::HTTP_HEADER_PREFIX, '', $key))] = $value;
            }
        }
    }

    private function dispatchSession() {
        session_start();
        $this->user = new User();
        foreach ($_SESSION as $name => $value) {
            $this->user->set($name, $value);
        }
    }

    public function getHttpParam($name, $default = null) {
        if (array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
        }
        if (array_key_exists($name, $_FILES)) {
            return $_FILES[$name];
        }

        return $default;
    }

    public function getHttpHeader($name, $default = null) {
        return array_key_exists($name, $this->httpHeaders) ? $this->httpHeaders[$name] : $default;
    }

    public function getCookieParam($name, $default = null) {
        $cookies = array_merge($this->tmpCookies, $_COOKIE);
        return array_key_exists($name, $cookies) ? $cookies[$name] : $default;
    }

    public function setCookieParam($name, $value) {
        $this->tmpCookies[$name] = $value;
        setcookie($name, $value, time()+60*60*24*365, "", "", false, true);
    }

    public function getHttpMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUser() {
        return $this->user;
    }
}