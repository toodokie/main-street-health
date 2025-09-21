/**
 * Fix testimonial carousel 3 height adjustment
 * Forces container to adjust to current slide height instead of tallest slide
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find all testimonial carousel 3 instances
    const carousels = document.querySelectorAll('.pxl-testimonial-carousel3 .pxl-swiper-container');
    
    carousels.forEach(function(carousel) {
        const swiper = carousel.swiper;
        
        if (swiper) {
            // Function to adjust container height
            function adjustHeight() {
                const activeSlide = carousel.querySelector('.swiper-slide-active .pxl-item--inner');
                if (activeSlide) {
                    const slideHeight = activeSlide.offsetHeight;
                    const wrapper = carousel.querySelector('.pxl-swiper-wrapper');
                    const container = carousel;
                    
                    // Set container height to active slide height + padding
                    const totalHeight = slideHeight + 40; // 20px top + 20px bottom padding
                    container.style.height = totalHeight + 'px';
                    wrapper.style.height = totalHeight + 'px';
                }
            }
            
            // Adjust height on slide change
            swiper.on('slideChange', adjustHeight);
            swiper.on('resize', adjustHeight);
            
            // Initial adjustment
            setTimeout(adjustHeight, 100);
        }
    });
});