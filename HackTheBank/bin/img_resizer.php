<?php

$MAX_WIDTH = 200;
$MAX_HEIGHT = 200;

function imageFromFile($file) {
    $type = mime_content_type($file);
    switch ($type) {
        case "image/jpeg":
        case "image/jpg":
            return imagecreatefromjpeg($file);
        case "image/png":
            return imagecreatefrompng($file);
        case "image/gif":
            return imagecreatefromgif($file);
        default:
            throw new Exception("Nieobslugiwany typ obrazu $type");
    }
}

function imageToFile($resource, $file) {
    $type = mime_content_type($file);
    switch ($type) {
        case "image/jpeg":
        case "image/jpg":
            return imagejpeg($resource, $file);
        case "image/png":
            return imagepng($resource, $file);
        case "image/gif":
            return imagegif($resource, $file);
        default:
            throw new Exception("Nieobslugiwany typ obrazu $type");
    }
}

try {
    if ($argc < 2) {
        throw new Exception("Nieprawidlowe wywolanie skryptu.
        \tPrzyklad:
        \t{$argv[0]} <nazwa pliku>");
    }

    $file = $argv[1];

    if (!file_exists($file)) {
        throw new Exception("Nie odnaleziono pliku $file");
    }

    list($width, $height) = getimagesize($file);
    $ratio = $width/$height;

    if ($width > $height) {
        $newWidth = min($MAX_WIDTH, $width);
        $newHeight = $newWidth/$ratio;
    } else {
        $newHeight = min($MAX_HEIGHT, $height);
        $newWidth = $newHeight*$ratio;
    }

    $src = imageFromFile($file);
    $dst = imagecreatetruecolor($newWidth, $newHeight);
    if (imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)
        && imageToFile($dst, $file)) {
        return 0;
    }

    return -1;
} catch (Exception $e) {
    echo $e->getMessage();
    return -1;
}
