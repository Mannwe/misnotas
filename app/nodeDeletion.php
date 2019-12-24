<?php

    include_once 'config.inc.php';
    include_once 'SessionManager.inc.php';
    include_once 'Node.inc.php';
    include_once 'NodeValidation.inc.php';
    include_once 'NodeRepository.inc.php';
    include_once 'UserRepository.inc.php';
    include_once 'Redirection.inc.php';
    include_once 'NodesStructureLoad.inc.php';

    if(SessionManager::sessionStarted()){

        $username = $_SESSION['username'];

        $errors = false;
        $_SESSION['errorMessage'] = [];
        
        // Nos conectamos a la base de datos
        Connection::openConnection();
        $connection = Connection::getConnection();

        // Aplicamos las validaciones
        // Estructura temporal que luego actualizaremos y transformaremos en la definitiva
        $structure = new NodesStructureLoad();
        $structure->load(); 
        $databaseStructureTmp = $structure->getNodesStructureArray();

        foreach($databaseStructureTmp as $databaseNode){
            /* Todos son false para evitar que quede algún nodo seleccionado mantenido de 
                una situación anterior */
            $databaseNode->setSelected(false); 
            $databaseStructure[] = $databaseNode;
        }
        
        if(!isset($databaseStructure)){
            $_SESSION['errorMessage'][] = 'Error al construir la nueva estructura de nodos.';
            Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
            Connection::closeConnection();
            return;
        }

        $_SESSION['nodesStructure'] = json_encode($databaseStructure); 
        
        $user = UserRepository::getUserByUsername($connection, $username);
        if(!isset($user)){
            $_SESSION['errorMessage'][] = 'Usuario inexistente.';
            $errors = true;
        }

        if(!isset($_POST['selectedNodeId'])){
            $_SESSION['errorMessage'][] = 'Nodo no definido.';
            Connection::closeConnection();
            Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
            return;
        }

        $nodeId = $_POST['selectedNodeId'];
        if($nodeId == 0){
            $_SESSION['errorMessage'][] = 'Error al obtener el identificador del nodo.';
            $errors = true;
        }

        /* Construimos un objeto Nodo con tan solo el id de usuario
           y su propio id, porque para borrarlos no se necesitan más
           datos */
        $node = new Node($user->getId(),
                        $nodeId,
                        0,
                        '',
                        false,
                        false,
                        false,
                        0,
                        0);

        $nodeDeletionError = '';
        $validator = new NodeValidation();

        $validator->validateNodeExists($connection, $node);
        $nodeExistsError = $validator->getNodeExistsError();
        if(!empty($nodeExistsError)){
            $_SESSION['errorMessage'][] = $nodeExistsError;
            $errors = true;
        }

        $validator->validateNodeHasChildren($connection, $node);
        $nodeHasChildrenError = $validator->getNodeWithChildrenError();
        if(!empty($nodeHasChildrenError)){
            $_SESSION['errorMessage'][] = $nodeHasChildrenError;
            $errors = true;
        }

        $validator->validateNodeHasNotes($connection, $node);
        $nodeWithNotesError = $validator->getNodeWithNotesError();
        if(!empty($nodeWithNotesError)){
            $_SESSION['errorMessage'][] = $nodeWithNotesError;
            $errors = true;
        }

        if($errors){
            Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
            Connection::closeConnection();
            return;
        }

        NodeRepository::deleteNode($connection, $node);
        $nodeExists = NodeRepository::nodeExists($connection, $node);
        if($nodeExists){
            $_SESSION['errorMessage'][] = 'Error inesperado. No se ha podido eliminar el nodo seleccionado.';
            Redirection::redirect(ERRORS_PAGE_PATH . '/' . $username);
            Connection::closeConnection();
            return;
        }

        // Volvemos a cargar los datos de la base de datos tras el borrado
        if(!isset($structure)){
            $structure = new NodesStructureLoad();                
        }
        $structure->initializeStructure();
        $structure->load(); 
        $databaseStructure = $structure->getNodesStructureArray();
        $_SESSION['nodesStructure'] = json_encode($databaseStructure); 

        Redirection::redirect(MAIN_PATH . '/' . $username);
        Connection::closeConnection();

    } // if(SessionManager::sessionStarted()){