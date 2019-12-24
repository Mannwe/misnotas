<?php

include_once '../app/config.inc.php';
include_once '../app/SessionManager.inc.php';

if(SessionManager::sessionStarted()){
    $username = $_SESSION['username'];
    $_SESSION['nodesStructure'] = $_POST['nodesStructure'];
    $currentNode = $_POST['currentNode'];
    $currentNote = (array)json_decode($_POST['note']); // Cargamos la estructura de nodos para que refresque bien
    $nodesStructure = $_POST['nodesStructure']; // La almacenamos en una variable para enviarla a JS
?>

<div class="card border-secondary">
    <div class='card-header bg-dark text-light'>
        <h3 class="text-center">Modificación de la nota</h3>
    </div>
    <div class="card-body border-secondary my-3">
        <div class="form-group">
            <input type='hidden' id='noteId' value=<?php echo $currentNote['id'] ?>>
            <label for="noteDescription">Descripción de la nota:</label>
            <input type="text" spellcheck="false" autocorrect="off" class="form-control mb-1" id='noteDescription' placeholder="Introduzca una descripción sencilla" autofocus
                value="<?php echo $currentNote['description'] ?>" >

            <label for="noteText">Texto:</label>
            <textarea spellcheck="false" autocorrect="off" class="form-control" rows=10 id='noteText' placeholder="Introduzca el texto de la nota"><?php echo $currentNote['text'] ?></textarea>
        </div>
        <div id='editNoteMsg'>
        </div>
        <button id='editNoteSubmit' class="btn btn-outline-success text-success btn-lg"><i class="fas fa-save"></i> Guardar</button>
        <button id='editNoteCancel' class="btn btn-outline-secondary text-secondary btn-lg"><i class="far fa-window-close"></i> Cancelar</button>
    </div>
</div>
<?php
require_once '../app/SendFromPHPToJS.inc.php';
}
?>
<script src="<?php echo PATH_JS ?>editNote.js" ></script>
