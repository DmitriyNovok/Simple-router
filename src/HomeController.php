<?php


class HomeController
{
    public static function home()
    {
        header('Content-Type: application/json; charset=utf-8');

        Views::view('/views/home.phtml', [
            'response' => json_encode(['Hello' => 'World!'])
        ]);
    }

    public static function hello()
    {
        header('Content-Type: application/json; charset=utf-8');

        Views::view('/views/home.phtml', [
            'response' => json_encode(['World' => 'Hello!'])
        ]);
    }
}