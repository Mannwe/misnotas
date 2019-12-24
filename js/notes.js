/**********************************************************************************/
/*  		            Código javascript para gestionar las notas                */
/**********************************************************************************/

'use strict'

var notes = {
    jsNotesArray: [],

    setNotesArray: function(jsNotesArray){
        notes.jsNotesArray = jsNotesArray;
    },

    // Construimos el array de notas a través del DOM
    displayNotesArray: function(selectedNode){
        let html = '';
        notes.jsNotesArray.forEach((jsNote) =>{
            // Mostramos todas las notas en formato Card, contrayendo todas menos la primera
            html+= '<div class="card border-secondary text-success mb-2 py-0">' +
                '<div class="container my-0 py-0">' +
                '<div class="row p-0 m-0">' +
                '<span id="dt-' + jsNote['id'] + '"class="deleteNote notSelectable col p-0 text-left display-6 text-muted" aria-hidden="true"><small>' +
                jsNote['creationDate'] + '</small></span>' +
                '<a id="del-' + jsNote['id'] + '" href="" data-toggle="modal" data-target="#noteDeletionConfirm" class="notSelectable deleteNote col m-0 p-0 text-right h4 text-muted" aria-hidden="true">&times;</a>' +
                '</div>';
            if(jsNote['expanded']){
                html += '<div class="row card-header note p-0 m-0"><i id="down-' + jsNote['id'] + '"class="down text-primary fas fa-caret-up"></i><h5 id="d-' + jsNote['id'] +
                        '" class="notSelectable ml-2 align-top pt-0 text-primary"> ' + jsNote['description'] + '</h5></div></div>' +
                        '<div class="card-body text-secondary">' +
                        '<p id="t-' + jsNote['id'] + '" class="text text-justify card-text" data-toggle="tooltip" title="Haz clic para editar la nota">' + jsNote['text'] + '</p>';
            }else{
                html += '<div class="row card-header p-0 m-0"><i id="down-' + jsNote['id'] + '"class="down text-primary fas fa-caret-down"></i><h5 id="d-' + jsNote['id'] +
                        '" class="notSelectable ml-2 align-top pt-0 text-primary"> ' + jsNote['description'] + '</h5></div></div>' +
                        '<div class="card-body text-secondary pt-0 pb-1 my-0">' +
                        '<span id="t-' + jsNote['id'] + '" class="text-justify card-text h4">...</span>';
            }
            html+= '</div></div>';
        });
        $('#notesList').html(html);
    },

    expandOrCollapseNote: function(e){
        let DOMId = e.target.id;
        let selectedNote = notes.retrieveNoteFromArray(DOMId);
        if(selectedNote != null){
            notesListArray.forEach((jsNote) =>{
                if(jsNote == selectedNote){
                    jsNote['expanded'] = !jsNote['expanded'];
                }
            });
            notes.setNotesArray(notesListArray);
            notes.displayNotesArray();
        }
        return notesListArray;
    },

    retrieveNoteFromArray: function(DOMId){
        let idArray = DOMId.split('-');
        let noteId = idArray[1];

        var selectedNoteObject = null;
        notes.jsNotesArray.forEach((jsNote) =>{
            if(jsNote['id'] == noteId){
                selectedNoteObject = jsNote;
            }
        });
        return selectedNoteObject;
    },

    editNote: function(DOMId, selectedNodeObject, nodesStructure){
        let container = $('#view');
        let node = JSON.stringify(selectedNodeObject);
        let note = notes.retrieveNoteFromArray(DOMId);

        // Tiene que haber una estructura de notas y que la seleccionada está expandida
        if(notes.jsNotesArray != null && note['expanded']){
            let parameters = {currentNode: node, note: JSON.stringify(note), nodesStructure: JSON.stringify(nodesStructure)};
            container.load(editNoteFormPath, parameters);
        }
    },

    printNoteErrors: function (responseArray){
        let container = $('#editNoteMsg');

        // Si hay errores empezamos a montar los avisos
        var html = '<div class="card-body pt-1">';
        if(responseArray['id'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['id'] +
                    '</div>';
        }
        if(responseArray['noteNotExists'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['noteNotExists'] +
                    '</div>';
        }
        if(responseArray['databaseNote'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['databaseNote'] +
                    '</div>';
        }
        if(responseArray['unknown'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['unknown'] +
                    '</div>';
        }
        html += '</div>';
        container.html(html)
    },

    isErrorArrayEmpty: function (errorArray){
        return errorArray['id'] == '' &&
               errorArray['noteNotExists'] == '' &&
               errorArray['databaseNote'] == '' &&
               errorArray['unknown'] == '';
    },

    showConfirmationMessageDOM: function(){
        let container = $('#deleteNoteConfirmation');

        // Añadimos el mensaje de confirmación de borrado al código html
        let html = '<div class="modal fade" id="noteDeletionConfirm" tabindex="-1" role="dialog" aria-hidden="true">' +
                   '<div class="modal-dialog modal-dialog-centered" role="document">' +
                   '<div class="modal-content">' +
                   '<div class="modal-header bg-dark text-light">' +
                   '<h5 class="modal-title">MisNotas - <span class="h6">Mensaje de confirmación</span></h5>' +
                   '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                   '<span class="text-ligh" aria-hidden="true">&times;</span></button></div>' +
                   '<div class="modal-body">¿Está seguro de que desea borrar esta nota?</div>' +
                   '<form method="post" action=' + deletionNotePath + '>' +
                   '<div class="modal-footer border-top-0">' +
                   '<input type="hidden" id="selectedNoteId" name="selectedNoteId" value=0>' +
                   '<button type="submit" class="btn btn-outline-success text-success"><i class="fas fa-check"></i> Aceptar</button>' +
                   '<button id="cancelDeleteNote" type="button" class="btn btn-outline-secondary text-secondary" data-dismiss="modal">' +
                   '<i class="far fa-window-close"></i> Cancelar</button></div></form>' +
                   '</div></div></div>';
        container.html(html);
    },

    findNodeFromNote: function(DOMId){
        let noteObject = notes.retrieveNoteFromArray(DOMId);
        let nodeId = noteObject['nodeId'];
        let nodeObject = nodes.retrieveNodeFromArray(nodeId);
        return nodeObject;
    }
}
