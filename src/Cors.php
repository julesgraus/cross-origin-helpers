<?php

namespace JulesGraus\CrossOriginHelpers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Cors
 *
 * Outputs cors headers directly instead of attaching them to a laravel response.
 * Used in cases where data is outputted directly, without the classic response object.
 *
 * @package JulesGraus\CrossOriginDD
 */
class Cors
{
    /** @var \Illuminate\Contracts\Container\Container $container */
    protected $container;

    public static function outputHeaders(Request $request) {
        if(!self::corsEnabledPath($request)) return;

        if(self::isPreflightRequest($request)) {
            self::handlePreflightRequest($request);
            return;
        }

        self::outputAllowOrigins();
        self::outputExposeHeaders();
        self::outputAllowCredentialsHeader();
        self::outputMaxAgeHeader();
    }

    public static function handlePreflightRequest(Request $request) {
        self::outputAllowMethods();
        self::outputAllowHeaders();
    }

    public static function outputAllowOrigins() {
        if(config('cors.supports_credentials', false)) {
            if(strpos(implode(', ', config('cors.allowed_origins', [])), '*') !== false) {
                Log::warning("The value of the 'Access-Control-Allow-Origin' header in the cors configuration must not be the wildcard '*' when the request's credentials mode is 'include'. The credentials mode of requests initiated by the XMLHttpRequest is controlled by the withCredentials attribute.");
                return;
            }
        };

        $origin = request()->header('origin');
        if(in_array($origin, config('cors.allowed_origins'))) {
            header('Access-Control-Allow-Origin: '.$origin);
        }
    }

    public static function outputAllowHeaders() {
        $headerData = implode(', ', config('cors.allowed_headers', []));
        header('Access-Control-Allow-Headers: '.$headerData);
    }

    public static function outputAllowMethods()
    {
        $headerData = implode(', ', config('cors.allowed_methods', []));
        header('Access-Control-Allow-Methods: ' . $headerData);
    }

    public static function outputExposeHeaders() {
        $headerData = implode(', ', config('cors.exposed_headers', []));
        if($headerData == '') return;
        header('Access-Control-Expose-Headers: '.$headerData);
    }

    public static function outputAllowCredentialsHeader() {
        header('Access-Control-Allow-Credentials: '.(config('cors.supports_credentials', false) ? 'true' : 'false'));
    }

    public static function outputMaxAgeHeader() {
        $seconds = config('cors.max_age', 0);
        if(!is_scalar($seconds)) $seconds = 0;
        header('Access-Control-Max-Age: '.$seconds);
    }

    public static function isPreflightRequest(Request $request): bool
    {
        return $request->getMethod() === 'OPTIONS' && $request->headers->has('Access-Control-Request-Method');
    }

    public static function corsEnabledPath(Request $request) {
        return self::isMatchingPath($request);
    }

    protected static function isMatchingPath(Request $request): bool
    {
        // Get the paths from the config or the middleware
        $paths = config('cors.paths', []);

        foreach ($paths as $path) {
            if ($path !== '/') {
                $path = trim($path, '/');
            }

            if ($request->fullUrlIs($path) || $request->is($path)) {
                return true;
            }
        }

        return false;
    }
}
