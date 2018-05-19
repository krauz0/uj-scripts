<?php

class User {

    const USERNAME_PARAM = 'userName';

    const USER_ID_PARAM = 'userId';

    const AUTHORIZED_PARAM = 'isAuthorized';

    /**
     * @var array
     */
    private $sessionParams = array();

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value) {
        $this->sessionParams[$name] = $value;
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function get($name, $default = null) {
        return $this->exists($name) ? $this->sessionParams[$name] : $default;
    }

    /**
     * @param $name
     * @return bool
     */
    public function exists($name) {
        return array_key_exists($name, $this->sessionParams);
    }

    /**
     * @return bool
     */
    public function isAuthorized() {
        return $this->get(self::AUTHORIZED_PARAM, false);
    }

    /**
     * @param $auth
     */
    public function setAuthorized($auth) {
        $this->set(self::AUTHORIZED_PARAM, $auth);
    }

    /**
     * @return mixed|null
     */
    public function getUsername() {
        return $this->get(self::USERNAME_PARAM);
    }

    /**
     * @param $name
     */
    public function setUsername($name) {
        $this->set(self::USERNAME_PARAM, $name);
    }

    /**
     * @return mixed|null
     */
    public function getUserId() {
        return $this->get(self::USER_ID_PARAM);
    }

    /**
     * @param $id
     */
    public function setUserId($id) {
        $this->set(self::USER_ID_PARAM, $id);
    }

    /**
     * @param $to
     */
    public function flush(&$to) {
        $to = $this->sessionParams;
    }

}