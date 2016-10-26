/**
 * Created by Aux on 26/10/2016.
 */
$(function(){
console.log('here');
$('.tab').click(function(){
    var lastSelected = $('.selected');
    lastSelected.removeClass('selected');
    $(this).addClass('selected')

    console.log('#' + $(this).attr('name'));
    $('#' + lastSelected.attr('name')).addClass('hidden');
    $('#' + $(this).attr('name')).removeClass('hidden');
})

})