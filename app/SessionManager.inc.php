<?php

class SessionManager{

    public static function startSession($username){

        // Con session_start abrimos un espacio en la memora del servidor para trabajar con la SESSION
        if(session_id() == ''){
            session_start();
        }

        $_SESSION['username'] = $username;
    }

    public static function sessionStarted(){
        if(session_id() == ''){
			session_start();	
        }

        if(isset($_SESSION['username'])){
            return true;
        }else{
            return false;
        }
    }

    public static function closeSession(){

        if(session_id() === ''){
			session_start();	
        }
        
        if(isset($_SESSION['username'])){
            unset($_SESSION['username']);
        }

        session_destroy();
    }
}