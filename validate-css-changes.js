// CSS Validation Script for Cross-Browser Testing
// Run this in browser DevTools console

console.log('🧪 MSH CSS VALIDATION SCRIPT');
console.log('=============================');

// 1. Check for text decoration removal
function validateTextLinks() {
    console.log('\n📋 TEXT LINK VALIDATION');
    console.log('------------------------');
    
    const links = document.querySelectorAll('a');
    let passCount = 0;
    let totalCount = 0;
    
    links.forEach((link, index) => {
        const styles = window.getComputedStyle(link);
        const textDecoration = styles.getPropertyValue('text-decoration');
        const textDecorationLine = styles.getPropertyValue('text-decoration-line');
        
        totalCount++;
        
        if (textDecoration === 'none' || textDecorationLine === 'none') {
            passCount++;
        } else {
            console.warn(`❌ Link ${index}: Has text-decoration: ${textDecoration}`);
        }
    });
    
    console.log(`✅ Text decoration check: ${passCount}/${totalCount} links without underlines`);
    return passCount === totalCount;
}

// 2. Check hover color capabilities
function validateHoverColor() {
    console.log('\n🎨 HOVER COLOR VALIDATION');
    console.log('-------------------------');
    
    const targetColor = '#DBAA17'; // Yellow hover color
    const navLinks = document.querySelectorAll('.nav-menu a, .top-nav a, nav a');
    
    console.log(`🔍 Found ${navLinks.length} navigation links to test`);
    console.log(`🎯 Target hover color: ${targetColor}`);
    
    // Check CSS variables
    const rootStyles = window.getComputedStyle(document.documentElement);
    const navHoverVar = rootStyles.getPropertyValue('--nav-hover-color').trim();
    
    if (navHoverVar === targetColor) {
        console.log('✅ CSS variable --nav-hover-color is correctly set');
    } else {
        console.warn(`❌ CSS variable mismatch. Expected: ${targetColor}, Got: ${navHoverVar}`);
    }
    
    return navHoverVar === targetColor;
}

// 3. Check minified CSS loading
function validateMinifiedCSS() {
    console.log('\n📦 MINIFIED CSS VALIDATION');
    console.log('--------------------------');
    
    const expectedFiles = [
        'navigation.min.css',
        'msh-services-list.min.css', 
        'button-fixes.min.css'
    ];
    
    const loadedStylesheets = Array.from(document.styleSheets);
    let loadedCount = 0;
    
    expectedFiles.forEach(file => {
        const found = loadedStylesheets.some(sheet => 
            sheet.href && sheet.href.includes(file)
        );
        
        if (found) {
            console.log(`✅ ${file} loaded successfully`);
            loadedCount++;
        } else {
            console.warn(`❌ ${file} not found or not loaded`);
        }
    });
    
    console.log(`📊 Minified CSS status: ${loadedCount}/${expectedFiles.length} files loaded`);
    return loadedCount === expectedFiles.length;
}

// 4. Check critical components
function validateCriticalComponents() {
    console.log('\n🔧 CRITICAL COMPONENTS VALIDATION');
    console.log('---------------------------------');
    
    const components = {
        'MSH Services List': '.msh-services-list, .msh-service-entry',
        'Navigation System': '.top-nav, .nav-menu',
        'Products & Devices': '.products-devices, .device-grid',
        'Service Icons': '.msh-service-icon, .service-icon'
    };
    
    let componentCount = 0;
    
    Object.entries(components).forEach(([name, selector]) => {
        const elements = document.querySelectorAll(selector);
        if (elements.length > 0) {
            console.log(`✅ ${name}: ${elements.length} elements found`);
            componentCount++;
        } else {
            console.warn(`⚠️ ${name}: No elements found (may not be on this page)`);
        }
    });
    
    return componentCount > 0;
}

// 5. Check font loading
function validateFontLoading() {
    console.log('\n🔤 FONT LOADING VALIDATION');
    console.log('--------------------------');
    
    if (document.fonts) {
        document.fonts.ready.then(() => {
            console.log('✅ All fonts loaded successfully');
            
            // Check for specific fonts
            const testFonts = ['bree', 'GT Walsheim Pro', 'Source Sans Pro'];
            testFonts.forEach(font => {
                if (document.fonts.check(`16px "${font}"`)) {
                    console.log(`✅ ${font} is available`);
                } else {
                    console.log(`⚠️ ${font} may not be loaded`);
                }
            });
        });
    } else {
        console.warn('⚠️ Font loading API not supported in this browser');
    }
}

// 6. Performance metrics
function validatePerformance() {
    console.log('\n⚡ PERFORMANCE VALIDATION');
    console.log('------------------------');
    
    if (window.performance) {
        const navigation = performance.getEntriesByType('navigation')[0];
        const loadTime = navigation.loadEventEnd - navigation.loadEventStart;
        const domContentLoaded = navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart;
        
        console.log(`📊 Page load time: ${loadTime}ms`);
        console.log(`📊 DOM content loaded: ${domContentLoaded}ms`);
        
        // Check CSS resources
        const cssResources = performance.getEntriesByType('resource').filter(
            resource => resource.name.includes('.css')
        );
        
        console.log(`📦 CSS files loaded: ${cssResources.length}`);
        cssResources.forEach(css => {
            const cssLoadTime = css.responseEnd - css.requestStart;
            console.log(`  - ${css.name.split('/').pop()}: ${cssLoadTime.toFixed(2)}ms`);
        });
    }
}

// 7. Console error check
function checkConsoleErrors() {
    console.log('\n🐛 CONSOLE ERROR CHECK');
    console.log('---------------------');
    console.log('💡 Check browser console for any CSS-related errors');
    console.log('💡 Look for 404s on CSS files or font loading issues');
}

// Main validation function
function runFullValidation() {
    console.clear();
    console.log('🚀 STARTING FULL CSS VALIDATION');
    console.log('================================');
    console.log(`🌐 Browser: ${navigator.userAgent.split(' ').pop()}`);
    console.log(`📱 Viewport: ${window.innerWidth}x${window.innerHeight}`);
    console.log(`🕐 Time: ${new Date().toLocaleString()}`);
    
    const results = {
        textLinks: validateTextLinks(),
        hoverColor: validateHoverColor(), 
        minifiedCSS: validateMinifiedCSS(),
        components: validateCriticalComponents(),
        fonts: validateFontLoading(),
        performance: validatePerformance()
    };
    
    checkConsoleErrors();
    
    console.log('\n📋 VALIDATION SUMMARY');
    console.log('====================');
    
    const passedTests = Object.values(results).filter(Boolean).length;
    const totalTests = Object.keys(results).length - 1; // Exclude performance (async)
    
    console.log(`✅ Tests passed: ${passedTests}/${totalTests}`);
    
    if (passedTests === totalTests) {
        console.log('🎉 ALL TESTS PASSED - CSS changes validated successfully!');
    } else {
        console.warn('⚠️ Some tests failed - review issues above');
    }
    
    return results;
}

// Auto-run validation
console.log('💡 Run runFullValidation() to test all CSS changes');
console.log('💡 Individual functions available:');
console.log('   - validateTextLinks()');
console.log('   - validateHoverColor()');
console.log('   - validateMinifiedCSS()');
console.log('   - validateCriticalComponents()');
console.log('   - validateFontLoading()');
console.log('   - validatePerformance()');

// Make functions available globally
window.MSHValidation = {
    runFullValidation,
    validateTextLinks,
    validateHoverColor,
    validateMinifiedCSS,
    validateCriticalComponents,
    validateFontLoading,
    validatePerformance,
    checkConsoleErrors
};