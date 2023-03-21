<?php

use JetBrains\PhpStorm\NoReturn;

class Router
{
    public static $routes = [];
    private static $params = [];
    public static $requestedUrl = '';

    public static function addRoute($route, $callback = null)
    {
        if ($callback != null && ! is_array($route)) {
            $route = [$route => $callback];
        }
        self::$routes = \array_merge(self::$routes, $route);
    }

    public static function run($requestedUrl = null)
    {
        if ($requestedUrl === null) {
            $array = explode('?', $_SERVER['REQUEST_URI']);
            $uri = @reset($array);
            $requestedUrl = urldecode(rtrim($uri, '/'));
        }

        self::$requestedUrl = $requestedUrl;

        if (isset(self::$routes[$requestedUrl])) {
            self::$params = self::splitUrl(self::$routes[$requestedUrl]);

            return self::executeAction();
        }

        foreach (self::$routes as $route => $uri) {
            if (str_contains($route, ':')) {
                $route = str_replace(':any', '(.+)', str_replace(':num', '([0-9]+)', $route));
            }

            if (preg_match('#^'.$route.'$#', $requestedUrl)) {
                if (str_contains($uri, '$') && str_contains($route, '(')) {
                    $uri = preg_replace('#^'.$route.'$#', $uri, $requestedUrl);
                }
                self::$params = self::splitUrl($uri);
                break;
            }
        }

        return self::executeAction();
    }

    public static function executeAction()
    {
        $controller = self::$params[0] ?? 'HomeController';
        $action = self::$params[1] ?? 'home';
        $params = array_slice(self::$params, 2);

        return call_user_func_array([$controller, $action], $params);
    }

    #[NoReturn]
    public static function redirect($url, $status = 200): void
    {
        \header('Location: ' . $url, true, $status);
        exit();
    }

    public static function extractDomain($domain)
    {
        if (\preg_match('/(?P<domain>[a-z0-9][a-z0-9\\-]{1,63}\\.[a-z\\.]{2,6})$/i', $domain, $matches)) {
            return $matches['domain'];
        }

        return $domain;
    }

    public static function httpDomain(): string
    {
        $protocol = !empty(@$_SERVER['HTTP_X_FORWARDED_PROTO'])
            ? @$_SERVER['HTTP_X_FORWARDED_PROTO']
            : @$_SERVER['REQUEST_SCHEME'];

        return $protocol .'://'. $_SERVER['HTTP_HOST'];
    }

    public static function urlFrom()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    public static function splitUrl($url)
    {
        return \explode('@', $url);
    }

    public static function getCurrentUrl(): string
    {
        return (self::$requestedUrl ? : '/');
    }
}