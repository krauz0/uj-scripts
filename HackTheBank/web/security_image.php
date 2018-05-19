<?php

require_once '../db/DatabaseConnection.php';
require_once '../db/DatabaseConnectionFactory.php';
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';
require_once '../view/RawResponseContent.php';
require_once '../config/db_config.php';
require_once '../utils/StringUtils.php';

$request = Request::getInstance();
$responseBuilder = new ResponseBuilder();

$db = DatabaseConnectionFactory::newConnection($dbConfig);

$username = addslashes($request->getHttpParam("username"));
$user = $db->fetchOne("SELECT * FROM user WHERE name = '$username'");
$securityImage = !empty($user) ? $user['security_image'] : null;

if (!$securityImage || !file_exists($securityImage)) {
    $securityImage = "images/safe.jpg";
}

$responseBuilder
    ->content(new RawResponseContent(file_get_contents(__DIR__ . "/$securityImage")))
    ->contentType(mime_content_type(__DIR__ . "/$securityImage"))
    ->send();