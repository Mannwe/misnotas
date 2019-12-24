<?php
    
    // Accesos a la base de datos
    include_once 'app/config.inc.php';
    include_once 'app/Connection.inc.php';
    include_once 'app/SessionManager.inc.php';
    include_once 'app/LoginValidation.inc.php';
    include_once 'app/Redirection.inc.php';
    include_once 'app/NodesStructureLoad.inc.php';

    $usernameError = '';
    $passwordError = '';
    $username = '';
    $password = '';

    // Inicializamos si hay sesión de usuario abierta
    SessionManager::closeSession();

    if(isset($_POST['Login'])){

        // Nos conectamos a la base de datos
        Connection::openConnection();
        $connection = Connection::getConnection();

        // Recuperamos las variables introducidas en el formulario
        if(isset($_POST['Username'])) $username = $_POST['Username'];
        if(isset($_POST['Password'])) $password = $_POST['Password'];

        // Aplicamos las validaciones
        $validator = new LoginValidation();
        $validator->validateEmptyFields($username, $password);
        $validator->validateUserExists($connection, $username);
        $validator->validatePassword($connection, $username, $password);

        $usernameError = $validator->getUsernameError();
        $passwordError = $validator->getPasswordError();        

        $validationOK = empty($usernameError) && empty($passwordError);

        if($validationOK){
            SessionManager::startSession($username);
            $structure = new NodesStructureLoad();
            $structure->load(); 
            Redirection::redirect(MAIN_PATH . '/' . $username);            
        }

        Connection::closeConnection();
    }

    $title = 'MisNotas -  Inicio de sesión';

    include_once 'templates/header.inc.php';
    include_once 'templates/navbar.inc.php';    
?>

<form method='post'>
    <div class="container my-5">
        <div class="m-5 row"></div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card my-3 border border-dark">
                    <div class="card-header bg-dark text-light"><h4>Introduce tus datos de acceso<h4></div>
                        <div class="card-body">
                            <div class="form-group m-4">
                            <label class="sr-only" for="username">Código de usuario</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-user"></i></div>
                                </div>
                                <?php
                                // Si hemos pulsado el botón registro, copiamos los valores de los campos introducidos
                                if (isset($_POST['Login']) && !empty($username)){
                                    echo '<input type="text" class="form-control" name="Username" id="username" placeholder="Código de usuario" value="'; 
                                    echo $username . '" autofocus>';
                                }else{
                                ?>
                                <input type="text" class="form-control" name='Username' id='username' placeholder="Código de usuario" autofocus>
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                            if(isset($_POST['Login']) && !empty($usernameError)){
                            ?>  
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?php echo $usernameError ?>
                                </div>
                                <script>
                                document.getElementById('username').focus();
                                </script>
                            <?php 
                            }
                            ?>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                </div>
                                <?php
                                // Si hemos pulsado el botón registro, copiamos los valores de los campos introducidos
                                if (isset($_POST['Login']) && !empty($password)){
                                    echo '<input type="password" class="form-control" name="Password" id="password" placeholder="Contraseña" value="'; 
                                    echo $password . '">';
                                }else{
                                ?>
                                <input type="password" class="form-control" name='Password' id='password' placeholder="Contraseña">
                                <?php
                                }
                                ?>
                                
                            </div>
                            <?php
                            if(isset($_POST['Login']) && !empty($passwordError)){
                            ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?php echo $passwordError ?>
                                    <script>
                                    document.getElementById('password').focus();
                                    </script>
                                </div>
                            <?php 
                            }
                            ?>
                            <button type="submit" name='Login' class="btn btn-warning btn-block btn-lg mt-3">Iniciar sesión</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</form>

<?php
    include_once 'templates/footer.inc.php';
?>