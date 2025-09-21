( function( $ ) {

    function equalizeRows($scope) {
        var $items = $scope.find('.pxl-grid-item');
        if (!$items.length) return;

        // Reset before measuring
        $items.find('.pxl-post--inner').css({
            'min-height': '',
            'height': 'auto'
        });

        // Group items by visual row using their top position
        var rows = {};
        var tolerance = 20;

        $items.each(function() {
            var $it = $(this);
            var top = Math.round($it.position().top);
            var key = null;
            for (var k in rows) {
                if (Math.abs(top - parseInt(k)) <= tolerance) { key = k; break; }
            }
            if (key === null) key = top;
            (rows[key] = rows[key] || []).push($it);
        });

        // Apply equal height per row
        Object.keys(rows).forEach(function(k){
            var row = rows[k];
            if (row.length < 2) return;
            var maxH = 0;
            row.forEach(function($it){
                var h = $it.find('.pxl-post--inner').outerHeight(true);
                if (h > maxH) maxH = h;
            });
            row.forEach(function($it){
                var $inner = $it.find('.pxl-post--inner');
                $inner.css({ 'min-height': maxH + 'px', 'display':'flex', 'flex-direction':'column' });
                if ($it.hasClass('custom-box')) {
                    $inner.css({ 'justify-content':'flex-end', 'align-items':'flex-start' });
                    $inner.find('.title-box').css({ 'margin-top':'auto', 'width':'100%' });
                } else {
                    $inner.find('.pxl-post--content').css({ 'flex':'1 1 auto', 'display':'flex', 'flex-direction':'column' });
                    $inner.find('.pxl-post--readmore, .pxl-post--button, .btn--readmore').css({ 'margin-top':'auto', 'padding-top':'15px' });
                }
            });
        });
    }

    function ensureCustomBoxIsLast($scope) {
        var $inner = $scope.find('.pxl-grid-inner');
        var $custom = $inner.children('.pxl-grid-item.custom-box');
        if (!$custom.length) return false;
        var $last = $inner.children('.pxl-grid-item').last();
        if ($custom.get(0) !== $last.get(0)) {
            $inner.append($custom);
            return true;
        }
        return false;
    }

    function setupForScope($scope) {
        // Move custom box to the end once
        ensureCustomBoxIsLast($scope);

        // Equalize initially after images and potential isotope layout
        var doEqualize = function(){ setTimeout(function(){ equalizeRows($scope); }, 50); };

        // Hook into Isotope arrange complete if present
        var $inner = $scope.find('.pxl-grid-inner');
        if (typeof $inner.on === 'function') {
            $inner.on('arrangeComplete', function(){
                var hasCustom = $scope.find('.pxl-grid-item.custom-box').length > 0;
                if (hasCustom && typeof $inner.isotope === 'function' && $inner.data('isotope')) {
                    // One-time enforce: use fitRows and custom sort so custom-box is last overall
                    if (!$scope.data('mshSortedCustomLast')) {
                        try {
                            $inner.isotope({
                                layoutMode: 'fitRows',
                                getSortData: {
                                    customLast: function(itemElem){ return $(itemElem).hasClass('custom-box') ? 1 : 0; },
                                    original: function(itemElem){ return $(itemElem).index(); }
                                },
                                sortBy: ['customLast','original'],
                                sortAscending: { customLast: true, original: true },
                                fitRows: { gutter: 0 }
                            });
                            $scope.data('mshSortedCustomLast', true);
                        } catch(e) { /* no-op */ }
                    }

                    // Ensure node is at the end to match sort intent, then relayout if moved
                    var moved = ensureCustomBoxIsLast($scope);
                    if (moved) {
                        $inner.isotope('reloadItems').isotope();
                    }
                } else {
                    // No isotope attached; basic DOM move + equalize
                    ensureCustomBoxIsLast($scope);
                }
                doEqualize();
            });
        }

        // Initial run after window load/images
        doEqualize();
    }

    function forceFitRowsForServiceGrid($scope) {
        // Only apply when a custom-box exists in this grid
        var $inner = $scope.find('.pxl-grid-inner.pxl-grid-masonry');
        if (!$inner.length || !$scope.find('.pxl-grid-item.custom-box').length) return;

        // If Isotope attached, switch layout to fitRows for predictable row order
        try {
            if (typeof $inner.isotope === 'function' && $inner.data('isotope')) {
                $inner.isotope({ layoutMode: 'fitRows', sortBy: 'original-order', fitRows: { gutter: 0 } });
                // Re-append custom box after layout mode switch
                ensureCustomBoxIsLast($scope);
                $inner.isotope('reloadItems').isotope();
            }
        } catch (e) { /* no-op */ }
    }

    // Init on load and on resize
    $(window).on('load', function(){
        $('.pxl-grid.pxl-service-grid.pxl-service-grid-layout2').each(function(){
            var $scope = $(this);
            setupForScope($scope);
            // Force fitRows so the last item (custom box) sits at row end
            forceFitRowsForServiceGrid($scope);
            // Final pass to append custom last and arrange
            var moved = ensureCustomBoxIsLast($scope);
            var $inner = $scope.find('.pxl-grid-inner');
            if (moved && typeof $inner.isotope === 'function' && $inner.data('isotope')) {
                $inner.isotope('reloadItems').isotope();
            }
        });
    });

    var resizeTimer;
    $(window).on('resize orientationchange', function(){
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function(){
            $('.pxl-grid.pxl-service-grid.pxl-service-grid-layout2').each(function(){
                equalizeRows($(this));
            });
        }, 250);
    });

    // Re-run when any image inside services grid loads
    $(document).on('load', '.pxl-service-grid img', function(){
        var $grid = $(this).closest('.pxl-grid.pxl-service-grid.pxl-service-grid-layout2');
        if ($grid.length) equalizeRows($grid);
    });

} )( jQuery );
