<?php

require_once __DIR__ . '/DatabaseProvider.php';

class MysqlDatabaseProvider implements DatabaseProvider {

    private $link;

    /**
     * @param $host
     * @param $user
     * @param $pass
     * @return void
     */
    function connect($host, $user, $pass) {
        $this->link = mysql_connect($host, $user, $pass);
    }

    /**
     * @param $database
     * @return void
     */
    function selectDatabase($database) {
        mysql_select_db($database);
    }

    /**
     * @param $query
     * @return void
     */
    function query($query) {
        return mysql_query($query);
    }

    /**
     * @param $query
     * @return array
     */
    function fetch($query) {
        $result = $this->query($query);
        $ret = array();
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $ret[] = $row;
        }

        mysql_free_result($result);

        return $ret;
    }

    /**
     * @return void
     */
    function close() {
        mysql_close($this->link);
    }
}