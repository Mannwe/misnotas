/**********************************************************************************/
/* Código javascript para controlar la creación de notas desde                    */
/* newNoteForm.inc.php                                                            */
/**********************************************************************************/

'use strict'

$(document).ready(function(){

    function printNoteErrors(ajaxResponse){

        let container = $('#newNoteMsg');

        // Si hay errores empezamos a montar los avisos
        var html = '<div class="card-body pt-1">';
        if(responseArray['currentNode'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['currentNode'] +
                    '</div>';
            $('#noteDescription').focus();
        }
        if(responseArray['description'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['description'] +
                    '</div>';
            $('#noteDescription').focus();
        }
        if(responseArray['noteExists'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['noteExists'] +
                    '</div>';
            $('#noteDescription').focus();
        }
        if(responseArray['unknown'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['unknown'] +
                    '</div>';
            $('#noteDescription').focus();
        }
        if(responseArray['text'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    responseArray['text'] +
                    '</div>';
            if(responseArray['currentNode'] == '' &&
                responseArray['description'] == '' &&
                responseArray['noteExists'] == '' &&
                responseArray['unknown'] == '')
            $('#noteText').focus();
        }
        html += '</div>';
        container.html(html)
    }

    function isErrorArrayEmpty(errorArray){
        return errorArray['currentNode'] == '' &&
               errorArray['description'] == '' &&
               errorArray['noteExists'] == '' &&
               errorArray['unknown'] == '' &&
               errorArray['text'] == '';
    }

    function sendNewNote(e){

        e.preventDefault();

        let description = $('#noteDescription').val();
        let noteText = $('#noteText').val();
        let parameters = {currentNode: currentNode,
                          description: description,
                          text: noteText};
        $.ajax({
            url: newNotePath,
            type: 'POST',
            data: parameters,
            success: function (response) {
                let responseArray = JSON.parse(response); // Pasamos a array para leerlo desde JS
                if(isErrorArrayEmpty(responseArray)){
                    window.location.href = mainPath + '/' + userName;
                }else{
                    printNoteErrors(responseArray);
                }
            }
        });
        return false;
    }

    $('#newNoteSubmit').on('click', sendNewNote);
    $('#newNoteCancel').on('click', function(e){
        e.preventDefault();
        if(nodesStructure != ''){
            $.ajax({
                type: "POST",
                url: ajaxNodesUpdate,
                data: {nodesStructure: nodesStructure},
                success: function(response){
                    window.location.href = mainPath + '/' + userName;
                }
            });
        }
        return false;
    });

});
