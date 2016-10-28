/**
 * Created by Aux on 28/10/2016.
 */
$(function () {
    $('.case').click(function () {
        console.log("click");
        var coord = $(this).attr('value').split('-');
        var x = coord[0];
        var y = coord[1];

        $('.infoBlock').addClass('hidden');

        $('.infoBlock').each(function () {
            var coord = $(this).attr('value').split('-');
            var xBlock = coord[0];
            var yBlock = coord[1];

            if ((x == xBlock) && (y == yBlock)) {
                $(this).removeClass('hidden');
            }
        });
    });
})