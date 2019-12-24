/**********************************************************************************/
/*  Clase javascript que registra las cajas donde están los nodos, para el drag   */
/*  and drop                                                                      */
/**********************************************************************************/

'use strict'

function NodeBox(nodeId, x, y, width, height){
    this.nodeId = nodeId;
    this.x = x;
    this.y = y;
    this.width = width;
    this.height = height;
}

NodeBox.prototype.isInBox = function(posX, posY){
    return posY > this.y &&
           posY < parseInt(this.y) + parseInt(this.height) &&
           posX > this.x &&
           posX < parseInt(this.x) + parseInt(this.width);
}
