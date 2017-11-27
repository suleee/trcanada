(function ($) {
    'use strict';
    var currentSq = 0;
    var list = [];
    var mainDivSel = '.rotator';
    var $div = $(mainDivSel);
    var $ad = $('.g-col', $div);
    $ad.each(function(idx, elem) {
        var $elem = $(elem);
        $elem.addClass('sq-' + idx);
        list.push($elem);
    });
    setInterval(function(){
        idxHandler(true);
        skip();
    }, 10000);

    function skip() {
        list.forEach(function(val, idx){
            val.removeClass('show');
            if (idx === currentSq) {
                list[currentSq].addClass('show');
            }
        });
    }
    skip();

    function idxHandler(side) {
        if (side) { // +1
            currentSq++;
            if (currentSq >= list.length) {
                currentSq = 0;
            }
        } else { // -1
            currentSq--;
            if (currentSq < 0) {
                currentSq = list.length-1;
            }
        }
    }

    $('.prev', $div).on('click', function(){
        idxHandler(false);
        skip();
    })

    $('.next', $div).on('click', function(){
        idxHandler(true);
        skip();
    })

})(jQuery);