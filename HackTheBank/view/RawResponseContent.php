<?php

require_once __DIR__ . '/../http/Request.php';
require_once __DIR__ . '/../http/ResponseContent.php';

class RawResponseContent implements ResponseContent {

    private $content;

    public function __construct($raw) {
        $this->content = $raw;
    }

    /**
     * @return string
     */
    public function render() {
        return $this->content;
    }

}