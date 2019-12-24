<?php

include_once 'Note.inc.php';

class NoteRepository{
    public static function noteExistsByDescription($connection, $note){

        $noteExists = 0;
        if(isset($connection)){
            try{
                if(isset($note)){
                    $nodeId = $note->getNodeId();
                    $description = $note->getDescription();

                    $sql = 'SELECT * FROM notas WHERE id_nodo = :nodeid AND descripcion = :notedescription';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':nodeid', $nodeId, PDO::PARAM_STR);
                    $statement->bindParam(':notedescription', $description, PDO::PARAM_STR);
                    $statement->execute();

                    $result = $statement->fetchAll();

                    if(count($result)) {
                        $noteExists = true;
                    }else{
                        $noteExists = false;
                    }
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $noteExists;
    }

    public static function createNote($connection, $note){

        $ok = false;
        if(isset($connection)){
            try{
                if(isset($note)){
                    // Preparamos la sentencia
                    $sql = 'INSERT INTO notas (id_nodo, descripcion, texto, fecha_creacion) VALUES (:nodeid, :noteDescription, :noteText, NOW());';
                    $statement = $connection->prepare($sql);

                    // Vinculamos los campos necesarios
                    $nodeId = $note->getNodeId();
                    $description = $note->getDescription();
                    $text = $note->getText();

                    $statement->bindParam(':nodeid', $nodeId, PDO::PARAM_STR);
                    $statement->bindParam(':noteDescription', $description, PDO::PARAM_STR);
                    $statement->bindParam(':noteText', $text, PDO::PARAM_STR);

                    $statement->execute();
                    $ok = $statement->rowCount() > 0;

                    /*************** Esto funciona, reemplazado por rowCount
                    $ok = $statement->execute();

                    // Recuperamos el id del nodo recién creado
                    if($ok){
                        $sql = 'SELECT LAST_INSERT_ID()';
                        $statement = $connection->prepare($sql);
                        $statement->execute();

                        $result = $statement->fetch();
                        $newNoteId = $result[0];
                    }
                    *******************************/
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $ok;
    }

    public static function nodeHasNotes($connection, $node){

        $hasNotes = 0;
        if(isset($connection)){
            try{
                if(isset($node)){
                    $nodeId = $node->getId();

                    $sql = 'SELECT * FROM notas WHERE id_nodo = :nodeid;';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam(':nodeid', $nodeId, PDO::PARAM_STR);
                    $statement->execute();

                    $result = $statement->fetchAll();
                    $hasNotes = count($result);
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $hasNotes;
    }

    public static function getNotesByNode($connection, $node){
        $arrayNotes = null;
        if(isset($connection)){
            try{
                if(!is_null($node)){
                    $nodeId = $node->getId();

                    $sql = 'SELECT * FROM notas WHERE id_nodo = :nodeid ORDER BY fecha_creacion DESC';
                    $statement = $connection->prepare($sql);

                    $statement->bindParam('nodeid', $nodeId);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    foreach($result as $record){
                        $date = date("m-d-Y H:i", strtotime($record['fecha_creacion']));
                        $note = new Note($record['id'],
                                         $record['id_nodo'],
                                         $record['descripcion'],
                                         $record['texto'],
                                         $date,
                                         false);
                        $arrayNotes[] = $note;
                    }

                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $arrayNotes;
    }

    public static function retrieveNote($connection, $noteId){

        $note = null;
        if(isset($connection)){
            try{
                $sql = 'SELECT * FROM notas WHERE id = :noteid';
                $statement = $connection->prepare($sql);

                $statement->bindParam(':noteid', $noteId, PDO::PARAM_STR);
                $statement->execute();

                $result = $statement->fetchAll();

                foreach($result as $record){
                    $date = date("m-d-Y H:i", strtotime($record['fecha_creacion']));
                    $note = new Note($record['id'],
                                     $record['id_nodo'],
                                     $record['descripcion'],
                                     $record['texto'],
                                     $date,
                                     false);
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $note;
    }

    public static function updateNote($connection, $note){

        $ok = false;
        if(isset($connection)){
            try{
                if(isset($note)){
                    // Vinculamos los campos necesarios
                    $noteId = $note->getId();
                    $description = $note->getDescription();
                    $text = $note->getText();

                    // Primero comprobamos que se han introducido datos diferentes
                    $sql = 'SELECT * FROM notas WHERE id = :noteid AND descripcion = :noteDescription AND texto = :noteText';

                    // Preparamos la sentencia
                    $statement = $connection->prepare($sql);
                    $statement->bindParam(':noteid', $noteId, PDO::PARAM_STR);
                    $statement->bindParam(':noteDescription', $description, PDO::PARAM_STR);
                    $statement->bindParam(':noteText', $text, PDO::PARAM_STR);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    if(count($result)){
                        $ok = true;
                    }else{
                        // No hay registros, por lo que se está tratando de actualizar realmente la tabla
                        $sql = 'UPDATE notas SET descripcion = :noteDescription, texto = :noteText WHERE id = :noteid;';
                        $statement = $connection->prepare($sql);
                        $statement->bindParam(':noteid', $noteId, PDO::PARAM_STR);
                        $statement->bindParam(':noteDescription', $description, PDO::PARAM_STR);
                        $statement->bindParam(':noteText', $text, PDO::PARAM_STR);

                        $statement->execute();
                        $ok = $statement->rowCount() > 0;
                    }
                }
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $ok;
    }

    public static function deleteNote($connection, $id){

        $ok = false;
        if(isset($connection)){
            try{
                // Preparamos la sentencia
                $sql = 'DELETE FROM notas WHERE id = :noteid;';
                $statement = $connection->prepare($sql);

                $statement->bindParam(':noteid', $id, PDO::PARAM_STR);
                $statement->execute();
                $ok = $statement->rowCount() > 0;
            }catch(PDOException $ex){
                print 'Error: ' . $ex->getMessage();
            }
        }
        return $ok;
    }
}
