<?php

use JulesGraus\CrossOriginHelpers\Cors;

if (!function_exists('cdd')) {
    function cdd(...$vars)
    {
        Cors::outputHeaders(request());
        dd(...$vars);
    }
}

if (!function_exists('cdie')) {
    function cdie($status = 0)
    {
        Cors::outputHeaders(request());
        die($status);
    }
}

if (!function_exists('cdump')) {
    function cdump($var, ...$moreVars)
    {
        Cors::outputHeaders(request());
        dump($var, ...$moreVars);
    }
}

