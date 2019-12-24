<?php

class SignupValidation{

    private $usernameError;
    private $passwordError;
    private $passwordRepeatError;
    private $emailError;

    public function validateEmptyFields($username, $password, $passwordRepeat, $email){

        if (empty($username)){
            $this->usernameError = 'Por favor, introduce un código de usuario.';
        }

        if(empty($password)){
            $this->passwordError = 'Por favor, introduce una contraseña.';
        }

        if(empty($passwordRepeat)){
            $this->passwordRepeatError = 'Por favor, confirma la contraseña.';
        }

        if(empty($email)){
            $this->emailError = 'Por favor, introduce una cuenta de correo electrónico válida.';
        }
    }

    public function validatePasswordMatching($password, $passwordRepeat){

        if(empty($this->passwordRepeatError)){
            if($password != $passwordRepeat){
                $this->passwordRepeatError = 'Las contraseñas no coinciden.';
            }
        }
    }

    public function validateUserExists($connection, $username){

        if(empty($this->usernameError)){
            include_once 'UserRepository.inc.php';

            if(UserRepository::usernameExists($connection, $username)){
                $this->usernameError = 'El código de usuario ya existe.';
            }
        }
    }

    public function validateEmailExists($connection, $email){

        if(empty($this->emailError)){
            include_once 'UserRepository.inc.php';

            if(UserRepository::emailExists($connection, $email)){
                $this->emailError = 'Ya existe un usuario con esa cuenta de correo electrónico.';
            }
        }
    }

    public function getUsernameError(){
        return $this->usernameError;
    }

    public function getPasswordError(){
        return $this->passwordError;
    }

    public function getPasswordRepeatError(){
        return $this->passwordRepeatError;
    }

    public function getEmailError(){
        return $this->emailError;
    }
}