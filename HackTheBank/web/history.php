<?php

require_once '../db/DatabaseConnection.php';
require_once '../db/DatabaseConnectionFactory.php';
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';
require_once '../view/PhpTemplateResponseContent.php';
require_once '../config/db_config.php';
require_once '../utils/StringUtils.php';

$request = Request::getInstance();
$responseBuilder = new ResponseBuilder();

if (!$request->getUser()->isAuthorized()) {
    return $responseBuilder
        ->redirect('index.php')
        ->send();
}

$content = new PhpTemplateResponseContent('layout.html.php', 'history_template.html.php');

$db = DatabaseConnectionFactory::newConnection($dbConfig);

$accId = addslashes($request->getHttpParam("account"));

$account = $db->fetchOne("SELECT * FROM account WHERE id = '$accId'");
$content->setParam('account', $account);

$transactions = $db->fetch("SELECT
    trn.*,
    acc.account_number as sender_account_number,
    acc2.account_number as recipient_account_number,
    concat(usr.first_name, ' ', usr.last_name) as sender,
    concat(usr2.first_name, ' ', usr2.last_name) as recipient
  FROM transaction as trn
    LEFT JOIN account as acc ON trn.src_account_id = acc.id
    LEFT JOIN account as acc2 ON trn.dest_account_id = acc2.id
    LEFT JOIN user_data as usr ON acc.user_id = usr.id
    LEFT JOIN user_data as usr2 ON acc2.user_id = usr2.id
  WHERE 
    trn.src_account_id = $accId OR trn.dest_account_id = $accId
  ORDER BY trn.id DESC");
$content->setParam('transactions', $transactions);

return $responseBuilder
    ->contentType(ContentType::TEXT_HTML)
    ->content($content)
    ->send();
