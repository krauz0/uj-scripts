<?php

require_once '../db/DatabaseConnectionFactory.php';
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';
require_once '../view/RawResponseContent.php';
require_once '../config/db_config.php';
require_once '../utils/StringUtils.php';

$request = Request::getInstance();
$responseBuilder = new ResponseBuilder();

if (!$request->getUser()->isAuthorized()) {
    return $responseBuilder
        ->redirect('index.php')
        ->send();
}

$userId = $request->getHttpParam("user");
$db = DatabaseConnectionFactory::newConnection($dbConfig);
$accounts = $db->fetch("SELECT * FROM account WHERE user_id = $userId");

foreach ($accounts as $i => $account) {
    $accounts[$i]['account_number'] = StringUtils::formatAccount($accounts[$i]['account_number']);
}

return $responseBuilder
    ->contentType(ContentType::TEXT_JSON)
    ->content(new RawResponseContent(json_encode($accounts)))
    ->send();