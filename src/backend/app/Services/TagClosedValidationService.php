<?php

namespace App\Services;

class TagClosedValidationService
{
    private array $allowedTags = ['<a>', '<i>', '<code>', '<strong>'];

    public function checkString(string $stringToCheckClosedTags): bool
    {
        if (!$this->checkStringForAllowedTags($stringToCheckClosedTags)) {
            return false;
        }

        $tags = $this->getTagsFromString($stringToCheckClosedTags);

        $stack = [];
        foreach ($tags as $tag) {
            if ($this->isClosingTag($tag)) {
                $closingTag = $this->getTagName($tag);
                if (end($stack) === $closingTag) {
                    array_pop($stack); // Properly nested, pop the stack
                } else {
                    return false; // Misnested tag found
                }
            } elseif ($this->isOpeningTag($tag)) {
                $openingTag = $this->getTagName($tag);
                if (in_array("<$openingTag>", $this->allowedTags)) {
                    $stack[] = $openingTag; // Push opening tag onto the stack
                } else {
                    return false; // Invalid tag found
                }
            } else {
                return false; // Invalid tag format found
            }
        }

        return empty($stack);
    }

    private function getTagsFromString(string $stringToExtractTags): array
    {
        $matches = $this->getDataFromStringUsingPregMatchAll('/<\/?[^>]+>/', $stringToExtractTags);
        return $matches[0] ?? [];
    }

    private function getTagName(string $tag): string
    {
        $matches = $this->getDataFromStringUsingPregMatch('/<\/?(\w+)/', $tag);
        return $matches[1] ?? '';
    }

    private function isClosingTag(string $tag): bool
    {
        return (bool) $this->getDataFromStringUsingPregMatch('/<\/\w+>/', $tag);
    }

    private function isOpeningTag(string $tag): bool
    {
        return (bool) $this->getDataFromStringUsingPregMatch('/<\w+[^>]*>/', $tag);
    }

    private function getDataFromStringUsingPregMatch(string $pregMatchPattern, string $stringToCheck): array
    {
        preg_match($pregMatchPattern, $stringToCheck, $returnValue);
        return $returnValue;
    }

    private function getDataFromStringUsingPregMatchAll(string $pregMatchPattern, string $stringToCheck): array
    {
        preg_match_all($pregMatchPattern, $stringToCheck, $returnValue);
        return $returnValue;
    }

    public function checkStringForAllowedTags(string $stringToCheckForAllowedTags): bool
    {
        $filteredString = strip_tags($stringToCheckForAllowedTags, implode('', $this->allowedTags));
        return $filteredString === $stringToCheckForAllowedTags;
    }
}

