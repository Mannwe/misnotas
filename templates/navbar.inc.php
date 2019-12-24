<?php
    // Incluimos las clases necesarias
    include_once 'app/SessionManager.inc.php'; 
?>


<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark mb-3">
    <div class="container">
        <span class="sr-only">Este botón despliega el botón de navegación</span>
        <?php 
        if(SessionManager::sessionStarted()){
        ?>
            <a class="navbar-brand" href="<?php echo MAIN_PATH . '/' . $_SESSION['username'] ?> "><h3>MisNotas</h3></a>
        <?php 
        }else{
        ?>  
            <h3 class="bg-dark text-light">MisNotas</h3>
        <?php 
        }
        ?>  
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Navigation">
            <span class="navbar-toggler-icon"></span>
        </button>   
        <div class="collapse navbar-collapse" id="navbar">
            <?php
            if(SessionManager::sessionStarted()){
            ?>
            <ul class="navbar-nav mr-sm-2">
                <li class="nav-item active">
                    <a id='newNodeNavbar' class="nav-link" href=""><i class="far fa-folder-open"></i> Nuevo Nodo</a>
                </li>
                <li class="nav-item active">
                    <a id='removeNodeNavbar' href='' class='nav-link' data-toggle="modal" data-target="#nodeDeletionConfirm"><i class="fas fa-trash-alt"></i> Borrar Nodo</a>
                </li>
                <li class="nav-item active">
                    <a id='editNodeNavbar' class="nav-link" href=""><i class="fas fa-edit"></i> Editar Nodo</a>
                </li>   
                <li class="nav-item active">
                    <a id='newNoteNavbar' class="nav-link" href=""><i class="far fa-file"></i> Nueva Nota</a>
                </li>                   
            </ul>
            <?php
            }
            ?>
            <ul class="navbar-nav mr-sm-2 ml-auto">
                <?php 
                if(SessionManager::sessionStarted()){
                ?>
                <li class="nav-item active">
                    <?php
                        echo '<a class="nav-link" href=""><i class="fas fa-user"></i> ';
                        echo $_SESSION['username'];
                    ?>
                    </a>
                </li>
                <li class="nav-item active">
                    <a id='closeSession' class="nav-link" href="<?php echo LOGOUT_PATH ?>"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                </li>
                <?php 
                }else{
                ?>
                <li class="nav-item active">
                    <a id='startSession' class="nav-link" href="<?php echo LOGIN_PATH ?>"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo SIGNUP_PATH ?>"><i class="fas fa-user-plus"></i> Registrarse</a>
                </li>
                <?php 
                } 
                ?>   
            </ul>
        </div>
    </div>       
</nav>