<?php

    namespace App\Helpers;

    class NumberHelper
    {
        public static function checkIsNumber(string $string_to_check): bool
        {
            return is_numeric($string_to_check);
        }

        public static function convertToNumber(string $string_number): int|false
        {
            if (self::checkIsNumber($string_number))
            {
                return intval($string_number);
            }else{
                return false;
            }
        }
    }