<?php
$title = 'MisNotas -  Pantalla Principal';

include_once 'templates/header.inc.php';
include_once 'templates/navbar.inc.php';
include_once 'app/SessionManager.inc.php';

if(SessionManager::sessionStarted()){

?>
    <!-- Variables recibidas de php y guardadas en el documento -->
    <div class="container">
        <div class="row mb-5"></div>
        <div class="row">
            <h1 class='w-100 text-center my-5'>Gestor de Notas</h1>
        </div>
        <div class="row row-cols-2">
            <div class="col-4">
                <div class="card border-secondary">
                    <div class='card-header bg-warning text-dark'>
                        <h4 class="pt-3 pl-3"><strong>Menú</strong></h4>
                    </div>
                    <div id='nodesMenu' class="card-body">
                    </div>
                </div>
            </div>
            <div id='view' class="col-8 mb-4">
                <div class="card border-secondary">
                    <div class='card-header bg-dark text-light'>
                        <h4 class="pt-3 pl-3">Notas</h4>
                    </div>
                    <div id='notesList' class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mensaje de confirmación de borrado de un nodo -->
    <div class="modal fade" id="nodeDeletionConfirm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-light">
                    <h5 class="modal-title">MisNotas - <span class='h6'>Mensaje de confirmación</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class='text-light' aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea borrar este nodo?
                </div>
                <form id='deleteForm' method='post' action='<?php echo NODE_DELETION_PATH ?>'>
                    <div class="modal-footer border-top-0">
                        <input type='hidden' id='nodeIdDelete' name='selectedNodeId' value=0>
                        <button type='submit' name="delete" class="btn btn-outline-success text-success"><i class="fas fa-check"></i> Aceptar</button>
                        <button id='cancelDelete' type="button" class="btn btn-outline-secondary text-secondary" data-dismiss="modal"><i class="far fa-window-close"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id='deleteNoteConfirmation'></div>
<?php
    require_once 'app/SendFromPHPToJS.inc.php';
}
require_once 'templates/footer.inc.php';
?>
