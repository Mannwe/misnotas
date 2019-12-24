/**********************************************************************************/
/* Código javascript para controlar la modificación de notas desde                */
/* editNoteForm.inc.php                                                           */  
/**********************************************************************************/

function printNoteErrors(responseArray){

    let container = $('#editNoteMsg');

    // Si hay errores empezamos a montar los avisos
    var html = '<div class="card-body pt-1">';
    if(responseArray['id'] != ''){
        html += '<div class="alert alert-danger mt-3" role="alert">' +
                responseArray['id'] +
                '</div>';
        $('#noteDescription').focus();
    }
    if(responseArray['description'] != ''){
        html += '<div class="alert alert-danger mt-3" role="alert">' +
                responseArray['description'] +
                '</div>';
        $('#noteDescription').focus();
    }
    if(responseArray['noteNotExists'] != ''){
        html += '<div class="alert alert-danger mt-3" role="alert">' +
                responseArray['noteNotExists'] +
                '</div>';
        $('#noteDescription').focus();
    }
    if(responseArray['databaseNote'] != ''){
        html += '<div class="alert alert-danger mt-3" role="alert">' +
                responseArray['databaseNote'] +
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
        if(responseArray['id'] == '' && 
           responseArray['description'] == '' && 
           responseArray['noteNotExists'] == '' && 
           responseArray['databaseNote'] == '' && 
           responseArray['unknown'] == '')
        $('#noteText').focus();
    }
    html += '</div>';
    container.html(html)
}

function isErrorArrayEmpty(errorArray){
    return errorArray['id'] == '' &&
           errorArray['description'] == '' &&
           errorArray['noteNotExists'] == '' &&
           errorArray['databaseNote'] == '' &&
           errorArray['unknown'] == '' &&
           errorArray['text'] == '';
}

function sendUpdatedNote(e){
    e.preventDefault();
    let id = $('#noteId').val();
    let description = $('#noteDescription').val();
    let noteText = $('#noteText').val();
    let parameters = {id: id,
                        description: description,
                        text: noteText};
    $.ajax({
        url: editNotePath, 
        type: 'POST', 
        data: parameters,
        success: function (response) { 
            console.log(response);
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

$('#editNoteSubmit').on('click', sendUpdatedNote);
$('#editNoteCancel').on('click', function(e){
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