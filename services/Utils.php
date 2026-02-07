<?php

use JetBrains\PhpStorm\NoReturn;

/**
 *  This class some useful functions
 */
class Utils
{
    /**
     * Return a mixed variable extract from $_REQUEST array
     * @param string $variableName
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public static function request(string $variableName, mixed $defaultValue = null) : mixed
    {
        return $_REQUEST[$variableName] ?? $defaultValue;
    }


    /**
     * Redirect to a page (action) with parameters
     * @param string $action
     * @param array $params
     * @return void
     */
    #[NoReturn]
    public static function redirect(string $action, array $params = []) : void
    {
        $url = "index.php?action=$action";
        foreach ($params as $paramName => $paramValue) {
            $url .= "&$paramName=$paramValue";
        }
        header("Location: $url");
        exit();
    }


}