/**
 * Main Street Health - Main JavaScript
 * Healthcare-focused theme scripts with accessibility focus
 */

class MainStreetHealth {
    constructor() {
        this.init();
    }
    
    init() {
        this.initNavigation();
        this.initSecondaryNav();
        this.initAccessibility();
        this.initPatientPortal();
        this.initScrollDetection();
    }
    
    // Initialize navigation functionality
    initNavigation() {
        const navToggle = document.querySelector('.nav-toggle');
        const mobileMenuContent = document.querySelector('.mobile-menu-content');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        
        const closeMobileMenu = () => {
            mobileMenuContent.classList.remove('active');
            navToggle.classList.remove('active');
            navToggle.setAttribute('aria-expanded', 'false');
        };
        
        if (navToggle && mobileMenuContent) {
            navToggle.addEventListener('click', (e) => {
                e.preventDefault();
                mobileMenuContent.classList.toggle('active');
                navToggle.classList.toggle('active');
                navToggle.setAttribute('aria-expanded', 
                    navToggle.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'
                );
            });
            
            // Mobile menu close button
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', (e) => {
                    e.preventDefault();
                    closeMobileMenu();
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!navToggle.contains(e.target) && !mobileMenuContent.contains(e.target)) {
                    closeMobileMenu();
                }
            });
            
            // Mobile dropdown toggles
            const mobileDropdownTriggers = document.querySelectorAll('[data-mobile-dropdown]');
            mobileDropdownTriggers.forEach(trigger => {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    const dropdownId = trigger.getAttribute('data-mobile-dropdown');
                    const dropdown = document.getElementById(`${dropdownId}-mobile-dropdown`);
                    
                    if (dropdown) {
                        dropdown.classList.toggle('active');
                        trigger.classList.toggle('active');
                    }
                });
            });
        }
        
        // Navigation mode switcher
        const navSwitcher = document.querySelector('.nav-mode-switcher');
        if (navSwitcher) {
            navSwitcher.addEventListener('change', (e) => {
                this.switchNavigationMode(e.target.value);
            });
        }
        
        // Dropdown menus
        this.initDropdownMenus();
    }
    
    // Initialize secondary navigation
    initSecondaryNav() {
        const dropdownTriggers = document.querySelectorAll('.secondary-nav-menu a[data-dropdown]');
        
        dropdownTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                
                const dropdownId = trigger.getAttribute('data-dropdown');
                const dropdown = document.getElementById(`${dropdownId}-dropdown`);
                const parentLi = trigger.parentElement;
                
                // Close all other dropdowns
                dropdownTriggers.forEach(otherTrigger => {
                    if (otherTrigger !== trigger) {
                        const otherDropdownId = otherTrigger.getAttribute('data-dropdown');
                        const otherDropdown = document.getElementById(`${otherDropdownId}-dropdown`);
                        const otherParentLi = otherTrigger.parentElement;
                        
                        if (otherDropdown) {
                            otherDropdown.classList.remove('active');
                        }
                        if (otherParentLi) {
                            otherParentLi.classList.remove('menu-item-open');
                        }
                    }
                });
                
                // Toggle current dropdown
                if (dropdown) {
                    const isOpening = !dropdown.classList.contains('active');
                    dropdown.classList.toggle('active');
                    parentLi.classList.toggle('menu-item-open');
                    
                    // Remove focus from trigger when closing to prevent yellow state
                    if (!isOpening) {
                        trigger.blur();
                    }
                }
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.secondary-nav')) {
                dropdownTriggers.forEach(trigger => {
                    const dropdownId = trigger.getAttribute('data-dropdown');
                    const dropdown = document.getElementById(`${dropdownId}-dropdown`);
                    const parentLi = trigger.parentElement;
                    
                    if (dropdown) {
                        dropdown.classList.remove('active');
                    }
                    if (parentLi) {
                        parentLi.classList.remove('menu-item-open');
                    }
                });
            }
        });
    }
    
    // Initialize dropdown menus with accessibility
    initDropdownMenus() {
        const dropdownItems = document.querySelectorAll('.menu-item-has-children');
        
        dropdownItems.forEach(item => {
            const link = item.querySelector('a');
            const submenu = item.querySelector('.sub-menu');
            
            if (link && submenu) {
                link.setAttribute('aria-haspopup', 'true');
                link.setAttribute('aria-expanded', 'false');
                
                // Keyboard navigation
                link.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.toggleDropdown(item);
                    }
                });
                
                // Mouse events
                item.addEventListener('mouseenter', () => this.showDropdown(item));
                item.addEventListener('mouseleave', () => this.hideDropdown(item));
            }
        });
    }
    
    toggleDropdown(item) {
        const link = item.querySelector('a');
        const submenu = item.querySelector('.sub-menu');
        const isExpanded = link.getAttribute('aria-expanded') === 'true';
        
        link.setAttribute('aria-expanded', !isExpanded);
        item.classList.toggle('dropdown-open');
    }
    
    showDropdown(item) {
        const link = item.querySelector('a');
        link.setAttribute('aria-expanded', 'true');
        item.classList.add('dropdown-open');
    }
    
    hideDropdown(item) {
        const link = item.querySelector('a');
        link.setAttribute('aria-expanded', 'false');
        item.classList.remove('dropdown-open');
    }
    
    // Switch navigation mode (Patient/Provider)
    switchNavigationMode(mode) {
        if (typeof msh_ajax === 'undefined') {
            console.warn('AJAX object not found for navigation switch');
            return;
        }
        
        const data = new FormData();
        data.append('action', 'switch_navigation_mode');
        data.append('mode', mode);
        data.append('nonce', msh_ajax.nonce);
        
        fetch(msh_ajax.ajaxurl, {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Navigation switch error:', error));
    }
    
    // Initialize accessibility features
    initAccessibility() {
        // Skip links
        const skipLinks = document.querySelectorAll('.skip-link');
        skipLinks.forEach(link => {
            link.addEventListener('focus', function() {
                this.style.left = '6px';
            });
            link.addEventListener('blur', function() {
                this.style.left = '-9999px';
            });
        });
        
        // Focus management for modals
        this.initModalFocus();
        
        // High contrast mode
        this.initHighContrastMode();
        
        // Keyboard navigation for dropdowns
        this.initKeyboardNavigation();
    }
    
    // Initialize modal focus management
    initModalFocus() {
        const modals = document.querySelectorAll('.modal, .overlay');
        
        modals.forEach(modal => {
            modal.addEventListener('show', () => {
                this.trapFocus(modal);
            });
            
            modal.addEventListener('hide', () => {
                this.restoreFocus();
            });
        });
    }
    
    // Trap focus within modal
    trapFocus(element) {
        const focusableElements = element.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        element.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
            
            if (e.key === 'Escape') {
                this.closeModal(element);
            }
        });
        
        firstElement.focus();
    }
    
    // Restore focus after modal closes
    restoreFocus() {
        if (this.lastFocusedElement) {
            this.lastFocusedElement.focus();
        }
    }
    
    // Close modal
    closeModal(modal) {
        modal.classList.remove('active', 'open');
        modal.setAttribute('aria-hidden', 'true');
        this.restoreFocus();
    }
    
    // Initialize keyboard navigation
    initKeyboardNavigation() {
        // Escape key closes all dropdowns
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                // Close secondary nav dropdowns
                const openDropdowns = document.querySelectorAll('.dropdown-menu.active');
                openDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                    const trigger = document.querySelector(`[data-dropdown="${dropdown.id.replace('-dropdown', '')}"]`);
                    if (trigger) {
                        trigger.parentElement.classList.remove('menu-item-open');
                        trigger.blur();
                    }
                });
                
                // Close mobile menu
                const mobileMenuContent = document.querySelector('.mobile-menu-content');
                const navToggle = document.querySelector('.nav-toggle');
                if (mobileMenuContent && mobileMenuContent.classList.contains('active')) {
                    mobileMenuContent.classList.remove('active');
                    navToggle.classList.remove('active');
                    navToggle.setAttribute('aria-expanded', 'false');
                    navToggle.focus();
                }
            }
        });
        
        // Arrow key navigation for menu items
        const menuItems = document.querySelectorAll('.secondary-nav-menu > li > a');
        menuItems.forEach((item, index) => {
            item.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextIndex = (index + 1) % menuItems.length;
                    menuItems[nextIndex].focus();
                } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevIndex = (index - 1 + menuItems.length) % menuItems.length;
                    menuItems[prevIndex].focus();
                }
            });
        });
    }
    
    // High contrast mode toggle
    initHighContrastMode() {
        const contrastToggle = document.querySelector('.high-contrast-toggle');
        const fontSizeToggle = document.querySelector('.font-size-toggle');
        
        if (contrastToggle) {
            contrastToggle.addEventListener('click', () => {
                document.body.classList.toggle('high-contrast');
                contrastToggle.classList.toggle('active');
                localStorage.setItem('highContrast', 
                    document.body.classList.contains('high-contrast')
                );
            });
            
            // Restore high contrast setting
            if (localStorage.getItem('highContrast') === 'true') {
                document.body.classList.add('high-contrast');
                contrastToggle.classList.add('active');
            }
        }
        
        if (fontSizeToggle) {
            fontSizeToggle.addEventListener('click', () => {
                document.body.classList.toggle('large-text');
                fontSizeToggle.classList.toggle('active');
                localStorage.setItem('largeText', 
                    document.body.classList.contains('large-text')
                );
            });
            
            // Restore large text setting
            if (localStorage.getItem('largeText') === 'true') {
                document.body.classList.add('large-text');
                fontSizeToggle.classList.add('active');
            }
        }
    }
    
    // Initialize patient portal features
    initPatientPortal() {
        // Patient portal specific functionality
        const patientForms = document.querySelectorAll('.patient-form');
        patientForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                this.handlePatientFormSubmit(e, form);
            });
        });
        
        // Secure form handling
        this.initSecureFormHandling();
    }
    
    // Handle patient form submission
    handlePatientFormSubmit(e, form) {
        e.preventDefault();
        
        // Basic validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                this.showFieldError(field, 'This field is required');
            } else {
                field.classList.remove('error');
                this.clearFieldError(field);
            }
        });
        
        if (isValid) {
            this.submitPatientForm(form);
        }
    }
    
    // Submit patient form securely
    submitPatientForm(form) {
        const formData = new FormData(form);
        formData.append('action', 'submit_patient_form');
        formData.append('nonce', msh_ajax.nonce);
        
        fetch(msh_ajax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showSuccessMessage('Form submitted successfully');
                form.reset();
            } else {
                this.showErrorMessage(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            this.showErrorMessage('An error occurred while submitting the form');
        });
    }
    
    // Initialize secure form handling
    initSecureFormHandling() {
        // Disable autocomplete for sensitive fields
        const sensitiveFields = document.querySelectorAll('[data-sensitive]');
        sensitiveFields.forEach(field => {
            field.setAttribute('autocomplete', 'off');
            field.setAttribute('spellcheck', 'false');
        });
        
        // Clear forms on page unload for security
        window.addEventListener('beforeunload', () => {
            const forms = document.querySelectorAll('.patient-form');
            forms.forEach(form => form.reset());
        });
    }
    
    // Show field error
    showFieldError(field, message) {
        let errorElement = field.parentNode.querySelector('.field-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
        errorElement.setAttribute('role', 'alert');
    }
    
    // Clear field error
    clearFieldError(field) {
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    // Show success message
    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }
    
    // Show error message
    showErrorMessage(message) {
        this.showMessage(message, 'error');
    }
    
    // Show message
    showMessage(message, type) {
        const messageElement = document.createElement('div');
        messageElement.className = `message message-${type}`;
        messageElement.textContent = message;
        messageElement.setAttribute('role', 'alert');
        
        document.body.appendChild(messageElement);
        
        setTimeout(() => {
            messageElement.remove();
        }, 5000);
    }
    
    
    // Utility function to debounce events
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize scroll detection for accessibility toolbar
    initScrollDetection() {
        const accessibilityToolbar = document.querySelector('.accessibility-toolbar');
        
        if (accessibilityToolbar) {
            let scrollTimeout;
            
            const handleScroll = () => {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    const scrollY = window.scrollY || document.documentElement.scrollTop;
                    
                    if (scrollY > 200) {
                        accessibilityToolbar.classList.add('scrolled');
                    } else {
                        accessibilityToolbar.classList.remove('scrolled');
                    }
                }, 10);
            };
            
            window.addEventListener('scroll', handleScroll, { passive: true });
            
            // Check initial scroll position
            handleScroll();
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new MainStreetHealth();
});

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MainStreetHealth;
}