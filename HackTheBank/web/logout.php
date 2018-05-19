<?php
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';

$request = Request::getInstance();
$request->getUser()->setAuthorized(false);
$request->getUser()->setUserId(null);
$request->getUser()->setUsername(null);

$responseBuilder = new ResponseBuilder();
$responseBuilder
    ->redirect('index.php')
    ->send();