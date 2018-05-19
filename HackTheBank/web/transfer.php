<?php

require_once '../db/DatabaseConnection.php';
require_once '../db/DatabaseConnectionFactory.php';
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';
require_once '../view/PhpTemplateResponseContent.php';
require_once '../config/db_config.php';
require_once '../utils/StringUtils.php';
require_once '../utils/Score.php';

$request = Request::getInstance();
$responseBuilder = new ResponseBuilder();

if (!$request->getUser()->isAuthorized()) {
    return $responseBuilder
        ->redirect('index.php')
        ->send();
}

$db = DatabaseConnectionFactory::newConnection($dbConfig);
$userId = $request->getUser()->getUserId();

$result = array();

if ($request->getHttpMethod() == Request::METHOD_POST) {
    $fromAccount = $request->getHttpParam('fromAccount');
    $toAccount = $request->getHttpParam('toAccount');
    $amount = $request->getHttpParam('amount');
    $title = $request->getHttpParam('title');

    if (empty($fromAccount)) {
        $validationErrors['fromAccount'] = "Wybierz rachunek";
    }

    if (empty($toAccount)) {
        $validationErrors['toAccount'] = "Wybierz rachunek";
    }

    if (empty($amount)) {
        $validationErrors['amount'] = "Wpisz kwotę przelewu";
    } /*else if (!preg_match('/^[0-9]+([,.][0-9]{1,2})?$/', $amount)) {
        $validationErrors['amount'] = "Nieprawidłowy format kwoty";
    }*/

    if (empty($validationErrors)) {
        $dbFromAccount = $db->fetchOne("SELECT * FROM account WHERE id = $fromAccount");
        $dbToAccount = $db->fetchOne("SELECT * FROM account WHERE id = $toAccount");
        $decimalAmount = floatval(str_replace(",", ".", $amount));

        if ($dbFromAccount['user_id'] != $request->getUser()->getUserId()) {
            throw new Exception("Nieautoryzowana operacja");
        }

        $currencyCheck = $dbFromAccount['currency'] == $dbToAccount['currency'];
        $amountCheck = $decimalAmount <= floatval($dbFromAccount['balance']);

        if ($currencyCheck && $amountCheck) {
            $securedTitle = addslashes($title);

            $res = $db->query("START TRANSACTION");
            if (!$res) {
                die("Nie można uruchomić transakcji bazy danych");
            }

            $res = $db->query("INSERT INTO transaction (src_account_id, dest_account_id, amount, currency, title)
              VALUES (
                  {$dbFromAccount['id']},
                  {$dbToAccount['id']},
                  {$decimalAmount},
                  '{$dbFromAccount['currency']}',
                  '{$securedTitle}'
              )
            ");
            if (!$res) {
                die("Nie można zapisać transakcji w bazie danych");
            }

            $newBalance = floatval($dbFromAccount['balance']) - $decimalAmount;
            $res = $db->query("UPDATE account SET balance = {$newBalance} WHERE id = {$dbFromAccount['id']}");
            if (!$res) {
                die("Nie można zaktualizować stanu konta źródłowego");
            }

            $newBalance = floatval($dbToAccount['balance']) + $decimalAmount;
            $res = $db->query("UPDATE account SET balance = {$newBalance} WHERE id = {$dbToAccount['id']}");
            if (!$res) {
                die("Nie można zaktualizować stanu konta docelowego");
            }

            $res = $db->query("COMMIT");
            if (!$res) {
                die("Nie można zatwierdzić transakcji bazy danych");
            }

            $request->getUser()->set("transferSuccess", true);

            if ($amount < 0) {
                Score::getInstance()->addPoint(ScoreType::NEGATIVE_TRANSACTION);
            }

            return $responseBuilder
                ->redirect("transfer.php")
                ->send();
        } else {
            $result['success'] = false;

            if (!$currencyCheck) {
                $result['message'] = "Waluta rachunku źródłowego ({$dbFromAccount['currency']}) nie jest zgodna z"
                    . " walutą rachunku docelowego ({$dbToAccount['currency']})";
            } else {
                $result['message'] = "Niewystarczające środki do wykonania przelewu: {$dbFromAccount['balance']} {$dbFromAccount['currency']}";
            }
        }
    }
}

$content = new PhpTemplateResponseContent('layout.html.php', 'transfer_template.html.php');
$content->setParam('validationErrors', isset($validationErrors) ? $validationErrors : array());

if ($request->getUser()->get('transferSuccess')) {
    $request->getUser()->set("transferSuccess", false);

    $result['success'] = true;
    $result['message'] = "Przelew został wykonany";
}

$content->setParam('result', $result);

$recipientId = $request->getHttpParam('recipient', 0);
$recipientAccounts = $db->fetch("SELECT * FROM account WHERE user_id = $recipientId");
$content->setParam('recipientAccounts', $recipientAccounts);

$accounts = $db->fetch("SELECT * FROM account WHERE user_id = $userId");
$content->setParam('accounts', $accounts);

$users = $db->fetch("SELECT * FROM user_data WHERE user_id <> $userId");
$content->setParam('users', $users);

return $responseBuilder
    ->contentType(ContentType::TEXT_HTML)
    ->content($content)
    ->send();
