/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {
    //get the click of modal button to create / update item
    //we get the button by class not by ID because you can only have one id on a page and you can
    //have multiple classes therefore you can have multiple open modal buttons on a page all with or without
    //the same link.
    //we use on so the dom element can be called again if they are nested, otherwise when we load the content once it kills the dom element and wont let you load anther modal on click without a page refresh
    $(document).on('click', '.showModalButton', function () {
        // check if the reloadOnClose option is set and put de "javascript:location.reload();"
        // on "hide.bs.modal" event
        reloadOnClose = "";
        if ($(this).attr('value').indexOf("reloadOnClose") > 0) {
            reloadOnClose = " onclick='javascript:location.reload();'";
        }
        //check if the modal is open. if it's open just reload content not whole modal
        //also this allows you to nest buttons inside of modals to reload the content it is in
        //the if else are intentionally separated instead of put into a function to get the 
        //button since it is using a class not an #id so there are many of them and we need
        //to ensure we get the right button and content. 
        if ($('#modal').data('bs.modal').isShown) {
            $('#modal').find('#modalContent')
                    .load($(this).attr('value'));
        } else {
            //if modal isn't open; open it and load content 
            $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'));
        }
        //dynamiclly set the header for the modal
        document.getElementById('modalHeader').innerHTML = '<h4 class="text-left">' + $(this).attr('title') + '</h4><button id="closeAutoModalHeader" type="button" ' + reloadOnClose + ' class="close" data-dismiss="modal" aria-label="Close">Fechar janela&nbsp;&times;</button>';
    });
    $(".modal-title-null").click(function () {
        document.getElementById('modalHeader').remove();
    });
    $(".modal-default").click(function () {
        $('#modal').children('.modal-lg').removeClass('modal-lg').addClass('modal-default');
    });
    $(".modal-sm").click(function () {
        $('#modal').children('.modal-lg').removeClass('modal-lg').addClass('modal-sm');
    });
    $(".modal-350").click(function () {
        $('#modal').children('.modal-lg').removeClass('modal-lg').addClass('modal-350');
    });
    $(".modal-wsm").click(function () {
        $('#modal').children('.modal-lg').removeClass('modal-lg').addClass('modal-sm');
    });
    $(".modal-md").click(function () {
        $('#modal').children('.modal-lg').removeClass('modal-lg').addClass('modal-md');
    });
});