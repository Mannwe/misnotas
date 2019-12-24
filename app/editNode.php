<?php

    include_once 'SessionManager.inc.php';
    include_once 'Connection.inc.php';
    include_once 'Node.inc.php';
    include_once 'NodeValidation.inc.php';
    include_once 'NodeRepository.inc.php';
    include_once 'Redirection.inc.php';

if(SessionManager::sessionStarted()){

    $username = $_SESSION['username'];

    $errors = false;
    $_SESSION['errorMessage'] = [];

    if(!isset($_POST['nodesStructure'])){
        $_SESSION['errorMessage'][] = 'Error desconocido al obtener la estructura de nodos.';
        Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
        return;
    }

    $tmpNodesStructure = json_decode($_POST['nodesStructure']);
    $nodesStructureArray = [];

    // Recuperamos las variables pasadas a través del post
    /*$nodeName = isset($_POST['nodeName']) ? $_POST['nodeName'] : null;

    if(is_null($nodeName)){
        $_SESSION['errorMessage'][] = 'Error desconocido al obtener el nombre del nodo.';
    }*/

    if(!isset($_POST['selectedNode'])){
        $_SESSION['errorMessage'][] = 'Nodo no definido.';
    }

    $nodeArray = $_POST['selectedNode'];
    $node = new Node($nodeArray['userId'],
                     $nodeArray['id'],
                     $nodeArray['parentId'],
                     $nodeArray['name'],
                     $nodeArray['hasChildren'],
                     $nodeArray['expanded'],
                     $nodeArray['selected'],
                     $nodeArray['top'],
                     $nodeArray['left']);

    // Nos conectamos a la base de datos
    Connection::openConnection();
    $connection = Connection::getConnection();

    $nodeId = $node->getId();
    $nodeEditError = '';
    $validator = new NodeValidation();

    $validator->validateNodeExists($connection, $node);
    $nodeExistsError = $validator->getNodeExistsError();
    if(!empty($nodeExistsError)){
        $_SESSION['errorMessage'][] = $nodeExistsError;
    }

    if($errors){
        // Actualizamos la variable de sesión
        foreach($tmpNodesStructure as $stdViewObject){
            // Transformamos los Std Objects en Nodes
            $updatedNode = Node::castingFromStdClass($stdViewObject);
            $nodesStructureArray[] = $updatedNode;
        }
        $_SESSION['nodesStructure'] = json_encode($nodesStructureArray);

        Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
        Connection::closeConnection();
        return;
    }

    $ok = NodeRepository::updateNode($connection, $node);
    if(!$ok){
        $_SESSION['errorMessage'][] = 'Error inesperado. No se ha podido modificar el nodo seleccionado.';

        // Actualizamos la variable de sesión
        foreach($tmpNodesStructure as $stdViewObject){
            // Transformamos los Std Objects en Nodes
            $updatedNode = Node::castingFromStdClass($stdViewObject);
            $nodesStructureArray[] = $updatedNode;
        }
        $_SESSION['nodesStructure'] = json_encode($nodesStructureArray);
        Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
        Connection::closeConnection();
        return;
    }

    /* Actualizamos la lista de nodos de la vista con el nombre del nodo seleccionado cambiado */
    foreach($tmpNodesStructure as $stdViewObject){
        // Transformamos los Std Objects en Nodes
        $updatedNode = Node::castingFromStdClass($stdViewObject);

        if($updatedNode->getId() == $nodeId){
            // Asignamos su situación de expandido tal y como la teníamos en la vista
            $updatedNode->setName($node->getName());
            $updatedNode->setParentId($node->getParentId());
        }
        $nodesStructureArray[] = $updatedNode;
    }

    // Actualizamos la variable de sesión
    $_SESSION['nodesStructure'] = json_encode($nodesStructureArray);

    Redirection::redirect(MAIN_PATH . '/' . $username);
    Connection::closeConnection();

}
