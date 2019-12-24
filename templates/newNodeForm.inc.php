
<?php

include_once '../app/config.inc.php';
include_once '../app/SessionManager.inc.php';  

if(SessionManager::sessionStarted()){
    $username = $_SESSION['username'];
    $parentNode = '';
    $nodesStructure = '';

    // Recogemos los parámetros 
    if(isset($_POST['nodesStructure'])){
        $nodesStructure = $_POST['nodesStructure'];
    }else if(isset($_SESSION['nodesStructure'])){
        $nodesStructure = $_SESSION['nodesStructure'];
    }

    if(isset($_POST['parent'])){
        $parentNode = $_POST['parent'];
    }

?>
<div class="card border-secondary">
    <div class='card-header bg-dark text-light'>
        <h3 class="text-center">Nuevo nodo</h3>
    </div>
    <div class="card-body border-secondary m-3">
        <form method='post'>
            <div class="form-group">
                <label for="nodeName">Nombre del nodo:</label>
                <input type="text" spellcheck="false" autocorrect="off" class="form-control" id='nodename' placeholder="Introduzca un texto descriptivo" autofocus>
            </div>
            <div id='newNodeMsg'>
            </div>
            <a id='newNodeSubmit' class="btn btn-outline-success text-success btn-lg" href="#"><i class="fas fa-save"></i> Guardar</a>
            <a id='newNodeCancel' class="btn btn-outline-secondary text-secondary btn-lg" href="#"><i class="far fa-window-close"></i> Cancelar</a>
        </form>
    </div>
</div>
<?php
    require_once '../app/SendFromPHPToJS.inc.php';
}
?>
<script src="<?php echo PATH_JS ?>newNode.js" ></script>

