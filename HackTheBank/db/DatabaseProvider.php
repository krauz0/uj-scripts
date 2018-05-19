<?php

interface DatabaseProvider {

    /**
     * @param $host
     * @param $user
     * @param $pass
     * @return void
     */
    function connect($host, $user, $pass);

    /**
     * @param $database
     * @return void
     */
    function selectDatabase($database);

    /**
     * @param $query
     * @return mixed
     */
    function query($query);

    /**
     * @param $query
     * @return array
     */
    function fetch($query);

    /**
     * @return void
     */
    function close();

}