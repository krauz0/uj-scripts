<?php

require_once '../http/Request.php';

$request = Request::getInstance();

if ($request->getUser()->isAuthorized()) {
    include 'dashboard.php';
} else {
    include 'login.php';
}