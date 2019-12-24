<?php

include_once 'User.inc.php';

class UserRepository{
    
    public static function createUser($connection, $user){

        $newUserOk = false;

        if(isset($connection)){
            try{
                // Preparamos la sentencia
                $sql = 'INSERT INTO usuarios (nombre, clave, fecha_registro, email) VALUES (:username, :password, NOW(), :email)';
                $statement = $connection->prepare($sql);

                // Vinculamos los campos necesarios
                $username = $user->getUsername();
                $statement->bindParam(':username', $username, PDO::PARAM_STR);
                $password = $user->getPassword();
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                $email = $user->getEmail();
                $statement->bindParam(':email', $email, PDO::PARAM_STR);

                $newUserOk = $statement->execute();

            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }            
        }
        return $newUserOk;
    }

    public static function usernameExists($connection, $username){

        $userExists = 0; // False
        if(isset($connection)){
            try{
                $sql = 'SELECT * FROM usuarios WHERE nombre = :username';
                $statement = $connection->prepare($sql);

                $statement->bindParam(':username', $username, PDO::PARAM_STR);
                $statement->execute();

                $result = $statement->fetchAll();
                $userExists = count($result);

            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }            
        }
        return $userExists;
    }

    public static function emailExists($connection, $email){

        $emailExists = 0; // False
        if(isset($connection)){
            try{
                $sql = 'SELECT * FROM usuarios WHERE email = :email';
                $statement = $connection->prepare($sql);

                $statement->bindParam(':email', $email, PDO::PARAM_STR);
                $statement->execute();

                $result = $statement->fetchAll();
                $emailExists = count($result);

            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }            
        }
        return $emailExists;
    }

    public static function getUserByUsername($connection, $username){
        
        $user = null;
        if(isset($connection)){
            try{
                $sql = 'SELECT * FROM usuarios WHERE nombre = :username';
                $statement = $connection->prepare($sql);

                $usernameTmp = $username;
                $statement->bindParam(':username', $usernameTmp, PDO::PARAM_STR);
                $statement->execute();
                $result = $statement->fetch();

                if(!empty($result)){
                    $user = new User($result['id'],
                                     $result['nombre'],
                                     $result['clave'],
                                     $result['fecha_registro'],
                                     $result['email']);
                }
                
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            } 
        }
        return $user;
    }
}