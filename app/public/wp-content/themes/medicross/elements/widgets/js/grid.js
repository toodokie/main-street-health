( function( $ ) {
    function equalizeCardHeights() {
        $('.pxl-grid-masonry').each(function() {
            var $grid = $(this);
            var $items = $grid.find('.pxl-grid-item');
            
            if ($items.length === 0) return;
            
            // Reset heights
            $items.find('.pxl-post--inner').css('height', 'auto').css('min-height', '');
            
            setTimeout(function() {
                var rows = {};
                
                // Group by row (same top position within 15px)
                $items.each(function() {
                    var top = Math.round($(this).position().top);
                    var foundRow = false;
                    
                    for (var rowTop in rows) {
                        if (Math.abs(top - parseInt(rowTop)) <= 15) {
                            rows[rowTop].push($(this));
                            foundRow = true;
                            break;
                        }
                    }
                    
                    if (!foundRow) {
                        rows[top] = [$(this)];
                    }
                });
                
                // Set equal heights per row
                for (var rowTop in rows) {
                    var $rowItems = rows[rowTop];
                    if ($rowItems.length < 2) continue;
                    
                    var maxHeight = 0;
                    $rowItems.forEach(function($item) {
                        var height = $item.find('.pxl-post--inner').outerHeight();
                        if (height > maxHeight) maxHeight = height;
                    });
                    
                    $rowItems.forEach(function($item) {
                        var $inner = $item.find('.pxl-post--inner');
                        $inner.css({
                            'min-height': maxHeight + 'px',
                            'display': 'flex',
                            'flex-direction': 'column'
                        });
                        
                        if ($item.hasClass('custom-box')) {
                            $inner.css('justify-content', 'flex-end');
                        } else {
                            $inner.find('.pxl-post--content').css('flex-grow', '1');
                            $inner.find('.pxl-post--readmore, .btn--readmore').css({
                                'margin-top': 'auto',
                                'padding-top': '1rem'
                            });
                        }
                    });
                }
            }, 200);
        });
    }
    
    $( window ).on( 'elementor/frontend/init', function() {
        setTimeout(function() {
            $('.pxl-grid').each(function(index, element) { 
                var $grid_scope = $(this);
                if( $grid_scope.hasClass('pxl-post-list')){
                    var isoOptions = {};
                    var $grid_isotope = null;
                }else{
                    var $grid_masonry = $grid_scope.find('.pxl-grid-masonry');
                    var isoOptions = {
                        itemSelector: '.pxl-grid-item',
                        layoutMode: $(this).closest('.pxl-grid').attr('data-layout'),
                        fitRows: {
                            gutter: 0
                        },
                        percentPosition: true,
                        masonry: {
                            columnWidth: '.grid-sizer',
                        },
                        containerStyle: null,
                        stagger: 30,
                        sortBy : 'original-order',
                        getSortData: {
                            'original-order': function( itemElem ) {
                                return $(itemElem).index();
                            }
                        }
                    };
                    var $grid_isotope = $grid_masonry.isotope(isoOptions);


                    $grid_scope.on('click', '.pxl-grid-filter .filter-item', function(e) {

                        var $this = $(this);
                        var term_slug = $this.attr('data-filter');  
                        
                        $this.siblings('.filter-item.active').removeClass('active');
                        $this.addClass('active'); 
                        $grid_scope.find('.pxl-post--inner').removeClass('animated');

                        if( $this.closest('.pxl-grid-filter').hasClass('ajax') ){
                            var loadmore = $grid_scope.data('loadmore');
                            loadmore.term_slug = term_slug;
                            medicross_grid_ajax_handler( $this, $grid_scope, $grid_isotope, 
                                { action: 'medicross_load_more_post_grid', loadmore: loadmore, iso_options: isoOptions, handler_click: 'filter', scrolltop: 0 }
                                );
                        }else{
                            $grid_isotope.isotope({ filter: term_slug });

                        }
                    });
                }

                $grid_scope.on('input', '.grid-search-input', function() {
                    var searchQuery = $(this).val().toLowerCase();
                    
                    // If Isotope is used
                    if ($grid_isotope) {
                        $grid_isotope.isotope({
                            filter: function() {
                                var itemTitle = $(this).find('.pxl-post--title a').text().toLowerCase();
                                return itemTitle.indexOf(searchQuery) !== -1; // Check if title starts with searchQuery
                            }
                        });
                    } else {
                        // AJAX search if Isotope is not used
                        var loadmore = $grid_scope.data('loadmore') || {};
                        loadmore.search_query = searchQuery;
                        medicross_grid_ajax_handler('medicross_load_more_post_grid', { action: 'medicross_load_more_post_grid', loadmore: loadmore, iso_options: isoOptions, handler_click: 'search', scrolltop: 0 }, 'search');
                    }
                });
                
                $grid_scope.on('click', '.pxl-grid-pagination .ajax a.page-numbers', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var $this = $(this);
                    var loadmore = $grid_scope.data('loadmore');
                    var paged = $this.attr('href');
                    paged = paged.replace('#', '');
                    loadmore.paged = parseInt(paged);
                    medicross_grid_ajax_handler( $this, $grid_scope, $grid_isotope, 
                        { action: 'medicross_load_more_post_grid', loadmore: loadmore, iso_options: isoOptions, handler_click: 'pagination', scrolltop: 0 }
                        );
                    $('html,body').animate({scrollTop: $grid_scope.offset().top - 130}, 500);
                });

                $grid_scope.on('click', '.btn-grid-loadmore', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var loadmore = $grid_scope.data('loadmore');
                    loadmore.paged = parseInt($grid_scope.data('start-page')) + 1; 

                    medicross_grid_ajax_handler( $this, $grid_scope, $grid_isotope, 
                        { action: 'medicross_load_more_post_grid', loadmore: loadmore, iso_options: isoOptions, handler_click: 'loadmore', scrolltop: 0 }
                        );
                });

                $grid_scope.on('change', '.orderby', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var loadmore = $grid_scope.data('loadmore');
                    loadmore.orderby = $this.val(); 

                    medicross_grid_ajax_handler( $this, $grid_scope, $grid_isotope, 
                        { action: 'medicross_load_more_post_grid', loadmore: loadmore, iso_options: isoOptions, handler_click: 'select_orderby', scrolltop: 0 }
                        );
                });

            });
            
            // Call equalize heights after grid initialization
            setTimeout(function() {
                equalizeCardHeights();
                
                // Also recalculate when images load
                $('.pxl-grid-masonry img').on('load', function() {
                    // Debounce image load events
                    clearTimeout(window.imageLoadTimer);
                    window.imageLoadTimer = setTimeout(function() {
                        equalizeCardHeights();
                    }, 100);
                });
            }, 500);
            
            // Resize handler
            var resizeTimer;
            $(window).on('resize orientationchange', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(equalizeCardHeights, 300);
            });

function medicross_grid_ajax_handler($this, $grid_scope, $grid_isotope, args = {}){
    var settings = $.extend( true, {}, {
        action: '',
        loadmore: '',
        iso_options: {},
        handler_click: '',
        scrolltop: 0
    }, args );

    var offset_top = $grid_scope.offset().top; 

    if( settings.handler_click == 'loadmore' ){
        var loadmore_text  = $this.closest('.pxl-load-more').data('loadmore-text');
        var loading_text  = $this.closest('.pxl-load-more').data('loading-text');
        var curoffsettop = $this.offset().top;
    }

    $.ajax({
        url: main_data.ajax_url,
        type: 'POST',
        data: {
            action: settings.action,
            settings: settings.loadmore,
            handler_click: settings.handler_click
        },
        success: function( res ) {   
            if(res.status == true){  

                if( settings.handler_click == 'loadmore' ){
                    if( settings.loadmore.wg_type == 'post-list'){
                        $grid_scope.find('.pxl-list-inner').append(res.data.html)
                    }else{
                        $grid_scope.find('.pxl-grid-inner').append(res.data.html)
                    }
                }else{
                    if( settings.loadmore.wg_type == 'post-list'){
                        $grid_scope.find('.pxl-list-inner .list-item').remove();
                        $grid_scope.find('.pxl-list-inner').append(res.data.html);
                    }else{
                        $grid_scope.find('.pxl-grid-inner .pxl-grid-item').remove();
                        $grid_scope.find('.pxl-grid-inner').append(res.data.html);
                    }
                }

                if( settings.iso_options && $grid_isotope != null){
                    $grid_isotope.isotope('destroy');
                    $grid_isotope.isotope(settings.iso_options);
                    
                    // Equalize heights after new content loads
                    setTimeout(function() {
                        equalizeCardHeights();
                    }, 400);
                }


                $grid_scope.data('start-page', res.data.paged);

                if( settings.loadmore['pagination_type'] == 'loadmore'){
                    if(res.data.paged >= res.data.max){
                        $grid_scope.find('.pxl-load-more').hide();
                    }else{
                        $grid_scope.find('.pxl-load-more').show();
                    } 
                } 
                if( settings.loadmore['pagination_type'] == 'pagination'){
                    $grid_scope.find(".pxl-grid-pagination").html(res.data.pagin_html);
                }

                if( $grid_scope.find('.result-count').length > 0 ){
                    $grid_scope.find(".result-count").html(res.data.result_count);
                } 
            }      

        },
        beforeSend: function() {  
            $grid_scope.find('.pxl-grid-overlay-loading').removeClass( 'loaded' ).addClass( 'loader' );
            if( settings.handler_click == 'loadmore' ){
                $this.find('.pxl-loadmore-text').text(loading_text);
                $this.parent().addClass('loading');
            }
        },
        complete: function() {
         $('.pxl-grid .pxl-post--icon img').each(function () {
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function (data) {
                var $svg = jQuery(data).find('svg');
                if (imgID) {
                    $svg.attr('id', imgID);
                }
                if (imgClass) {
                    $svg.attr('class', imgClass + ' replaced-svg');
                }
                $svg.removeAttr('xmlns:a');
                if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                    $svg.attr('viewBox', '0 0 24 24');
                }
                $img.replaceWith($svg);
            }, 'xml');
        });
         $grid_scope.find('.pxl-grid-overlay-loading').removeClass( 'loader' ).addClass( 'loaded' ); 
         if( settings.handler_click == 'loadmore' ){
            $this.find('.pxl-loadmore-text').text(loadmore_text);
            $this.parent().removeClass('loading');
        }
        if ( settings.scrolltop ) {
            $( 'html, body' ).animate( { scrollTop: offset_top - 100 }, 0 );
        }
    }
});
}
}, 150);
});
$(document).ready(function() {
    $('.filter-item').on('click', function() {
        var activeContent = $(this).text().trim();
        $('.label-text-fillter').text(activeContent);
    });
});

} )( jQuery ); 