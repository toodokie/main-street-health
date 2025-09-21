(function(){
    function formatNumber(value, decimals, delimiter) {
        if (isNaN(value)) value = 0;
        if (delimiter === undefined) {
            return value.toLocaleString(undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }
        var fixed = value.toFixed(decimals);
        var parts = fixed.split('.');
        var intPart = parts[0];
        if (delimiter) {
            var groupSep = delimiter === ' ' ? '\u00A0' : delimiter;
            intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, groupSep);
        }
        var decPart = parts[1] ? '.' + parts[1] : '';
        return intPart + decPart;
    }

    function animateCounter(el) {
        if (el.dataset.mshAnimated) return;
        el.dataset.mshAnimated = 'true';

        var start = parseFloat(el.dataset.startnumber || el.dataset.startNumber || el.textContent) || 0;
        var endAttr = el.dataset.endnumber || el.dataset.endNumber || el.dataset.toValue;
        var end = endAttr !== undefined ? parseFloat(endAttr) : start;
        var duration = parseInt(el.dataset.duration, 10);
        duration = isNaN(duration) ? 2000 : duration;
        var delimiter = el.dataset.delimiter;
        var decimals = 0;
        if (endAttr && endAttr.indexOf('.') !== -1) {
            decimals = endAttr.split('.')[1].length;
        }

        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var current = start + (end - start) * progress;
            el.textContent = formatNumber(current, decimals, delimiter);
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = formatNumber(end, decimals, delimiter);
            }
        }

        requestAnimationFrame(step);
    }

    function initCounters(root) {
        var scope = root && root.nodeType ? root : document;
        var counters = scope.querySelectorAll('.pxl-counter--value');
        if (!counters.length) return;

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry){
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.3 });

            counters.forEach(function(counter){
                if (!counter.dataset.mshAnimated) {
                    observer.observe(counter);
                }
            });
        } else {
            counters.forEach(animateCounter);
        }
    }

    document.addEventListener('DOMContentLoaded', function(){
        initCounters(document);
    });

    if (window.elementorFrontend && window.elementorFrontend.hooks) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/pxl_counter.default', function(scope){
            initCounters(scope instanceof jQuery ? scope.get(0) : scope);
        });
    }
})();
