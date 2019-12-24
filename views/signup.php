<?php
    $title = 'MisNotas -  Registro';

    require_once 'templates/header.inc.php';
    require_once 'templates/navbar.inc.php';    

    // Accesos a la base de datos
    require_once 'app/config.inc.php';
    require_once 'app/Connection.inc.php';
    require_once 'app/User.inc.php';
    require_once 'app/UserRepository.inc.php';
    require_once 'app/SignupValidation.inc.php';

    if(isset($_POST['Signup'])){

        // Nos conectamos a la base de datos
        Connection::openConnection();
        $connection = Connection::getConnection();
        
        $usernameError = '';
        $passwordError = '';
        $passwordRepeatError = '';
        $emailError = '';

        $username = '';
        $password = '';
        $PasswordRepeat = '';
        $email = '';

        $newUserOk = false;

        // Recuperamos las variables introducidas en el formulario
        if(isset($_POST['Username'])) $username = $_POST['Username'];
        if(isset($_POST['Password'])) $password = $_POST['Password'];
        if(isset($_POST['PasswordRepeat'])) $passwordRepeat = $_POST['PasswordRepeat'];
        if(isset($_POST['Email'])) $email = $_POST['Email'];

        // Aplicamos las validaciones
        $validator = new SignupValidation();

        $validator->validateEmptyFields($username, $password, $passwordRepeat, $email);
        $validator->validatePasswordMatching($password, $passwordRepeat);
        $validator->validateUserExists($connection, $username);
        $validator->validateEmailExists($connection, $email);

        $usernameError = $validator->getUsernameError();
        $passwordError = $validator->getPasswordError();  
        $passwordRepeatError = $validator->getPasswordRepeatError(); 
        $emailError = $validator->getEmailError();

        $validationOK = empty($usernameError) && empty($passwordError) && 
                        empty($passwordRepeatError) && empty($emailError);

        if($validationOK){
            $user = new User(0, $username, password_hash($password, PASSWORD_DEFAULT), '', $email);
            $newUserOk = UserRepository::createUser($connection, $user);
        }

        Connection::closeConnection();
    }
?>

<form method='post' action='#'>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card my-3 border border-dark">
                    <div class="card-header bg-dark text-light"><h4>Introduce los datos de registro<h4></div>
                        <div class="card-body">
                            <div class="form-group m-4">
                            <label class="sr-only" for="username">Código de usuario</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-user"></i></div>
                                </div>
                                
                                <?php
                                // Si hemos pulsado el botón registro, copiamos los valores de los campos introducidos
                                if (isset($_POST['Signup']) && !empty($username)){
                                    echo '<input type="text" class="form-control" name="Username" id="username" placeholder="Código de usuario" value="'; 
                                    echo $username . '">';
                                ?>
                                <?php

                                }else{
                                ?>
                                <input type="text" class="form-control" name="Username" id='username' placeholder="Código de usuario">
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                            if(isset($_POST['Signup']) && !empty($usernameError)){
                            ?>  
                                <div id='alert' class="alert alert-danger mt-3" role="alert">
                                    <?php echo $usernameError ?>
                                    <script>
                                    document.getElementById('username').focus();
                                    </script>
                                </div>
                            <?php 
                            }
                            ?>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                </div>
                                <input type="password" class="form-control" name="Password" id='password' placeholder="Contraseña">
                            </div>
                            <?php
                            if(isset($_POST['Signup']) && !empty($passwordError)){
                            ?>  
                                <div class="alert alert-danger mt-3" role="alert">
                                <?php echo $passwordError;
                                if(!empty($usernameError)){
                                    ?>
                                        <script>
                                        document.getElementById('username').focus();
                                        </script>
                                    <?php
                                    }else{
                                    ?>   
                                        <script>
                                        document.getElementById('password').focus();
                                        </script>
                                    <?php 
                                    }
                                ?>
                                </div>
                            <?php 
                            }
                            ?>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                </div>
                                <input type="password" class="form-control" name="PasswordRepeat" id='passwordRepeat' placeholder="Repite la contraseña">
                            </div>
                            <?php
                            if(isset($_POST['Signup']) && !empty($passwordRepeatError)){
                            ?>  
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?php echo $passwordRepeatError;
                                    if(!empty($usernameError)){
                                        ?>
                                        <script>
                                        document.getElementById('username').focus();
                                        </script>
                                    <?php
                                    }else if(!empty($passwordError)){
                                    ?>   
                                        <script>
                                        document.getElementById('password').focus();
                                        </script>
                                        <?php 
                                    }else{
                                    ?>
                                        <script>
                                        document.getElementById('passwordRepeat').focus();
                                        </script>
                                    <?php 
                                    }
                                    ?>
                                </div>
                            <?php 
                            }
                            ?>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-at"></i></div>
                                </div>
                                <?php
                                // Si hemos pulsado el botón registro, copiamos los valores de los campos introducidos
                                if (isset($_POST['Signup']) && !empty($email)){
                                    echo '<input type="email" class="form-control" name="Email" id="email" placeholder="E-mail"'; 
                                    echo $email . '">';
                                ?>
                                <?php
                                }else{
                                ?>
                                <input type="email" class="form-control" name="Email" id='email' placeholder="E-mail">
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                            if(isset($_POST['Signup']) && !empty($emailError)){
                            ?>  
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?php echo $emailError;
                                    if(!empty($usernameError)){
                                        ?>
                                        <script>
                                        document.getElementById('username').focus();
                                        </script>
                                    <?php
                                    }else if(!empty($passwordError)){
                                    ?>   
                                        <script>
                                        document.getElementById('password').focus();
                                        </script>
                                        <?php 
                                    }else if(!empty($passwordRepeatError)){
                                    ?>   
                                        <script>
                                        document.getElementById('passwordRepeat').focus();
                                        </script>
                                        <?php 
                                    }else{
                                    ?>
                                        <script>
                                        document.getElementById('email').focus();
                                        </script>
                                    <?php 
                                    }
                                    ?>
                                </div>
                            <?php 
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" id='submit' name='Signup' class="btn btn-warning btn-block btn-lg mt-3 text-dark">Registrarse</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="reset" class="btn btn-warning btn-block btn-lg mt-3 text-dark">Limpiar datos</button>
                                </div>    
                            </div>
                            <?php
                            // Mostramos los mensajes de error o éxito
                            if (isset($_POST['Signup']) && $validationOK){
                                if(!$newUserOk){
                            ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    Error desconocido al crear el usuario.
                                </div>
                            <?php 
                                }else{
                            ?>
                                <div class="alert alert-success mt-3" role="alert">
                                    Registro creado con éxito.<br>
                                    <a href="<?php echo LOGIN_PATH ?>">Inicia sesión</a> para comenzar a usar tu cuenta.
                                </div>
                            <?php 
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</form>

<?php
    require_once 'templates/footer.inc.php';
?>