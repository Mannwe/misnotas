/**********************************************************************************/
/*  		   Código javascript para gestionar los drag and drops   		      */
/**********************************************************************************/

'use strict'

var dragAndDrop = {
    moveNode: function(ui, nodeId){
        let boxNode = null;
        nodes.nodeBoxesArray.forEach((box) =>{
            if(box.isInBox(ui.offset.left, ui.offset.top)){
                boxNode = box;
                return false;
            }
        });
        if(boxNode != null){
            // Si el nodo es el mismo, lo devolvemos a su posición original
            if(nodeId == boxNode.nodeId){
                $('#' + nodeId).offset({top:boxNode.y,left:boxNode.x});
            }else{
                let nodeObject = nodes.retrieveNodeFromArray(nodeId);
                if(nodeObject['parentId'] != boxNode.nodeId){
                    nodeObject['parentId'] = boxNode.nodeId;

                    // Le indicamos al nodo de la caja que ahora tiene hijos y que está expandido
                    let boxNodeObject = nodes.retrieveNodeFromArray(boxNode.nodeId);
                    boxNodeObject['hasChildren'] = true;
                    boxNodeObject['expanded'] = true;

                    // Registramos el nodo modificado
                    nodes.setNodeChangedOnDragAndDrop(nodeObject);
                }
            }
        }else{
            /* Si lo deja fuera de cualquier caja, le asignaremos padre 0 al nodo
               en caso de que no lo tenga ya */
            let nodeObject = nodes.retrieveNodeFromArray(nodeId);
            if(nodeObject['parentId'] == 0){
                let boxNode = nodes.findNodeCurrentBox(nodeId);
                $('#' + nodeId).offset({top:boxNode.y,left:boxNode.x});
            }else{
                let parentId = nodeObject['parentId'];
                nodeObject['parentId'] = '0';

                /* Reasignamos las propiedades hasChildren y expanded en caso de
                   que el nodo padre se quede sin hijos */
                let oldParent = nodes.retrieveNodeFromArray(parentId);
                if(oldParent != null){
                    if(nodes.nodeHasChildren(parentId)){
                        oldParent['expanded'] = true;
                        oldParent['hasChildren'] = true;
                    }else{
                        oldParent['expanded'] = false;
                        oldParent['hasChildren'] = false;
                    }
                }
                // Registramos el nodo modificado
                nodes.setNodeChangedOnDragAndDrop(nodeObject);
            }
        }
    },

    /****************** No me convence el efecto ***********
     pero lo dejo aquí por si le doy otra vuelta ***********
    moveNote: function(ui, noteId, startPosX, startPosY){
        let boxNode = null;
        nodes.nodeBoxesArray.forEach((box) =>{
            if(box.isInBox(ui.offset.left, ui.offset.top)){
                boxNode = box;
                return false;
            }
        });
        if(boxNode != null){
            // Si la nota no cambia de nodo, la devolvemos a su posición original
            let nodeObject = notes.findNodeFromNote(noteId);
            let nodeId = nodeObject['id'];
            if(nodeId == boxNode.nodeId){
                console.log(startPosX, startPosY);
                $('#' + noteId).offset({top:startPosY,left:startPosX});
            }
        }
    }
    ********************************************************/
}
