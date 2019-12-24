<?php

// Enviamos a JS los paths definidos en PHP para las distintas vistas
$username = $_SESSION['username'];
echo '<script>';
echo 'var ajaxNodesRequestPath = "' . NODE_REQUEST_PATH . '"; ';
echo 'var ajaxNotesRequestPath ="' . NOTE_REQUEST_PATH . '"; ';
echo 'var ajaxNodesUpdate = "' . NODE_UPDATE_PATH . '"; ';
echo 'var ajaxNodeRepository = "' . NODE_REPOSITORY_PATH . '"; ';
echo 'var newNodeFormPath = "' . NEW_NODE_FORM_PATH . '"; ';
echo 'var mainPath = "' . MAIN_PATH . '"; ';
echo 'var newNodePath = "' . NEW_NODE_PATH . '"; ';
echo 'var deletionNodePath =" ' . NODE_DELETION_PATH . '"; ';
echo 'var newNoteFormPath ="' . NEW_NOTE_FORM_PATH . '"; ';
echo 'var newNotePath ="' . NEW_NOTE_PATH . '"; ';
echo 'var editNoteFormPath ="' . EDIT_NOTE_FORM_PATH . '"; ';
echo 'var editNotePath ="' . EDIT_NOTE_PATH . '"; ';
echo 'var deletionNotePath ="' . NOTE_DELETION_PATH . '"; ';
echo 'var editNodePath = "' . EDIT_NODE_PATH . '"; ';
echo 'var userName = "' . $username . '"; ';

echo 'var nodesStructure = ""; ';
if(isset($nodesStructure) && !empty($nodesStructure)){
    echo 'nodesStructure = JSON.stringify(' . $nodesStructure . '); ';
}
echo 'var parentNode = ""; ';
if(isset($parentNode)){
    if(!empty($parentNode)){
        echo 'parentNode = ' . $parentNode . '; ';
    }
}
if(isset($currentNode)){
    echo 'var currentNode = ' . $currentNode . '; ';
}
echo '</script>';
