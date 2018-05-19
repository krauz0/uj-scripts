<?php

require_once __DIR__ . '/../http/Request.php';
require_once __DIR__ . '/../http/ResponseContent.php';

class PhpTemplateResponseContent implements ResponseContent {

    /**
     * @var string
     */
    private $layoutPath;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var array
     */
    private $templateParams;

    public function __construct($layoutPath, $templatePath, $templateParams = array()) {
        $this->layoutPath = $layoutPath;
        $this->templatePath = $templatePath;
        $this->templateParams = is_array($templateParams) ? $templateParams : array();
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setParam($name, $value) {
        $this->templateParams[$name] = $value;
    }

    /**
     * @return string
     */
    public function render() {
        $templateContent = $this->evaluateTemplate($this->templatePath, $this->templateParams);
        $layoutContent = $this->evaluateTemplate($this->layoutPath, array('content' => $templateContent));

        return $layoutContent;
    }

    private function evaluateTemplate($template, $params) {
        foreach ($params as $name => $value) {
            $$name = $value;
        }

        $request = Request::getInstance();
        ob_start();
        require $template;
        return ob_get_clean();
    }
}