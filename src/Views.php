<?php

class Views
{
    public static function view($template, $vars = [])
    {
        $output = '';
        extract($vars);

        try {
            \ob_start();
            include ROOT.$template;
            $output = \ob_get_contents();
        } catch (Exception $e) {
            \ob_clean();
        }

        return $output;
    }
}

