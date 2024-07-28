<?php

namespace App\Services;

class TagClosedCheckService
{
    public function checkString(string $string): bool
    {
        if (!self::checkTags($string))
        {
            return false;
        }

        $success = false;
        $userInput = $string;
        $allowedTags = '<a><code><i><strong>';
        $cleanedContent = strip_tags($userInput, $allowedTags);

        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $cleanedContent, $result);
        preg_match_all('#</([a-z]+)>#iU', $cleanedContent, $result2);

        if (count($result[0]) != count($result2[0])) {
            return $success;
        }
        for ($i = 0; $i < count($result[0]); $i++) {
            switch ($result[0][$i]) {
                case "<a>":
                    if ($result2[0][$i] != "</a>") {
                        $success = false;
                        break;
                    }
                    break;

                case '<code>':
                    if ($result2[0][$i] != "</code>") {
                        $success = false;
                        break;
                    }
                    break;
                case '<strong>':
                    if ($result2[0][$i] != "</strong>") {
                        $success = false;
                        break;
                    }
                    break;
            }
        }

        return $success;
    }

    public function checkTags(string $string): bool 
    {
        $allowedTags = ['<a>', '<i>', '<code>', '<strong>'];
        $filteredString = strip_tags($string, implode('', $allowedTags));
        return $filteredString !== $string;
    }
}
