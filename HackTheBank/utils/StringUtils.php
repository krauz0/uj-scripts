<?php

class StringUtils {

    public static function startsWith($str, $search) {
        return is_string($str) && strpos($str, $search) === 0;
    }

    public static function endsWith($str, $search) {
        return is_string($str) && strrpos($str, $search) == strlen($str) - strlen($search);
    }

    public static function formatAccount($accountNumber) {
        return trim(preg_replace('/([0-9]{4})/', ' $1', $accountNumber));
    }

    public static function formatAmount($balance) {
        return number_format($balance, 2, ',', ' ');
    }
}