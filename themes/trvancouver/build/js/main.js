(function ($) {
    
        $('.cross').hide();
    
        var $crossIcon = $('.cross');
        var $hamburgerIcon = $('.hamburger');
        var $mobileMenu = $('.mobile-menu');
        
    
        $hamburgerIcon.click(function () {
            
                $hamburgerIcon.addClass('transparent').hide();
                $mobileMenu.css({'width': '60vw', 'height': 'auto'}).addClass('opaque');
                $crossIcon.addClass('opaque').show();
        });
    
        $crossIcon.click(function () {
            
                $crossIcon.removeClass('opaque').hide();
                $hamburgerIcon.removeClass('transparent').show();
                $mobileMenu.removeClass('opaque').css('width', '0');
        });

    })(jQuery);