/**********************************************************************************/
/*  		            Código javascript para gestionar los nodos                */
/**********************************************************************************/

'use strict'

var nodes = {
    jsNodesArray: [],
    nodeBoxesArray: [],
    jsNodeChangedOnDragAndDrop: null,

    setNodesArray: function(jsNodesArray){
        nodes.jsNodesArray = jsNodesArray;
    },

    setNodeChangedOnDragAndDrop: function(jsNodeChanged){
        nodes.jsNodeChangedOnDragAndDrop = jsNodeChanged;
    },

    getNodeChangedOnDragAndDrop: function(){
        return nodes.jsNodeChangedOnDragAndDrop;
    },

    drawNodesStructure: function(currentParent){
        var html = '';
        nodes.jsNodesArray.forEach((jsNode) =>{

            let nodeName = jsNode['name'];
            let nodeId = jsNode['id'];

            if ((currentParent == null && jsNode['parentId'] == 0) ||
                (currentParent != null && jsNode['parentId'] == currentParent['id'])){
                if (jsNode['hasChildren']){
                    if(jsNode['selected']){
                        if(jsNode['expanded']){
                            html += '<li><i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-down"></i><h5 id="' + nodeId + '" class="nodeSelected"> ' + nodeName + '</h5>';
                        }else{
                            html += '<li><i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-right"></i><h5 id="' + nodeId + '" class="nodeSelected"> ' + nodeName + '</h5>';
                        }
                    }else{
                        if(jsNode['expanded']){
                            html += '<li><i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-down"></i><h5 id="' + nodeId + '" class="node"> ' + nodeName + '</h5>';
                        }else{
                            html += '<li><i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-right"></i><h5 id="' + nodeId + '" class="node"> ' + nodeName + '</h5>';
                        }
                    }

                    if(jsNode['expanded']){
                        html += '<ul>';
                        html += nodes.drawNodesStructure(jsNode);
                        html += '</ul></li>';
                    }else{
                        html += '</li>';
                    }
                }else{
                    if(jsNode['selected']){
                        html += '<li><h5 id="' + nodeId + '" class="nodeSelected"> ' + nodeName + '</h5></li>';
                    }else{
                        html += '<li><h5 id="' + nodeId + '" class="node"> ' + nodeName + '</h5></li>';
                    }
                }
            }
        });
        return html;
    },

    // Desplegamos la estructura de nodos
    nodesStructureDeployment: function(){

        var html = '';
        var nodesMenu = $('#nodesMenu');

        html = nodes.drawNodesStructure(null);

        var html = '<ul class="mb-3">' + html + '</ul>';

        nodesMenu.html(html);
    },

    // Expande el nodo seleccionado (nodeId) de la estructura jsNodesArray
    expandNode: function (jsNodeObject){

        // Obtenemos el nombre y el código del nodo actual
        let nodeId = jsNodeObject['id'];
        let nodeName = jsNodeObject['name'];

        // Marcamos el nodo actual como expandido y seleccionado
        jsNodeObject['expanded'] = true;

        // Buscamos todos los hijos del nodo actual
        if(jsNodeObject['selected']){
            var html = '<i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-down"></i><h5 id="' + nodeId + '" class="nodeSelected"> ' + nodeName + '</h5><ul>';
        }else{
            var html = '<i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-down"></i><h5 id="' + nodeId + '" class="node"> ' + nodeName + '</h5><ul>';
        }

        nodes.jsNodesArray.forEach(jsNode =>{
            // Dibujamos los nodos cuyo padre es el actual
            if(jsNode['parentId'] == nodeId){
                let childName = jsNode['name'];
                let childId = jsNode['id'];

                // Añadimos los hijos en el DOM
                if (jsNode['hasChildren']){
                    if(jsNode['selected']){
                        html += '<li><i id="icon-' + childId + '" class="nodeIcon fas fa-caret-right"></i><h5 id="' + childId + '" class="nodeSelected"> ' + childName + '</h5></li>';
                    }else{
                        html += '<li><i id="icon-' + childId + '" class="nodeIcon fas fa-caret-right"></i><h5 id="' + childId + '" class="node"> ' + childName + '</h5></li>';
                    }
                }else{
                    if(jsNode['selected']){
                        html += '<li><h5 id="' + childId + '" class="nodeSelected"> ' + childName + '</h5></li>';
                    }else{
                        html += '<li><h5 id="' + childId + '" class="node"> ' + childName + '</h5></li>';
                    }
                }
            }
        });

        html += '</ul>';

        $('#' + nodeId).parent().html(html);

        nodes.setNodesArray(jsNodesArray);
        return jsNodesArray;
    },

    // Contrae el nodo seleccionado (nodeId) de la estructura jsNodesArray
    collapseNode: function (jsNodeObject){
        // Obtenemos el nombre y el código del nodo actual
        let nodeId = jsNodeObject['id'];
        let nodeName = jsNodeObject['name'];
        jsNodeObject['expanded'] = false;
        let html = '';

        // Buscamos el componente DOM del nodo seleccionado y le asignamos el nuevo códig HTML
        if(jsNodeObject['selected']){
            html = '<li><i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-right"></i><h5 id="' + nodeId + '" class="nodeSelected"> ' + nodeName + '</h5></li>';
        }else{
            html = '<li><i id="icon-' + nodeId + '" class="nodeIcon fas fa-caret-right"></i><h5 id="' + nodeId + '" class="node"> ' + nodeName + '</h5></li>';
        }
        $('#' + nodeId).parent().html(html);
    },

    unselectAll: function(){
        nodes.jsNodesArray.forEach(jsNode =>{
            jsNode['selected'] = false;
        });
    },

    retrieveNodeFromArray: function(nodeId){
        var selectedNodeObject = null;

        nodes.jsNodesArray.forEach(jsNode =>{
            if(jsNode['id'] == nodeId){
                selectedNodeObject = jsNode;
            }
        });

        return selectedNodeObject;
    },

    // Busco los padres y los marco como expandidos
    keepParentsExpanded: function(childNode){
        let parentNode = nodes.findParent(childNode);

        if(parentNode != null){
            let nodeId = parentNode['id'];

            if($('#' + nodeId + ' i').hasClass('fa-caret-right')) $('#' + nodeId + ' i').removeClass('fa-caret-right');
            if(!$('#' + nodeId + ' i').hasClass('fa-caret-down')) $('#' + nodeId + ' i').addClass('fa-caret-down');
            nodes.keepParentsExpanded(parentNode, nodes.jsNodesArray);
        }
    },

    findParent: function(childNode){
        var parentNode = null;

        nodes.jsNodesArray.forEach(jsNode =>{
            if(jsNode['id'] == childNode['parentId']){
                parentNode = jsNode;
            }
        });
        return parentNode;
    },

    expandSelectedNodeParent: function(){
        // Primero buscamos el nodo seleccionado
        var selectedNode = null;

        nodes.jsNodesArray.forEach((jsNode) =>{
            if(jsNode['selected']) selectedNode = jsNode;
        });

        // Luego buscamos el padre y lo marcamos como expandido
        if (selectedNode != null){
            let parentNode = nodes.findParent(selectedNode);
            if(parentNode != null) parentNode['expanded'] = true;
        }
    },

    changeDescription: function(nodeObject){
        let nodesStructure = JSON.stringify(nodes.jsNodesArray);
        let nodeName = $('#editField').val();
        if(nodeObject['name'] == nodeName) {
            window.location.href = mainPath + '/' + userName;
            return;
        }
        nodeObject['name'] = nodeName;
        let parameters = {selectedNode: nodeObject, nodesStructure: nodesStructure};

        // Obtenemos los objetos Node de PHP mediante AJAX
        $.ajax({
            url: editNodePath,
            type: 'POST',
            data: parameters,
            success: function(response){
                $('body').html(response);
            }
        });
    },

    changeParent: function(nodeObject){
        // Obtenemos los objetos Node de PHP mediante AJAX
        if(nodeObject != null){
            let nodesStructure = JSON.stringify(nodes.jsNodesArray);
            let parameters = {selectedNode: nodeObject, nodesStructure: nodesStructure};
            $.ajax({
                url: editNodePath,
                type: 'POST',
                data: parameters,
                success: function(response){
                    $('body').html(response);
                }
            });
        }
    },

    findSelectedNode: function(){
        let selectedNode = null;
        nodes.jsNodesArray.forEach((jsNode) => {
            if(jsNode['selected']) selectedNode = jsNode;
        });
        return selectedNode;
    },

    setNodesPosition: function(){
        nodes.jsNodesArray.forEach((jsNode) => {
            if(typeof $('#' + jsNode['id']).offset() !== 'undefined'){ // Si el nodo no está visible hay que omitir este paso
              let top = $('#' + jsNode['id']).offset().top;
              let left = $('#' + jsNode['id']).offset().left;
              jsNode['top'] = top;
              jsNode['left'] = left;
            }
        });

        return jsNodesArray;
    },

    buildCurrentBoxesArray: function(){
        nodes.nodeBoxesArray = [];
        nodes.jsNodesArray.forEach((jsNode) => {
            if(typeof $('#' + jsNode['id']).offset() !== 'undefined'){ // Si el nodo no está visible hay que omitir este paso
              let width = $('#' + jsNode['id']).width();
              let height = $('#' + jsNode['id']).height();
              let top = $('#' + jsNode['id']).offset().top;
              let left = $('#' + jsNode['id']).offset().left;

              let nodeBox = new NodeBox(jsNode['id'], left, top, width, height);
              nodes.nodeBoxesArray.push(nodeBox);
            }
        });
    },

    findNodeCurrentBox: function(nodeId){
        let currentBox;
        nodes.nodeBoxesArray.forEach((box) =>{
            if(box.nodeId == nodeId){
                currentBox = box;
                return false;
            }
        });
        return currentBox;
    },

    nodeHasChildren: function(parentId){
        let isStillParent = false;
        nodes.jsNodesArray.forEach((jsNode) => {
            if(jsNode['parentId'] == parentId){
                isStillParent = true;
                return false;
            }
        });
        return isStillParent;
    }
}
