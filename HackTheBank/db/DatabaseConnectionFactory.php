<?php

require_once __DIR__ . '/DatabaseProvider.php';
require_once __DIR__ . '/DatabaseConnection.php';

class DatabaseConnectionFactory {

    /**
     * @param array $config
     * @return DatabaseConnection
     * @throws Exception
     */
    public static function newConnection(array $config) {
        if (!isset($config['provider']) || !$config['provider'] instanceof DatabaseProvider) {
            throw new Exception("Database provider is not defined or it is not an instance of DatabaseProvider");
        }

        return new DatabaseConnection($config['provider'], $config);
    }



}