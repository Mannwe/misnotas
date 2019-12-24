/**********************************************************************************/
/*  		   Código javascript para gestionar la barra de navegación		      */
/**********************************************************************************/

var navbar = {
    hideToolbarButtons: function(button){
        if(button == 'All'){
            if(!$('#newNodeNavbar').is(":hidden")) $('#newNodeNavbar').hide();
            if(!$('#removeNodeNavbar').is(":hidden")) $('#removeNodeNavbar').hide();
            if(!$('#editNodeNavbar').is(":hidden")) $('#editNodeNavbar').hide();
            if(!$('#newNoteNavbar').is(":hidden")) $('#newNoteNavbar').hide();
        }else{
            if(!$('#' + button).is(":hidden")) $('#' + button).hide();
        }
    },
    showToolbarButtons: function(button){
        if(button == 'All'){
            if($('#newNodeNavbar').is(":hidden")) $('#newNodeNavbar').show();
            if($('#removeNodeNavbar').is(":hidden")) $('#removeNodeNavbar').show();
            if($('#editNodeNavbar').is(":hidden")) $('#editNodeNavbar').show();
            if($('#newNoteNavbar').is(":hidden")) $('#newNoteNavbar').show();
        }else{
            if($('#' + button).is(":hidden")) $('#' + button).show();
        }
    }
}