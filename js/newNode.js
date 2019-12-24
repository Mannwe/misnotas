/**********************************************************************************/
/* Código javascript para controlar la creación de nodos desde                    */
/* newNodeForm.inc.php                                                            */
/**********************************************************************************/

'use strict'

$(document).ready(function(){

    function printNodeErrors(ajaxResponse){

        let container = $('#newNodeMsg');

        // Si hay errores empezamos a montar los avisos
        $('#nodename').focus();

        var html = '<div class="card-body pt-1">';
        if(ajaxResponse['parent'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    ajaxResponse['parent'] +
                    '</div>';

        }
        if(ajaxResponse['parentNode'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    ajaxResponse['parentNode'] +
                    '</div>';
        }
        if(ajaxResponse['nodename'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    jaxResponse['nodename'] +
                    '</div>';
        }
        if(ajaxResponse['nodesStructure'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    ajaxResponse['nodesStructure'] +
                    '</div>';
        }
        if(ajaxResponse['nodeNameError'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    ajaxResponse['nodeNameError'] +
                    '</div>';
        }
        if(ajaxResponse['unknown'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    ajaxResponse['unknown'] +
                    '</div>';
        }
        if(ajaxResponse['newStructure'] != ''){
            html += '<div class="alert alert-danger mt-3" role="alert">' +
                    ajaxResponse['newStructure'] +
                    '</div>';
        }
        html += '</div>';
        container.html(html)
    }

    function isErrorArrayEmpty(errorArray){
        return errorArray['parent'] == '' &&
               errorArray['parentNode'] == '' &&
               errorArray['nodename'] == '' &&
               errorArray['nodesStructure'] == '' &&
               errorArray['nodeNameError'] == '' &&
               errorArray['unknown'] == '' &&
               errorArray['newStructure'] == '';
    }

    function sendNewNode(e){
        e.preventDefault();
        let nodename = $('#nodename').val();

        // Cuando no hay nodos, la estructura viene como undefined
        if (typeof nodesStructure === 'undefined'){
            nodesStructure = "";
        }

        let parameters = {parent: parentNode, nodesStructure: nodesStructure, nodename: nodename};
        $.ajax({
            url: newNodePath,
            type: 'POST',
            data: parameters,
            success: function (response) {
                let responseArray = JSON.parse(response); // Pasamos a array para leerlo desde JS
                if(isErrorArrayEmpty(responseArray)){
                    window.location.href = mainPath + '/' + userName;
                }else{
                    printNodeErrors(responseArray);
                }
            }
        });
        return false;
    }

    $('#newNodeSubmit').bind('click', sendNewNode);
    $('#newNodeCancel').bind('click', function(e){
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
