<?php
    
    include_once 'config.inc.php';
    include_once 'SessionManager.inc.php';
    include_once 'Connection.inc.php';
    include_once 'Node.inc.php';
    include_once 'NodeValidation.inc.php';
    include_once 'UserRepository.inc.php';
    include_once 'NodesStructureLoad.inc.php';

    if(SessionManager::sessionStarted()){

        $errors = false;
        $username = $_SESSION['username'];

        $parent = isset($_POST['parent']) ? $_POST['parent'] : null;
        if(is_null($parent)){
            $errors = true;
            $_SESSION['errorMessage']['parent'] = 'No se ha podido recuperar el nodo padre.';
        }else{
            $_SESSION['errorMessage']['parent'] = '';
        }

        // Nos conectamos a la base de datos
        Connection::openConnection();
        $connection = Connection::getConnection();

        $nodeName = '';
        $nodenameError = '';
        $newNodeId = 0;

        // Transformamos la clase stdClass de json_decode en Node
        if(!is_null($parent)){
            if(empty($parent)){
                $tmpUser = UserRepository::getUserByUsername($connection, $username);
                $userId = $tmpUser->getId();
                if(isset($userId) && !is_null($userId)){
                    $parentNode = new Node($userId,
                                            0,
                                            0,
                                            '',
                                            false,
                                            false,
                                            false,
                                            0,
                                            0);
                }else{
                    $parentNode = null;
                }         
            }else{
                /* Al hacer el load desde Javascript acaba convirtiendo un parámetro
                    en formato JSON (el objeto padre) en un array. Por lo tanto, no
                    usaremos decode */
                $parentNode = Node::castingFromStdClass($_POST['parent']);  
            }
        }

        if(!isset($parentNode) || is_null($parentNode)){
            $errors = true;
            $_SESSION['errorMessage']['parentNode'] = 'Error desconocido al obtener el nodo padre.';
        }else{
            $_SESSION['errorMessage']['parentNode'] = '';
        }

        // Recuperamos las variables introducidas en el formulario
        $nodeName = isset($_POST['nodename']) ? $_POST['nodename'] : null;

        if(is_null($nodeName)){
            $errors = true;
            $_SESSION['errorMessage']['nodename'] = 'Error desconocido al obtener el nombre del nodo.';
        }else{
            $_SESSION['errorMessage']['nodename'] = '';
        }

        if(!isset($_POST['nodesStructure'])){
            $errors = true;
            $_SESSION['errorMessage']['nodesStructure'] = 'Error al obtener la estructura de nodos.';
        }else{
            $nodesStructure = $_POST['nodesStructure'];
            $_SESSION['errorMessage']['nodesStructure'] = '';
        }

        // Construimos el nuevo objeto Node (sin Id todavía, pero no es necesaria para las validaciones)
        if(isset($parentNode) && !is_null($parentNode)){
            $newNode = new Node($parentNode->getUserId(),
                                0,
                                $parentNode->getId(),
                                $nodeName,
                                false,
                                false,
                                false,
                                0,
                                0);
        }

        // Aplicamos las validaciones
        $validator = new NodeValidation();
        $validator->validateEmptyFields($nodeName);
        if(isset($newNode)){
            $validator->validateNodenameExists($connection, $newNode);
        }
        $nodenameError = $validator->getNodenameError();
        $validationOK = empty($nodenameError);

        if(!$validationOK){
            $errors = true;
            $_SESSION['errorMessage']['nodeNameError'] = $nodenameError;
        }else{
            $_SESSION['errorMessage']['nodeNameError'] = '';
        }

        $_SESSION['errorMessage']['unknown'] = '';
        if(!$errors){
            $newNodeId = NodeRepository::createNode($connection, $newNode);
            if(!$newNodeId){
                $errors = true;
                $_SESSION['errorMessage']['unknown'] = 'Error desconocido al crear el nodo.';
            }
        }

        /* En caso de que haya estructura en la vista, cargamos la nueva estructura de nodos 
            de la base de datos y la transformamos en array para compararla */
        if(isset($nodesStructure)){
            if(empty($nodesStructure)){
                // Creamos un array con el nuevo nodo como único elemento
                $newNode->setId($newNodeId);
                $newNode->setSelected(true); 
                $databaseStructure[] = $newNode;
                $_SESSION['nodesStructure'] = json_encode($databaseStructure); 
            }else{
                $structure = new NodesStructureLoad();
                $structure->load(); 

                // Estructura temporal que luego actualizaremos y transformaremos en la definitiva
                $databaseStructureTmp = $structure->getNodesStructureArray();

                if(isset($databaseStructureTmp)){
                    // Transformamos la estructura de la vista en un array para compararla
                    $nodesArray = json_decode($_POST['nodesStructure']);

                    // Actualizamos la estructura según parámetros con la situación actual en la vista
                    foreach($databaseStructureTmp as $databaseNode){
                        foreach($nodesArray as $stdViewObject){
                            // Transformamos los Std Objects en Nodes
                            $viewNode = Node::castingFromStdClass($stdViewObject);                                

                            if($viewNode->getId() == $databaseNode->getId()){
                                // Asignamos su situación de expandido tal y como la teníamos en la vista
                                $databaseNode->setExpanded($viewNode->isExpanded());   
                                break;                                 
                            }
                        }

                        // El nuevo nodo pasa a ser el seleccionado
                        if($databaseNode->getId() == $newNodeId){
                            $databaseNode->setSelected(true);                            
                        }else{
                            $databaseNode->setSelected(false);                            
                        }

                        $databaseStructure[] = $databaseNode;
                    }

                    // Devolvemos el array a formato JSON para guardarlo actualizado en la variable de sesión
                    $_SESSION['nodesStructure'] = json_encode($databaseStructure); 
                } // if(isset($databaseStructureTmp)){
            } // if(!empty($_POST['nodesStructure'])){
        } // if(isset($nodesStructure)){

        if(!isset($databaseStructure)){
            if($_SESSION['errorMessage']['unknown'] == ''){
                $_SESSION['errorMessage']['newStructure'] = 'Error al construir la nueva estructura de nodos. Nodo creado de todas formas.';
            }else{
                $_SESSION['errorMessage']['newStructure'] = 'Error al construir la nueva estructura de nodos.';
            }
        }else{
            $_SESSION['errorMessage']['newStructure'] = '';
        }
        echo json_encode($_SESSION['errorMessage']);

        Connection::closeConnection();
    }    