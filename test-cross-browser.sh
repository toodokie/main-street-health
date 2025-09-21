#!/bin/bash

# Cross-Browser Testing Script for Main Street Health
# Local by Flywheel Environment

echo "🧪 CROSS-BROWSER TESTING - MAIN STREET HEALTH"
echo "=============================================="
echo "Local URL: https://main-street-health.local"
echo "Testing Time: $(date)"
echo ""

# Check if Local site is running
echo "🔍 Checking Local site status..."
if curl -k -s https://main-street-health.local > /dev/null; then
    echo "✅ Site is accessible"
else
    echo "❌ Site is not accessible - please start Local by Flywheel"
    exit 1
fi

echo ""
echo "🚀 Opening browsers for cross-browser testing..."

# Launch browsers with the local site
echo "Opening Chrome..."
open -a "Google Chrome" "https://main-street-health.local" 2>/dev/null

sleep 2

echo "Opening Firefox..."
open -a "Firefox" "https://main-street-health.local" 2>/dev/null

sleep 2

echo "Opening Safari..."
open -a "Safari" "https://main-street-health.local" 2>/dev/null

sleep 2

echo "Opening Edge..."
open -a "Microsoft Edge" "https://main-street-health.local" 2>/dev/null

echo ""
echo "📋 TESTING CHECKLIST"
echo "==================="
echo ""
echo "Phase 1: Text Link Behavior (8 minutes)"
echo "----------------------------------------"
echo "✓ Check homepage navigation links"
echo "✓ Verify no underlines by default"
echo "✓ Test yellow hover color (#DBAA17)"
echo "✓ Validate arrow icon animations"
echo ""
echo "Phase 2: CSS Minification Impact (8 minutes)"
echo "-------------------------------------------"
echo "✓ Load homepage in each browser"
echo "✓ Check services listing page"
echo "✓ Verify contact page rendering"
echo "✓ Test mobile responsive views"
echo ""
echo "Phase 3: Critical Components (9 minutes)"
echo "---------------------------------------"
echo "✓ MSH Services List widget functionality"
echo "✓ Products & Devices section display"
echo "✓ Navigation system behavior"
echo "✓ Dropdown menus (if applicable)"
echo ""
echo "Phase 4: Performance & Technical (15 minutes)"
echo "--------------------------------------------"
echo "✓ Custom font loading (GT Walsheim Pro, Bree)"
echo "✓ Responsive breakpoint behavior"
echo "✓ Page load time measurements"
echo "✓ Console error checking"
echo ""

echo "💡 TESTING INSTRUCTIONS"
echo "======================"
echo ""
echo "In each browser, test these pages:"
echo "• Homepage: https://main-street-health.local"
echo "• Services: https://main-street-health.local/services"
echo "• Contact: https://main-street-health.local/contact"
echo ""
echo "DevTools Commands to run in each browser:"
echo "• F12 to open DevTools"
echo "• Check Network tab for CSS loading"
echo "• Run: console.log('Fonts loaded:', document.fonts.ready)"
echo "• Check for console errors"
echo ""
echo "Key Visual Elements to Validate:"
echo "• Text links have NO underlines"
echo "• Hover color is yellow (#DBAA17)"
echo "• MSH Services List displays properly"
echo "• Navigation is functional"
echo "• Fonts load correctly"
echo ""
echo "🎯 SUCCESS CRITERIA"
echo "=================="
echo "• Zero critical visual issues"
echo "• Consistent text link behavior"
echo "• All minified CSS loading"
echo "• Core components functional"
echo "• No console errors"
echo ""
echo "⏱️  Estimated testing time: 1 hour"
echo "🔺 Risk level: Low (CSS-only changes)"
echo ""
echo "Happy testing! 🎉"