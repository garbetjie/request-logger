<?php

namespace Garbetjie\Http\RequestLogging\Tests;

use Symfony\Component\HttpFoundation\ParameterBag;
use function stripos;

class HeaderBag
{
    protected static $headers = [];

    private function __construct()
    {

    }

    public static function add(string $header)
    {
        static::$headers[] = $header;
    }

    public static function clear()
    {
        static::$headers = [];
    }

    public static function list(): array
    {
        return static::$headers;
    }
}
