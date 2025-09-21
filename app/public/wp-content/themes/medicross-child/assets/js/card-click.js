/**
 * Card Click Handler
 * Makes entire card clickable by finding the title link
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find all card containers
    const cards = document.querySelectorAll('.pxl-post--inner');
    
    cards.forEach(card => {
        // Find the title link within this card
        const titleLink = card.querySelector('.pxl-post--title a');
        
        if (titleLink) {
            // Add click handler to the card
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on a button or link directly
                if (e.target.closest('.pxl-post--readmore, .pxl-post--button, a')) {
                    return;
                }
                
                // Navigate to the title link URL
                window.location.href = titleLink.href;
            });
            
            // Optional: Add visual feedback on hover
            card.addEventListener('mouseenter', function() {
                card.style.cursor = 'pointer';
            });
        }
    });
    
    // Convert uploaded SVG images to inline SVG for color control
    function convertSVGImages() {
        const svgImages = document.querySelectorAll('.msh-service-image img[src$=".svg"]');
        
        svgImages.forEach(img => {
            const imgSrc = img.getAttribute('src');
            if (!imgSrc) return;
            
            // Fetch the SVG content
            fetch(imgSrc)
                .then(response => response.text())
                .then(svgContent => {
                    // Create a temporary div to parse the SVG
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = svgContent;
                    const svg = tempDiv.querySelector('svg');
                    
                    if (svg) {
                        // Copy attributes from img to svg
                        svg.style.width = '100%';
                        svg.style.height = '100%';
                        svg.style.maxWidth = '100%';
                        svg.style.maxHeight = '100%';
                        svg.style.objectFit = 'contain';
                        svg.style.fill = 'currentColor';
                        
                        // Remove hardcoded colors and apply currentColor
                        cleanSVGColors(svg);
                        
                        // Replace img with inline SVG
                        img.parentNode.replaceChild(svg, img);
                    }
                })
                .catch(error => {
                    console.log('Could not convert SVG image:', error);
                });
        });
    }

    // Aggressive SVG color fix - remove all original colors and force inherit
    function fixUploadedSVGColors() {
        const imageSVGs = document.querySelectorAll('.msh-service-image svg');
        
        imageSVGs.forEach(svg => {
            console.log('Processing uploaded SVG:', svg);
            
            // AGGRESSIVELY remove ALL color attributes and styles
            svg.removeAttribute('fill');
            svg.removeAttribute('stroke');
            svg.removeAttribute('color');
            svg.style.fill = '';
            svg.style.stroke = '';
            svg.style.color = '';
            
            const svgElements = svg.querySelectorAll('*');
            svgElements.forEach(element => {
                // Remove all possible color attributes
                element.removeAttribute('fill');
                element.removeAttribute('stroke');
                element.removeAttribute('color');
                
                // Clear all inline styles that might set colors
                element.style.fill = '';
                element.style.stroke = '';
                element.style.color = '';
                
                // Remove any CSS classes that might set colors
                if (element.classList.length > 0) {
                    console.log('SVG element has classes:', Array.from(element.classList));
                }
            });
            
            // Now force currentColor on everything
            svg.style.setProperty('fill', 'currentColor', 'important');
            svgElements.forEach(element => {
                element.style.setProperty('fill', 'currentColor', 'important');
            });
            
            console.log('Forced currentColor on uploaded SVG');
        });
    }

    // Simple initialization
    function initializeSVGFixes() {
        convertSVGImages();
        setTimeout(fixUploadedSVGColors, 100);
    }
    
    // Run on load and DOM changes
    initializeSVGFixes();
    setTimeout(initializeSVGFixes, 1000);
    
    const observer = new MutationObserver(() => {
        setTimeout(fixUploadedSVGColors, 100);
    });
    observer.observe(document.body, { childList: true, subtree: true });
});