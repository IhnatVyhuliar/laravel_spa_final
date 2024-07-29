<?php

namespace App\Services;

class LoadFileUsingLinkService
{
    public static function getBaseUrl(): string
    {
        return url();
    }

    public static function GetImageLink(string $imageFilePath): string
    {
        return self::getBaseUrl()."/comments/photo/". $imageFilePath;
    }

    public static function GetTXTLink(string $txtFilePath): string
    {
        return self::getBaseUrl()."/comments/txt/". $txtFilePath;
    }
}