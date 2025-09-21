/**
 * MSH Injury Cards Carousel Widget JavaScript
 * Handles Swiper initialization for injury cards carousel
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all MSH injury card carousels
    const injuryCarousels = document.querySelectorAll('.msh-injury-carousel .swiper');
    
    injuryCarousels.forEach(function(carousel, index) {
        // Get settings from data attributes
        const container = carousel.closest('.msh-injury-carousel');
        const settings = {
            slidesPerView: parseInt(carousel.dataset.slidesPerView) || 3,
            spaceBetween: parseInt(carousel.dataset.spaceBetween) || 30,
            autoplay: carousel.dataset.autoplay === 'yes' ? {
                delay: parseInt(carousel.dataset.autoplayDelay) || 3000,
                disableOnInteraction: false,
            } : false,
            speed: parseInt(carousel.dataset.speed) || 300,
            loop: carousel.dataset.loop === 'yes',
            grabCursor: true,
            watchOverflow: true,
            // Navigation
            navigation: carousel.dataset.navigation === 'yes' ? {
                nextEl: container.querySelector('.swiper-button-next'),
                prevEl: container.querySelector('.swiper-button-prev'),
            } : false,
            // Pagination
            pagination: carousel.dataset.pagination === 'yes' ? {
                el: container.querySelector('.swiper-pagination'),
                clickable: true,
                dynamicBullets: true,
            } : false,
            // Responsive breakpoints
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: parseInt(carousel.dataset.slidesPerViewTablet) || 2,
                    spaceBetween: 25,
                },
                1024: {
                    slidesPerView: parseInt(carousel.dataset.slidesPerView) || 3,
                    spaceBetween: parseInt(carousel.dataset.spaceBetween) || 30,
                }
            }
        };
        
        // Initialize Swiper
        const swiper = new Swiper(carousel, settings);
        
        // Add custom hover effects
        swiper.on('slideChange', function() {
            const activeSlide = carousel.querySelector('.swiper-slide-active');
            if (activeSlide) {
                const card = activeSlide.querySelector('.msh-injury-card');
                if (card) {
                    card.style.transform = 'translateY(-5px) scale(1.02)';
                    setTimeout(() => {
                        card.style.transform = '';
                    }, 300);
                }
            }
        });
        
        // Pause autoplay on hover
        if (settings.autoplay) {
            container.addEventListener('mouseenter', function() {
                swiper.autoplay.stop();
            });
            
            container.addEventListener('mouseleave', function() {
                swiper.autoplay.start();
            });
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (container.matches(':hover')) {
                if (e.key === 'ArrowLeft') {
                    swiper.slidePrev();
                } else if (e.key === 'ArrowRight') {
                    swiper.slideNext();
                }
            }
        });
        
        // Resize handler
        window.addEventListener('resize', function() {
            swiper.update();
        });
        
        // Equal height cards
        function equalizeCardHeights() {
            const cards = container.querySelectorAll('.msh-injury-card');
            let maxHeight = 0;
            
            // Reset heights
            cards.forEach(card => {
                card.style.height = 'auto';
            });
            
            // Find max height
            cards.forEach(card => {
                const cardHeight = card.offsetHeight;
                if (cardHeight > maxHeight) {
                    maxHeight = cardHeight;
                }
            });
            
            // Apply max height to all cards
            cards.forEach(card => {
                card.style.height = maxHeight + 'px';
            });
        }
        
        // Equalize heights after images load
        const images = container.querySelectorAll('.card-bg');
        let imagesLoaded = 0;
        
        if (images.length > 0) {
            images.forEach(img => {
                if (img.complete) {
                    imagesLoaded++;
                } else {
                    img.addEventListener('load', function() {
                        imagesLoaded++;
                        if (imagesLoaded === images.length) {
                            setTimeout(equalizeCardHeights, 100);
                        }
                    });
                }
            });
            
            if (imagesLoaded === images.length) {
                setTimeout(equalizeCardHeights, 100);
            }
        } else {
            setTimeout(equalizeCardHeights, 100);
        }
        
        // Re-equalize on window resize
        window.addEventListener('resize', function() {
            setTimeout(equalizeCardHeights, 200);
        });
        
        // Accessibility improvements
        container.setAttribute('role', 'region');
        container.setAttribute('aria-label', 'Injury treatment services carousel');
        
        const slides = container.querySelectorAll('.swiper-slide');
        slides.forEach((slide, slideIndex) => {
            slide.setAttribute('aria-label', `Injury service ${slideIndex + 1} of ${slides.length}`);
        });
        
        // Update aria-labels on slide change
        swiper.on('slideChange', function() {
            slides.forEach((slide, slideIndex) => {
                if (slide.classList.contains('swiper-slide-active')) {
                    slide.setAttribute('aria-current', 'true');
                } else {
                    slide.removeAttribute('aria-current');
                }
            });
        });
    });
    
    // Global utility functions
    window.MSHInjuryCarousel = {
        // Refresh all carousels
        refresh: function() {
            const carousels = document.querySelectorAll('.msh-injury-carousel .swiper');
            carousels.forEach(carousel => {
                if (carousel.swiper) {
                    carousel.swiper.update();
                }
            });
        },
        
        // Get carousel instance
        getInstance: function(element) {
            const carousel = element.querySelector('.swiper');
            return carousel ? carousel.swiper : null;
        }
    };
});