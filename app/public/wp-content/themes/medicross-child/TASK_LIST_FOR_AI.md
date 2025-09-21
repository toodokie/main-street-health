# URGENT TASK LIST FOR AI: Products & Devices Elementor Controls Issues

## 🚨 CURRENT ISSUES TO FIX:

### 1. Title Tag (H1-H5) Dropdown Not Working
**Issue**: The title tag dropdown in Elementor shows H1-H6 options but doesn't actually change the HTML tag from H3.

**Current Implementation**:
- Control exists in `/inc/elementor/extend-product-grid-icons.php` lines 61-73
- Setting gets passed through layout template at line 75 & 111 in `layout-service-2.php`
- Template logic exists in `element-templates.php` lines 744-753

**Problem**: Despite correct data flow, the title tag remains `<h3>` regardless of selection.

### 2. Styling Controls Still Not Working
**Issue**: Color controls in Style tab don't affect the actual elements despite using CSS variables.

**What's Been Tried**:
- CSS variables approach (`:root` selectors)
- Direct element targeting with `{{WRAPPER}}`
- Dual selector approach (both CSS vars + direct targeting)
- Added `!important` declarations
- Fixed selector specificity issues

**Current State**: All controls exist but don't apply colors to Products & Devices elements.

---

## 📋 COMPLETE IMPLEMENTATION HISTORY:

### Phase 1: Icon Display Issues (COMPLETED ✅)
**Problem**: Product icons not showing in Case Post Grid service-2 layout
**Solution**: 
- Removed duplicate logic in `element-templates.php` lines 608-620
- Added direct icon fix for pxl_product at line 740+
- Fixed JavaScript interference with CSS (grid.js converts IMG to SVG)
- Added CSS for both IMG and SVG elements with filters

### Phase 2: Styling Controls Implementation
**Attempts Made**:

1. **Direct CSS Selectors** (FAILED)
   - Used hardcoded selectors like `{{WRAPPER}} .pxl-grid.pxl-service-grid`
   - Problem: Too specific, didn't match actual HTML structure

2. **CSS Variables Approach** (PARTIALLY WORKING)
   - Created CSS variables in `button-fixes.css` lines 188-199
   - Used `:root` selectors in Elementor controls
   - Problem: Variables set but elements don't use them

3. **Dual Selector Approach** (CURRENT STATE)
   - Uses both CSS variables AND direct element targeting
   - Example: Both `:root => '--product-title-color: {{VALUE}}'` AND `{{WRAPPER}} .pxl-grid .pxl-post--inner => 'color: {{VALUE}}'`
   - Problem: Still not working

### Phase 3: Widget Architecture Analysis
**Understanding Achieved**:
- Medicross uses template-based widgets (complex data flow)
- Settings flow: Widget → Layout Template → Element Templates
- Data passed via `$load_more` array becomes `$settings` in element-templates.php
- CSS must target specific class combinations

---

## 🔧 CURRENT FILE STRUCTURE:

### Key Files Modified:
1. **`/inc/elementor/extend-product-grid-icons.php`** - Main Elementor controls
2. **`/elements/templates/pxl_post_grid/layout-service-2.php`** - Layout template (passes settings)
3. **`/elements/element-templates.php`** - Individual item rendering
4. **`/assets/css/button-fixes.css`** - CSS variables and styling

### Controls Currently Exist:
```php
// In Style Tab:
- Title Tag (H1-H6 dropdown) 
- Title Color
- Title Hover Color  
- Content Text Color
- Read More Link Color
- Read More Hover Color
- Card Background Color
- Card Hover Background
- Button Hover Color
- Icon Hover Background
- Icon Color

// In Grid Tab:
- Icon Type, Font Icon, Icon Image
- Hide Custom Box toggle
```

---

## 🎯 DEBUGGING APPROACH FOR AI:

### Step 1: Debug Title Tag Issue
**Check these specific points**:
1. Verify `$settings['product_title_tag']` contains the selected value in element-templates.php
2. Add debug output: `echo "Title tag: " . $title_tag;` before line 751
3. Confirm the condition `$settings['post_type'] === 'pxl_product'` is true
4. Check if Elementor cache needs clearing

### Step 2: Debug Styling Controls
**Investigation needed**:
1. **HTML Structure**: Inspect actual rendered HTML classes vs CSS selectors
2. **CSS Specificity**: Check if other styles override with higher specificity
3. **Variable Application**: Verify CSS variables are actually set in browser developer tools
4. **Selector Matching**: Confirm `{{WRAPPER}}` generates correct element ID

**Potential Issues**:
- CSS selectors don't match actual HTML structure  
- Missing `[data-post-type="pxl_product"]` attribute on wrapper
- Parent theme styles overriding with higher specificity
- Elementor not generating CSS properly due to conditions mismatch

### Step 3: Verification Method
**Test these scenarios**:
1. Create test page with Products & Devices in Case Post Grid
2. Select pxl_product post type, service-2 layout  
3. Change title tag from H3 to H1 - check if HTML changes
4. Change title color - check if color applies
5. Inspect CSS in browser developer tools to see what's actually generated

---

## 💡 SUGGESTED SOLUTIONS TO TRY:

### For Title Tag:
1. Add debug output to confirm setting value
2. Try simpler condition check
3. Clear Elementor cache after changes
4. Verify widget settings are saved properly

### For Styling Controls:
1. **Inspect actual HTML structure** and match selectors exactly
2. **Use browser developer tools** to see what CSS is generated
3. **Try inline CSS approach** if selectors fail
4. **Check parent theme CSS** for conflicting styles
5. **Consider using `body` prefix** like original theme does

### Alternative Approach:
If current approach fails, consider creating a **custom widget** (like MSH Services Grid) instead of extending the existing one, as it would allow direct control over HTML output and CSS generation.

---

## 📁 REFERENCE FILES:

- **Widget Architecture**: `/WIDGET_ARCHITECTURE.md` - Complete documentation
- **Working Example**: `/inc/elementor/msh-services-grid.php` - Custom widget that works
- **Current Extension**: `/inc/elementor/extend-product-grid-icons.php` - The problematic extension
- **CSS Variables**: `/assets/css/button-fixes.css` lines 188-199

---

## ⚠️ IMPORTANT NOTES:

1. **User Feedback**: "nothing is following the editor selections" - NO controls work
2. **Two Hover States**: Card hover (background) + individual element hover (text/links)
3. **Theme Complexity**: Medicross uses complex template system, not standard Elementor widgets
4. **Previous Success**: Icon display was fixed, proving the approach CAN work
5. **CSS Variables**: Already defined and structured correctly, just not applying

**PRIORITY**: Fix the styling controls first (title color, etc.), then address title tag functionality.

**SUCCESS CRITERIA**: 
- Changing colors in Style tab actually changes Products & Devices colors
- H1-H6 dropdown actually changes HTML tag from `<h3>` to selected tag