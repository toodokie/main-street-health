/**
 * MSH Mixed Post Carousel Initialization
 * Register our widget with the theme's carousel handler
 */

(function($) {
    'use strict';
    
    // Wait for theme's carousel handler to be available
    $(window).on('elementor/frontend/init', function() {
        if (window.elementorFrontend && typeof pxl_swiper_handler === 'function') {
            // Register our widget with the theme's carousel system
            elementorFrontend.hooks.addAction('frontend/element_ready/msh_mixed_post_carousel.default', function($scope) {
                // Use the theme's carousel handler but override spaceBetween
                var originalHandler = pxl_swiper_handler;
                
                // Modify the settings before initializing
                $scope.find('.pxl-swiper-container').each(function() {
                    var $container = $(this);
                    var settings = $container.data('settings');
                    
                    if (settings) {
                        // Force spaceBetween to use our gutter setting
                        settings.slides_gutter = settings.slides_gutter || 20;
                        $container.data('settings', settings);
                    }
                });
                
                // Call the theme's carousel handler
                originalHandler($scope);
                
                console.log('Mixed carousel initialized via theme handler');
            });
        }
    });
    
})(jQuery);