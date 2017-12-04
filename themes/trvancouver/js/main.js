(function ($) {
    $('.menu li').hover(function() {
        $(this).find('li').append('<div class="hover-box"></div>');
        $('.hover-box').css("width","10px","height","5px","color", "red");
    });
})(jQuery);