<?php

require_once "../http/Request.php";

class Score {

    const SCORE_COOKIE = "score";
    const JUST_SCORED_FLASH = "just_scored";

    private $request;
    private $points = 0;

    private function __construct() {
        $this->request = Request::getInstance();
        $this->countPoints();
    }

    private function countPoints() {
        $cookie = $this->request->getCookieParam(Score::SCORE_COOKIE, "");
        foreach (ScoreType::values() as $score) {
            $this->points += strpos($cookie, $score) !== false ? 1 : 0;
        }
    }

    public function getPoints() {
        return $this->points;
    }

    public function justScored() {
        $scored = $this->request->getUser()->get(self::JUST_SCORED_FLASH, false);
        $this->request->getUser()->set(self::JUST_SCORED_FLASH, false);
        return $scored;
    }

    public function addPoint($type) {
        if (!ScoreType::isCorrect($type)) {
            throw new InvalidArgumentException("Invalid score type " + $type);
        }

        $score = ScoreType::hash($type);
        $cookie = $this->request->getCookieParam(Score::SCORE_COOKIE, "");

        if (strpos($cookie, $score) === true) {
            return false;
        }

        $cookie .= $score;
        $this->request->setCookieParam(Score::SCORE_COOKIE, $cookie);
        $this->request->getUser()->set(self::JUST_SCORED_FLASH, true);
        $this->points++;

        return true;
    }

    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new Score();
        }

        return $instance;
    }

}

abstract class ScoreType {

    const SQL_INJECTION = "SQL_INJECTION";
    const XSS = "XSS";
    const NEGATIVE_TRANSACTION = "NEGATIVE_TRANSACTION";
    const SHELL_INJECTION = "SHELL_INJECTION";
    const SQL_INJECTION_ADD_SLASHES = "SQL_INJECTION_ADD_SLASHES";

    public static function values() {
        $oClass = new ReflectionClass(__CLASS__);
        return array_map(function ($const) {
            return ScoreType::hash($const);
        }, $oClass->getConstants());
    }

    public static function hash($str) {
        return sha1(sha1($str) . "secret_salt_77816650a,a@T10.");
    }

    public static function isCorrect($type) {
        return in_array(ScoreType::hash($type), ScoreType::values());
    }
}
