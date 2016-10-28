/**
 * Created by Aux on 28/10/2016.
 */
$(function () {
    $('.case').click(function () {
        var coord = $(this).attr('value').split('-');
        var x = coord[0];
        var y = coord[1];

        $('.infoBlock').addClass('hidden');
        $('#move').addClass('hidden');
        $('#attack').addClass('hidden');
        $(".selected").removeClass("selected");


        $('.infoBlock').each(function () {
            var coord = $(this).attr('value').split('-');
            var xBlock = coord[0];
            var yBlock = coord[1];

            if ((x == xBlock) && (y == yBlock)) {
                $(this).removeClass('hidden');
            }
        });
    });

    $('.adjacent').click(function(){
        $(this).addClass("selected");

        var coord = $(this).attr('value').split('-');
        var x = coord[0];
        var y = coord[1];

        $("#xSelected").attr('value',x);
        $("#ySelected").attr('value',y);


        if(!$(this).hasClass('occupied')){
            $('#move').removeClass('hidden');
        }
        else
            $('#attack').removeClass('hidden');
    })



})