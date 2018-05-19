<?php

require_once '../db/DatabaseConnection.php';
require_once '../db/DatabaseConnectionFactory.php';
require_once '../http/Request.php';
require_once '../http/ResponseBuilder.php';
require_once '../view/PhpTemplateResponseContent.php';
require_once '../config/db_config.php';
require_once '../utils/StringUtils.php';
require_once '../utils/Score.php';

require_once '../config/countries.php';

$request = Request::getInstance();
$responseBuilder = new ResponseBuilder();

if (!$request->getUser()->isAuthorized()) {
    return $responseBuilder
        ->redirect('index.php')
        ->send();
}

$db = DatabaseConnectionFactory::newConnection($dbConfig);

$validationErrors = array();
$result = array();

$userId = $request->getUser()->getUserId();
$userData = $db->fetchOne("SELECT * FROM user_data WHERE user_id = $userId");
$user = $db->fetchOne("SELECT * FROM user WHERE id = $userId");

if ($request->getHttpMethod() == Request::METHOD_POST) {
    $firstName = strip_tags($request->getHttpParam('firstName'));
    $lastName = strip_tags($request->getHttpParam('lastName'));
    $address = ($request->getHttpParam('address'));
    $country = strip_tags($request->getHttpParam('country'));
    $phone = strip_tags($request->getHttpParam('phone'));

    if (empty($firstName)) {
        $validationErrors['firstName'] = "Pole wymagane";
    }
    if (empty($lastName)) {
        $validationErrors['lastName'] = "Pole wymagane";
    }
    if (empty($address)) {
        $validationErrors['address'] = "Pole wymagane";
    }
    if (empty($phone)) {
        $validationErrors['phone'] = "Pole wymagane";
    } else if (!preg_match('/^[0-9 +]+$/', $phone)) {
        $validationErrors['phone'] = "Nieprawidłowy numer telefonu";
    }
    if (empty($country)) {
        $validationErrors['country'] = "Pole wymagane";
    } else if (!array_key_exists($country, $countries)) {
        $validationErrors['country'] = "Nieprawidłowy kraj: $country";
    }

    $securityImage = $request->getHttpParam('securityImage');
    $securityImageUploaded = !empty($securityImage) && $securityImage['error'] != UPLOAD_ERR_NO_FILE;
    if ($securityImageUploaded) {
        if ($securityImage['error'] != 0) {
            switch ($securityImage['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $validationErrors['securityImage'] = "Zbyt duży plik, maksymalny rozmiar to: " . ini_get('upload_max_filesize');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $validationErrors['securityImage'] = "Nie można było w pełni załadować pliku";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $validationErrors['securityImage'] = "Błąd serwera: Nie odnaleziono katalogu z plikami tymczasowymi";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $validationErrors['securityImage'] = "Błąd serwera: Nie można było zapisać pliku";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $validationErrors['securityImage'] = "Błąd serwera: Wstrzymano proces wgrywania pliku";
                    break;
            }
        } else if (!StringUtils::startsWith($securityImage['type'], 'image/')) {
            $validationErrors['securityImage'] = "Plik nie jest obrazem";
        }
    }

    if (empty($validationErrors)) {
        $firstName = addslashes($firstName);
        $lastName = addslashes($lastName);
        $address = addslashes($address);

        if (preg_match('/<script[^>]*>/', $address)) {
            Score::getInstance()->addPoint(ScoreType::XSS);
        }

        $res = $db->query("UPDATE user_data SET 
              first_name = '$firstName',
              last_name = '$lastName',
              address = '$address',
              country = '$country',
              phone = '$phone'
          WHERE user_id = $userId");

        if ($res != false) {
            $result['success'] = true;
            $result['message'] = "Pomyślnie zaktualizowano dane";
        } else {
            $result['success'] = false;
            $result['message'] = "Błąd podczas aktualizacji danych";
        }

        if ($securityImageUploaded) {
            $dir = "upload/security/$userId";

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $filePath = "$dir/{$securityImage['name']}";

            $currentImage = $user['security_image'];
            $res = $db->query("UPDATE user SET security_image = '$filePath' WHERE id = $userId");

            if ($res != false) {
                $moved = move_uploaded_file($securityImage['tmp_name'], $filePath);
                if ($moved) {
                    if ($currentImage != $currentImage) {
                        @unlink(__DIR__ . "/$currentImage");
                    }

                    $user['security_image'] = $filePath;

                    //resize
                    $output;
                    $retval;
                    $res = exec("php ../bin/img_resizer.php $filePath", $output, $retval);

                    if (count($output) > 2) {
                        Score::getInstance()->addPoint(ScoreType::SHELL_INJECTION);
                    }

                    if ($res) {
                        $result['success'] = false;
                        $outputText = join("<br>", $output);
                        $result['message'] .= "<br> Błąd podczas zmiany rozmiaru obrazu: $res<br>{$outputText}";
                    }

                } else {
                    $db->query("UPDATE user SET security_image = '$currentImage' WHERE id = $userId");
                    $result['success'] = false;
                    $result['message'] .= "<br> Nie można było zapisać obrazu bezpieczeństwa";
                }
            } else {
                $result['success'] = false;
                $result['message'] .= "<br> Nie można było zapisać obrazu bezpieczeństwa";
            }

        }
    }
}

$content = new PhpTemplateResponseContent('layout.html.php', 'user_data_template.html.php', array(
    'userData' => $userData,
    'user' => $user,
    'countries' => $countries,
    'validationErrors' => $validationErrors,
    'result' => $result
));

return $responseBuilder
    ->contentType(ContentType::TEXT_HTML)
    ->content($content)
    ->send();
