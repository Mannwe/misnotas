<?php
    $title = 'MisNotas -  Error';

    include_once 'app/config.inc.php';
    include_once 'templates/header.inc.php';
    include_once 'templates/navbar.inc.php';  
    include_once 'app/SessionManager.inc.php';  

    if(SessionManager::sessionStarted()){

        $errorMessages = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : 'Error inesperado.';
        $username = $_SESSION['username'];
        $errors = is_array($errorMessages) ? $errorMessages : (array)$errorMessages;
        $totalErrors = count($errors);

?>
    <!-- Variables recibidas de php y guardadas en el documento -->
    <div class="container">
        <div class="row mb-5"></div>
        <div class="row row-cols-3">
            <div class="col-1"></div>
            <div class="col-10">
                <div class="card mt-5">
                    <div class='card-header text-light bg-secondary'>
                        <h3 class="pt-3 pl-3">MisNotas - <span class='h4'>Resumen Errores</span></h3>
                    </div>
                    <div class="card-body py-4">
                        <?php 
                            foreach($errors as $error){
                                echo '<div class="alert alert-danger mt-3" role="alert">';
                                echo $error;
                                echo '</div>';
                            }
                        ?>                        
                    </div>                                        
                </div>
                <div class="alert alert-secondary mt-3" role="alert">
                    <?php 
                        if(count($errors) > 1){ 
                            echo 'Se han producido ' . count($errors) . ' errores.';
                        }else{
                            echo 'Se ha producido 1 error.';
                        }
                    ?>
                </div>
                <a class="mt-3 btn btn-outline-secondary text-secondoary btn-lg" href="<?php echo MAIN_PATH . '/' . $username ?>"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
    </button>
<?php
    }

    require_once 'templates/footer.inc.php';
?>

<script>
$(document).ready(function(){
    navbar.hideToolbarButtons('All');
});
</script>

