<?php

class LoginValidation{

    private $usernameError;
    private $passwordError;

    public function validateEmptyFields($username, $password){

        if (empty($username)){
            $this->usernameError = 'Por favor, introduce tu código de usuario.';
        }
        if(empty($password)){
            $this->passwordError = 'Por favor, introduce la contraseña.';
        }
    }

    public function validateUserExists($connection, $username){
        
        if(empty($this->usernameError)){
            include_once 'UserRepository.inc.php';

            if(!UserRepository::usernameExists($connection, $username)){
                $this->usernameError = 'El código de usuario no existe. Por favor, reintente.';
            }
        }
    }

    public function validatePassword($connection, $username, $password){
        if(empty($this->passwordError)){
            include_once 'UserRepository.inc.php';

            $user = UserRepository::getUserByUsername($connection, $username);
            if (is_null($user)  ||
                !password_verify($password, $user->getPassword())){ // password_verify comprueba que la contraseña (clave) coincida con el hash guardado
                $this->passwordError = 'Contraseña incorrecta. Por favor, reintente';
            }
        }
    }

    public function getUsernameError(){
        return $this->usernameError;
    }

    public function getPasswordError(){
        return $this->passwordError;
    }

}