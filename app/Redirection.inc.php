<?php

class Redirection{

    public static function redirect($url){
        $_SESSION['username'];
        header('Location: ' . $url, true, 301); // 301: indica redirección
        exit();
    }
}