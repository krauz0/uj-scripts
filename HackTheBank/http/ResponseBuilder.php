<?php

require_once 'ResponseContent.php';
require_once 'ContentType.php';


class ResponseBuilder {

    /**
     * @var string
     */
    private $contentType = ContentType::TEXT_HTML;

    /**
     * @var ResponseContent
     */
    private $content;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @param mixed $contentType
     * @return ResponseBuilder
     */
    public function contentType($contentType) {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @param mixed $content
     * @return ResponseBuilder
     */
    public function content(ResponseContent $content) {
        $this->content = $content;
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return ResponseBuilder
     */
    public function header($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param $url
     * @return ResponseBuilder
     */
    public function redirect($url) {
        $this->redirectUrl = $url;
        return $this;
    }

    public function send() {
        if ($this->redirectUrl != null) {
            header("location: $this->redirectUrl");
        }

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        header("content-type: $this->contentType");

        if ($this->content != null) {
            echo $this->content->render();
        }

        return 0;
    }
}