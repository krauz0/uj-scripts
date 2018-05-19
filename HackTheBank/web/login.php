<?php

require_once '../db/DatabaseConnection.php';
require_once '../db/DatabaseConnectionFactory.php';
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';
require_once '../view/PhpTemplateResponseContent.php';
require_once '../config/db_config.php';
require_once '../utils/Score.php';

$request = Request::getInstance();

$content = new PhpTemplateResponseContent('login_layout.html.php', 'login_template.html.php');

if ($request->getHttpMethod() == Request::METHOD_POST) {
    $db = DatabaseConnectionFactory::newConnection($dbConfig);

    $username = $request->getHttpParam("login");
    $md5pass = md5($request->getHttpParam("password"));
    $user = $db->fetchOne("SELECT * FROM user WHERE name = '$username' and password = '$md5pass'");

    $userAddSlashes = $db->fetchOne("SELECT * FROM user WHERE name = '".addslashes($username)."' and password = '$md5pass'");

    if ($user != null) {
        $request->getUser()->setAuthorized(true);
        $request->getUser()->setUserId($user['id']);
        $request->getUser()->setUsername($user['name']);

        if ($user['name'] !== $username) {
            Score::getInstance()->addPoint(ScoreType::SQL_INJECTION);

            if ($userAddSlashes != null) {
                Score::getInstance()->addPoint(ScoreType::SQL_INJECTION_ADD_SLASHES);
            }
        }

    } else {
        $content->setParam('login', $username);
        $content->setParam('loginError', "Niepoprawna nazwa użytkownika lub hasło");
    }
}

if ($request->getUser()->isAuthorized()) {
    $responseBuilder = new ResponseBuilder();
    return $responseBuilder
        ->redirect('index.php')
        ->send();
}

$responseBuilder = new ResponseBuilder();
return $responseBuilder
        ->contentType(ContentType::TEXT_HTML)
        ->content($content)
        ->send();

