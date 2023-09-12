<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helpers extends Insidesk
{
    public function getClientIpAddress()
    {
        return (isset($_SERVER['HTTP_CLIENT_IP'])) ?
            $_SERVER['HTTP_CLIENT_IP'] :
            ( (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR']) ) ?
                $_SERVER['HTTP_X_FORWARDED_FOR'] :
                $_SERVER['REMOTE_ADDR']
            );
    }

    public function getServerIpAdderss()
    {
        return $_SERVER['SERVER_ADDR'];
    }
}