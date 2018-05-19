<?php

require_once '../db/DatabaseConnection.php';
require_once '../db/DatabaseConnectionFactory.php';
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';
require_once '../view/PhpTemplateResponseContent.php';
require_once '../config/db_config.php';
require_once '../utils/StringUtils.php';

require_once '../config/countries.php';

$request = Request::getInstance();
$responseBuilder = new ResponseBuilder();

if (!$request->getUser()->isAuthorized()) {
    return $responseBuilder
        ->redirect('index.php')
        ->send();
}

$content = new PhpTemplateResponseContent('layout.html.php', 'dashboard_template.html.php');

$db = DatabaseConnectionFactory::newConnection($dbConfig);

$userId = $request->getUser()->getUserId();

$userData = $db->fetchOne("SELECT * FROM user_data WHERE user_id = $userId");
$content->setParam('userData', $userData);

$accounts = $db->fetch("SELECT * FROM account WHERE user_id = $userId");
$content->setParam('accounts', $accounts);

$user = $db->fetchOne("SELECT * FROM user WHERE id = $userId");
$content->setParam('user', $user);

$content->setParam('countries', $countries);

return $responseBuilder
    ->contentType(ContentType::TEXT_HTML)
    ->content($content)
    ->send();
