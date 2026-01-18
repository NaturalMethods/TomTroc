<?php

class Utils
{
    public static function request(string $variableName, mixed $defaultValue = null) : mixed
    {
        return $defaultValue ?? $_REQUEST[$variableName];

    }

}