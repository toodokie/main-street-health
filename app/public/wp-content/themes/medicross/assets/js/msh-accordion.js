/**
 * MSH Accordion Widget JavaScript
 * Handles accordion expand/collapse functionality with animations
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMSHAccordions);
    } else {
        initMSHAccordions();
    }
    
    function initMSHAccordions() {
        const accordions = document.querySelectorAll('.pxl-msh-accordion');
        
        accordions.forEach(accordion => {
            new MSHAccordion(accordion);
        });
    }
    
    class MSHAccordion {
        constructor(element) {
            this.accordion = element;
            this.items = element.querySelectorAll('.pxl-accordion-item');
            this.headers = element.querySelectorAll('.pxl-accordion-header');
            this.transitioning = new WeakMap();
            
            // Get settings from data attributes
            this.animationSpeed = parseInt(this.accordion.dataset.animationSpeed) || 300;
            const closeOthersValue = String(this.accordion.dataset.closeOthers || 'yes');
            this.closeOthers = (closeOthersValue === 'yes' || closeOthersValue === 'true');
            const firstOpenValue = String(this.accordion.dataset.firstOpen || 'no');
            this.firstOpen = (firstOpenValue === 'yes' || firstOpenValue === 'true');
            this.startState = String(this.accordion.dataset.startState || '');
            const scrollIntoViewValue = String(this.accordion.dataset.scrollIntoView || '');
            this.scrollIntoView = (scrollIntoViewValue === 'yes' || scrollIntoViewValue === 'true');
            this.scrollOffset = parseInt(this.accordion.dataset.scrollOffset) || 0;
            
            
            // Expose instance for public API helpers
            this.accordion.medicalAccordion = this;

            this.init();
        }
        
        init() {
            // Add event listeners
            this.headers.forEach((header, index) => {
                header.addEventListener('click', (e) => this.handleClick(e, index));
                header.addEventListener('keydown', (e) => this.handleKeydown(e, index));
            });
            
            // Handle hash linking
            this.handleHashLinking();
            
            // Initialize open state by startState / legacy firstOpen
            if (this.startState === 'first') {
                this.openItem(0, false);
            } else if (this.firstOpen) {
                this.openItem(0, false);
            }
            
            // Dispatch ready event
            this.accordion.dispatchEvent(new CustomEvent('pxl:accordion:ready', {
                detail: { accordion: this }
            }));
        }
        
        handleClick(event, index) {
            event.preventDefault();
            this.toggleItem(index);
        }
        
        handleKeydown(event, index) {
            switch (event.key) {
                case 'Enter':
                case ' ':
                    event.preventDefault();
                    this.toggleItem(index);
                    break;
                case 'ArrowDown':
                    event.preventDefault();
                    this.focusNext(index);
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.focusPrevious(index);
                    break;
                case 'Home':
                    event.preventDefault();
                    this.focusFirst();
                    break;
                case 'End':
                    event.preventDefault();
                    this.focusLast();
                    break;
            }
        }
        
        toggleItem(index) {
            const item = this.items[index];
            const isActive = item.classList.contains('active');
            
            if (isActive) {
                this.closeItem(index);
            } else {
                if (this.closeOthers) {
                    this.closeAllItems();
                }
                this.openItem(index);
            }
        }
        
        openItem(index, animate = true) {
            const item = this.items[index];
            const header = this.headers[index];
            const content = item.querySelector('.pxl-accordion-content');
            
            if (item.classList.contains('active')) {
                return;
            }
            // Prevent conflicts if this panel is mid-transition
            if (this.transitioning.get(content)) return;
            
            // Update classes and ARIA attributes
            item.classList.remove('collapsed');
            item.classList.add('active');
            header.setAttribute('aria-expanded', 'true');
            
            // Animate content
            if (animate && this.animationSpeed > 0) {
                this.slideDown(content, () => {
                    if (this.scrollIntoView) this.scrollWithOffset(item);
                });
            } else {
                content.style.display = 'block';
                content.style.height = 'auto';
                content.style.overflow = 'visible';
                if (this.scrollIntoView) this.scrollWithOffset(item);
            }
            
            // Dispatch event
            this.accordion.dispatchEvent(new CustomEvent('pxl:accordion:opened', {
                detail: { 
                    accordion: this, 
                    item: item, 
                    index: index 
                }
            }));
        }
        
        closeItem(index) {
            const item = this.items[index];
            const header = this.headers[index];
            const content = item.querySelector('.pxl-accordion-content');
            
            if (!item.classList.contains('active')) {
                return;
            }
            // Prevent conflicts if this panel is mid-transition
            if (this.transitioning.get(content)) return;
            
            // Update classes and ARIA attributes
            item.classList.remove('active');
            item.classList.add('collapsed');
            header.setAttribute('aria-expanded', 'false');
            
            // Animate content
            this.slideUp(content);
            
            // Dispatch event
            this.accordion.dispatchEvent(new CustomEvent('pxl:accordion:closed', {
                detail: { 
                    accordion: this, 
                    item: item, 
                    index: index 
                }
            }));
        }
        
        closeAllItems() {
            this.items.forEach((item, index) => {
                if (item.classList.contains('active')) {
                    this.closeItem(index);
                }
            });
        }
        
        slideDown(element, callback) {
            this.transitioning.set(element, true);
            
            // Reset styles and get target height
            element.style.display = 'block';
            element.style.height = 'auto';
            element.style.opacity = '1';
            element.style.overflow = 'hidden';
            element.style.boxSizing = 'border-box';
            
            const height = element.scrollHeight;
            element.style.height = '0px';
            element.style.opacity = '0';
            
            // Use requestAnimationFrame for smoother animation
            requestAnimationFrame(() => {
                // Set transition
                element.style.transition = `height ${this.animationSpeed}ms cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                                           opacity ${this.animationSpeed}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)`;
                
                // Trigger the animation
                requestAnimationFrame(() => {
                    element.style.height = height + 'px';
                    element.style.opacity = '1';
                });
            });
            
            setTimeout(() => {
                element.style.height = 'auto';
                element.style.overflow = 'visible';
                element.style.transition = '';
                element.style.boxSizing = '';
                this.transitioning.delete(element);
                if (typeof callback === 'function') callback();
            }, this.animationSpeed + 50);
        }
        
        slideUp(element) {
            this.transitioning.set(element, true);
            
            // Get current height and set it explicitly
            const height = element.scrollHeight;
            element.style.height = height + 'px';
            element.style.overflow = 'hidden';
            element.style.boxSizing = 'border-box';
            
            // Use requestAnimationFrame for smoother animation
            requestAnimationFrame(() => {
                // Set transition after height is set
                element.style.transition = `height ${this.animationSpeed}ms cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                                           opacity ${this.animationSpeed}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)`;
                
                // Trigger the animation
                requestAnimationFrame(() => {
                    element.style.height = '0px';
                    element.style.opacity = '0';
                });
            });
            
            setTimeout(() => {
                element.style.display = 'none';
                element.style.height = '';
                element.style.overflow = '';
                element.style.transition = '';
                element.style.opacity = '';
                element.style.boxSizing = '';
                this.transitioning.delete(element);
            }, this.animationSpeed + 50);
        }

        scrollWithOffset(target) {
            try {
                const rect = target.getBoundingClientRect();
                const targetY = rect.top + window.pageYOffset - this.scrollOffset;
                window.scrollTo({ top: targetY < 0 ? 0 : targetY, behavior: 'smooth' });
            } catch (e) {}
        }
        
        focusNext(currentIndex) {
            const nextIndex = (currentIndex + 1) % this.headers.length;
            this.headers[nextIndex].focus();
        }
        
        focusPrevious(currentIndex) {
            const prevIndex = currentIndex === 0 ? this.headers.length - 1 : currentIndex - 1;
            this.headers[prevIndex].focus();
        }
        
        focusFirst() {
            this.headers[0].focus();
        }
        
        focusLast() {
            this.headers[this.headers.length - 1].focus();
        }
        
        handleHashLinking() {
            // Check if URL has hash that matches an accordion item
            const hash = window.location.hash.substring(1);
            if (!hash) return;
            
            this.items.forEach((item, index) => {
                const itemId = item.dataset.itemId;
                if (itemId === hash) {
                    // Close others if setting is enabled
                    if (this.closeOthers) {
                        this.closeAllItems();
                    }
                    
                    // Open the target item
                    setTimeout(() => {
                        this.openItem(index);
                        if (this.scrollIntoView) {
                            this.scrollWithOffset(item);
                        } else {
                            item.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }, 100);
                }
            });
        }
    }
    
    // Public API
    window.MSHAccordion = MSHAccordion;
    
    // Utility functions for external use
    window.pxlMSHAccordion = {
        openItem: function(accordionId, itemIndex) {
            const accordion = document.getElementById(accordionId);
            if (accordion && accordion.medicalAccordion) {
                accordion.medicalAccordion.openItem(itemIndex);
            }
        },
        
        closeItem: function(accordionId, itemIndex) {
            const accordion = document.getElementById(accordionId);
            if (accordion && accordion.medicalAccordion) {
                accordion.medicalAccordion.closeItem(itemIndex);
            }
        },
        
        closeAll: function(accordionId) {
            const accordion = document.getElementById(accordionId);
            if (accordion && accordion.medicalAccordion) {
                accordion.medicalAccordion.closeAllItems();
            }
        }
    };
    
})();
