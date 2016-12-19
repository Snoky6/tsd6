/*
 Stellar by HTML5 UP
 html5up.net | @ajlkn
 Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
 */

(function ($) {

    skel.breakpoints({
        xlarge: '(max-width: 1680px)',
        large: '(max-width: 1280px)',
        medium: '(max-width: 980px)',
        small: '(max-width: 736px)',
        xsmall: '(max-width: 480px)',
        xxsmall: '(max-width: 360px)'
    });

    $(function () {

        var $window = $(window),
                $body = $('body'),
                $main = $('#main');

        // Disable animations/transitions until the page has loaded.
        $body.addClass('is-loading');

        $window.on('load', function () {
            window.setTimeout(function () {
                $body.removeClass('is-loading');
            }, 100);
        });

        // Fix: Placeholder polyfill.
        $('form').placeholder();

        // Prioritize "important" elements on medium.
        skel.on('+medium -medium', function () {
            $.prioritize(
                    '.important\\28 medium\\29',
                    skel.breakpoint('medium').active
                    );
        });

        // Nav.
        var $nav = $('#nav');

        if ($nav.length > 0) {

            // Shrink effect.
            $main
                    .scrollex({
                        mode: 'top',
                        enter: function () {
                            $nav.addClass('alt');
                        },
                        leave: function () {
                            $nav.removeClass('alt');
                        },
                    });

        }

        // Scrolly.
        $('.scrolly').scrolly({
            speed: 1000
        });

    });

})(jQuery);