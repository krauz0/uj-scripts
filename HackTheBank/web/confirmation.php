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

$content = new PhpTemplateResponseContent('layout.html.php', 'confirmation_template.html.php', array(
    'countries' => $countries
));

$db = DatabaseConnectionFactory::newConnection($dbConfig);

$transactionId = intval($request->getHttpParam("transaction"));

$transaction = $db->fetchOne("SELECT
    trn.*,
    acc.account_number as sender_account_number,
    acc2.account_number as recipient_account_number,
    usr.first_name as sender_first_name,
    usr.last_name as sender_last_name,
    usr.address as sender_address,
    usr.country as sender_country,
    usr2.first_name as recipient_first_name,
    usr2.last_name as recipient_last_name,
    usr2.address as recipient_address,
    usr2.country as recipient_country,
    acc.user_id = {$request->getUser()->getUserId()} as is_own,
    IF(acc.user_id = {$request->getUser()->getUserId()}, acc.id, acc2.id) as account_id
  FROM transaction as trn
    LEFT JOIN account as acc ON trn.src_account_id = acc.id
    LEFT JOIN account as acc2 ON trn.dest_account_id = acc2.id
    LEFT JOIN user_data as usr ON acc.user_id = usr.id
    LEFT JOIN user_data as usr2 ON acc2.user_id = usr2.id
  WHERE 
    trn.id = $transactionId");

$content->setParam('transaction', $transaction);

if (empty($transaction)) {
    return $responseBuilder
        ->redirect('index.php')
        ->send();
}


return $responseBuilder
    ->contentType(ContentType::TEXT_HTML)
    ->content($content)
    ->send();
