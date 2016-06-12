/**
 * Created by Saniac on 2016/6/12.
 */

var currentSelect = 'geo';

$(document).ready(function () {

    $('#geoSelector').css("display", "block");
    $('#geo').css("display", "block");

    $('.navList>li').bind("click", function () {
        $(".active").removeClass("active");
        $(this).addClass("active");
        $('#' + currentSelect ).css("display", "none");
        $('#' + currentSelect + 'Selector').css("display", "none");
        currentSelect = $(this).attr('id').slice(0,3);
        $('#' + currentSelect ).css("display", "block");
        $('#' + currentSelect + 'Selector').css("display", "block");



    });

}
);