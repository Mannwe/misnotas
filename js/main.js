/**********************************************************************************/
/*  		   Código javascript para gestionar la pantalla principal		      */
/**********************************************************************************/

'use strict'

var jsNodesArray = [];
var notesListArray = [];
var currentPage = 'MainPage';
var selectedNodeObject = null;
// Posición de la nota para el drag and drop
/*var noteStartX = 0;
var noteStartY = 0;*/

$(document).ready(function(){

    /* Las variables de rutas se carga tras el login, así que establecemos
       esta excepción para que salte de manera controlada  si todavía no está definida  */
    if (typeof ajaxNodesRequestPath === 'undefined' ||
        typeof newNodeFormPath === 'undefined') {
       throw 'Variables de rutas de la aplicación no definidas.';
    }

    function stopDragNodes(ui, id){
        dragAndDrop.moveNode(ui, id);

        // Enviamos la estructura por Ajax a la bbdd
        if(nodes.getNodeChangedOnDragAndDrop != null){
            nodes.changeParent(nodes.jsNodeChangedOnDragAndDrop); // Cambiamos el padre en la bbdd a través de Ajax
            nodes.setNodeChangedOnDragAndDrop(null); // Reseteamos el nodo modificado a null para el próximo cambio
        }
    }

    function successNodesRequest(ajaxResponse){
        if(ajaxResponse != ''){
            jsNodesArray = JSON.parse(ajaxResponse);
            navbar.showToolbarButtons('newNodeNavbar');

            // Desplegamos la estructura de nodos
            if(jsNodesArray != null){
                nodes.setNodesArray(jsNodesArray);
                nodes.expandSelectedNodeParent();
                nodes.nodesStructureDeployment();
                //jsNodesArray = nodes.setNodesPosition(); // Asignamos top y left iniciales de los nodos visibles
                nodes.buildCurrentBoxesArray(); // Cajas que forman los nodos, para el drag and drop
                $('.node').draggable({
                    containment: $('#nodesMenu'),
                    stop: function(event, ui){
                        stopDragNodes(ui, $(this).attr('id'));
                    }
                });
                $('.nodeSelected').draggable({
                    containment: $('#nodesMenu'),
                    stop: function(event, ui){
                        stopDragNodes(ui, $(this).attr('id'));
                    }
                });
                /* Mostramos los botones de gestión de nodos y notas
                si hay alguno seleccionado (se acaba de crear) */
                let selectedNodeId = 0;
                jsNodesArray.forEach((jsNode) => {
                    if(jsNode['selected']) {
                        selectedNodeId = jsNode['id'];
                        selectedNodeObject = jsNode;
                    }
                });
                if(selectedNodeId != 0) {
                    retrieveNodeNotes(JSON.stringify(selectedNodeObject));
                    navbar.showToolbarButtons('All');
                }
                $('#nodeIdDelete').val(selectedNodeId);
            }else{
                navbar.hideToolbarButtons('All');
                navbar.showToolbarButtons('newNodeNavbar');
            }
        }
    }
    // Esta función la sacamos de nodes.js por la llamada a través de Ajax
    function retrieveNodesStructure(){

        // Obtenemos los objetos Node de PHP mediante AJAX
        $.ajax({
            url: ajaxNodesRequestPath,
            type: 'GET',
            success: successNodesRequest
        });
    }

    function retrieveNodeNotes(selectedNode){
        // Obtenemos los objetos Note de cada nodo de PHP mediante AJAX
        if(selectedNode != null){
            let parameters = {selectedNode: selectedNode};
            let html = '';
            /* Preparamos el DOM del mensaje de confirmación para poder acceder a él desde
               el botón de borrar */
            if($('#deleteNoteConfirmation').html() == '') notes.showConfirmationMessageDOM();
            $('#notesList').html(html);
            $.ajax({
                data: parameters,
                url: ajaxNotesRequestPath,
                type: 'POST',
                success: function(response){
                    if(response != ''){
                        notesListArray = JSON.parse(response);
                        notes.setNotesArray(notesListArray);
                        notes.displayNotesArray(selectedNode);

                        /****************** No me convence el efecto ***********
                         pero lo dejo aquí por si le doy otra vuelta ***********
                        $('.note').draggable({
                            containment: $('#nodesMenu'),
                            start: function(event, ui){
                                noteStartX = ui.offset.left;
                                noteStartY = ui.offset.top;
                                console.log('start desde main', noteStartX, noteStartY);
                            },
                            drag: function(event, ui){
                            },
                            stop: function(event, ui){
                                stopDragNotes(ui, $(this).attr('id'));
                            }
                        });
                        navbar.showToolbarButtons('newNoteNavbar');
                        ***********************************************************/
                    }
                }
            });
        }
    }

    /* Ocultamos los botones de borrar nodo y nueva nota, y los activaremos
       sólo si hay algún nodo seleccionado */
    navbar.hideToolbarButtons('All');
    retrieveNodesStructure();

    function nodesSelection(e){
        if(jsNodesArray != null && currentPage == 'MainPage'){
            var currentId = e.target.id;
            selectedNodeObject = nodes.retrieveNodeFromArray(currentId);
            let currentNodeSelected = $('#' + currentId).hasClass('nodeSelected');

            if(selectedNodeObject != null){
                //let id = $('#' + e.target.id).attr('id');
                // Primero deseleccionamos todos los nodos, para evitar mantener padres seleccionados
                $('.nodeSelected').each(function(){
                    let id = $(this).attr('id');
                    $('#' + id).removeClass('nodeSelected').addClass('node');
                });
                nodes.unselectAll();

                // Si no está seleccionado, lo seleccionamos
                if(!currentNodeSelected){
                    $('#' + currentId).removeClass('node').addClass('nodeSelected');
                    selectedNodeObject['selected'] = true;
                    nodes.keepParentsExpanded(selectedNodeObject);
                }
                if(selectedNodeObject['selected']){
                    navbar.showToolbarButtons('All');

                    /* Copiamos el id del nodo en el campo oculto del formulario de borrado
                    para poder enviarlo con el POST */
                    $('#nodeIdDelete').val(currentId);
                    retrieveNodeNotes(JSON.stringify(selectedNodeObject));
                }else{
                    $('#notesList').html('');
                    selectedNodeObject = null;
                    navbar.hideToolbarButtons('newNoteNavbar');
                    navbar.hideToolbarButtons('removeNodeNavbar');
                    navbar.hideToolbarButtons('editNodeNavbar');
                }
            }
        }
    }

    function expandOrCollapseNode(){
        if($(this).hasClass('fa-caret-down')){
            $(this).removeClass('fa-caret-down');
            if(!$(this).hasClass('fa-caret-right')) $(this).addClass('fa-caret-right');
        }else{
            $(this).removeClass('fa-caret-right');
            if(!$(this).hasClass('fa-caret-down')) $(this).addClass('fa-caret-down');
        }

        // Buscamos su único nodo 'hermano', que será el componente h5 de clase nodo
        let nodeId = $(this).next().attr('id');
        let expandedNodeObject = nodes.retrieveNodeFromArray(nodeId);

        // Expandimos/contraemos el nodo si tiene hijos y actualizamos
        if(expandedNodeObject['hasChildren']){
            if(expandedNodeObject['expanded']){
                nodes.collapseNode(expandedNodeObject);
            }else{
                jsNodesArray = nodes.expandNode(expandedNodeObject);
                nodes.keepParentsExpanded(expandedNodeObject);
            }
        }
        // Cajas que forman los nodos, para el drag and drop
        nodes.buildCurrentBoxesArray();

        // Volvemos a regisrar la propiedad draggable para que afecte a los nuevos nodos
        $('.node').draggable({
            containment: $('#nodesMenu'),
            stop: function(event, ui){
                stopDragNodes(ui, $(this).attr('id'));
            }
        });
        $('.nodeSelected').draggable({
            containment: $('#nodesMenu'),
            stop: function(event, ui){
                stopDragNodes(ui, $(this).attr('id'));
            }
        });
    }

    function newNode(e) {
        e.preventDefault(); // Para que no recargue la página

        /* Impedimos que se vuelva a lanzar la petición de nuevo
           nodo si estamos en la página de nuev nodo */
        if(currentPage != 'NewNode'){
            let container = $('#view');
            let parameters = '';
            let parentNode = '';

            /* Separamos los caso en los que no haya ningún nodo seleccionado (en cuyo caso irá al nodo raíz),
               y los que no haya estructura todavía */
            if (selectedNodeObject == null){
                parentNode = '';
            }else{
                parentNode = JSON.stringify(selectedNodeObject);
            }
            if(jsNodesArray != null && jsNodesArray.length){
                parameters = {parent: parentNode, nodesStructure: JSON.stringify(jsNodesArray)};
            }else{
                parameters = {parent: parentNode, nodesStructure: ''};
            }
            container.load(newNodeFormPath, parameters);
        }
        /* Ocultamos los botones mientras dure el proceso de alta */
        navbar.hideToolbarButtons('All');
        currentPage = 'NewNode';
        return false;
    }

    function newNote(e){
        e.preventDefault();
        if(currentPage != 'NewNote'){
            let container = $('#view');
            if(selectedNodeObject == null) return false;
            let node = JSON.stringify(selectedNodeObject);

            // Tiene que haber una estructura de nodos y que uno de ellos esté seleccionado
            if(node == '') return false;
            if(jsNodesArray == null || !jsNodesArray.length) return false;
            let nodesStructure = JSON.stringify(jsNodesArray);

            // Obtenemos el nodo actual mediante  mediante AJAX
            let parameters = {currentNode: node, nodesStructure: nodesStructure};
            if(node != ''){
                container.load(newNoteFormPath, parameters);
            }
        }
        navbar.hideToolbarButtons('All');
        currentPage = 'NewNote';
        return false;
    }

    /* Controlamos los eventos de seleccionar y deseleccionar los nodos
       Utilizamos on click porque son elementos creados dinámicamente */
    $('body').on('click','.node', function(e){
        nodesSelection(e);
        e.stopPropagation();
        e.stopImmediatePropagation();
    });
    $('body').on('click','.nodeSelected', function(e){
        nodesSelection(e);
        e.stopPropagation();
        e.stopImmediatePropagation();
    });
    $('body').on('click','.nodeIcon', expandOrCollapseNode);
    $('body').on('click','.down', function(e){
        notesListArray = notes.expandOrCollapseNote(e);
    });
    $('body').on('click','.text', function(e){
        e.preventDefault();
        if(selectedNodeObject != null){
            if(currentPage != 'EditNote'){
                notes.editNote(e.target.id, selectedNodeObject, jsNodesArray);
                navbar.hideToolbarButtons('All');
                currentPage = 'EditNote';
            }
        }
        return false;
    });

    // Controlamos el evento de clicar el botón de nuevo nodo y nueva nota
    $('#newNodeNavbar').bind('click', newNode);
    $('#newNoteNavbar').bind('click', newNote);
    $('#editNodeNavbar').bind('click', function(e){
        e.preventDefault();
        $('.nodeSelected').each(function(){
            // Creamos el campo para editar con el nombre del nodo y los botones de aceptar y cancelar
            let html = '<div class="row"><div class="col-9"><input type="text" id="editField" spellcheck="false" autocorrect="off" ' +
                       'required class="text-success form-control h-75" value=' + $(this).html() + '></div>' +
                       '<button type="button" id="acceptChanges" class="border-0 m-0 p-0 col-1"><i class="align-top     text-success far fa-check-circle"></i></button>' +
                       '<button type="button" id="refuseChanges" class="border-0 m-0 p-0 col-1"><i class="align-top text-danger fas fa-ban"></i></button></div>' +
                       '<input type="hidden" id="currentDescription" value=' + $(this).html() + '>'; // Añadimos un campo con la descripción actual para recuperar si cancelamos
            $(this).html(html);
            $('#editField').select();
            $('#editField').on('keydown', function(e){
                if(e.key == 'Enter') acceptChanges(e);
            });
            $('#acceptChanges').on('click', acceptChanges);
            $('#refuseChanges').on('click', refuseChanges);
        });
        $('#view *').prop('disabled', true);
        return false;
    });

    // Borrado de notas, con previo mensaje de confirmación
    $('body').on('click','.deleteNote', function(e){
        let selectedNote = notes.retrieveNoteFromArray(e.target.id);
        $('#selectedNoteId').val(selectedNote['id']);
    });

    function acceptChanges(e){
        e.preventDefault();

        if(selectedNodeObject == null){
            selectedNodeObject = nodes.findSelectedNode();
        }
        nodes.changeDescription(selectedNodeObject);
        return false;
    }

    function refuseChanges(e){
        e.preventDefault();
        $('.nodeSelected').each(function(){
            $(this).html($('#currentDescription').val());
        });
        $('#view *').prop('disabled', false);
        return false;
    }

    // Añadimos los tooltips personalizados de la aplicación
    tooltips.loadTooltips();
});
