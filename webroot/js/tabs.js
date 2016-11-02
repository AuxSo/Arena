/**
 * Created by Aux on 26/10/2016.
 */
$(function () {

    // Quand on clique sur un des onglets
    $('.tab').click(function () {

        // Stockage du dernier onglet sélectionné
        var lastSelected = $('.selected');
        // Il n'est plus sélectionné
        lastSelected.removeClass('selected');
        // L'onglet cliqué est sélectionné
        $(this).addClass('selected')

        // On cache les infos du combattant anciennement sélectionné (on passe par l'id qui est stocké dans "name" de l'onglet
        $('#' + lastSelected.attr('id').split("tab")[1]).addClass('hidden');
        // On affiche l'onglet actuellement sélectionné (idem)
        $('#' + $(this).attr('id').split("tab")[1]).removeClass('hidden');

    })

    // Quand on clique sur "I forgot my password"
    $('#forgot').click(function () {
        $('#lostMdp').slideDown();
    })

})