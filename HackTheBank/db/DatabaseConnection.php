<?php

class DatabaseConnection {

    /**
     * @var DatabaseProvider
     */
    private $provider;

    /**
     * @var array
     */
    private $config;

    public function __construct($provider, $config) {
        $this->provider = $provider;
        $this->config = $config;

        $this->connect();
        $this->fireEvent('onConnect');
    }

    public function __destruct() {
        $this->provider->close();
        $this->fireEvent('onClose');
    }

    private function connect() {
        $this->provider->connect($this->config['host'], $this->config['user'], $this->config['password']);
        $this->provider->selectDatabase($this->config['database']);
    }

    private function fireEvent($event) {
        if (isset($this->config['events'][$event])) {
            $this->query($this->config['events'][$event]);
        }
    }

    public function fetch($query) {
        return $this->provider->fetch($query);
    }

    public function fetchOne($query) {
        $fetched = $this->provider->fetch($query);
        return isset($fetched[0]) ? $fetched[0] : null;
    }

    public function query($query) {
        return $this->provider->query($query);
    }

}