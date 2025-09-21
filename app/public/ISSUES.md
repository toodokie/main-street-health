# Known Issues - Main Street Health

## MSH Injury Cards Grid Widget - Color Control Defect

**Issue**: Elementor color controls not applying user-selected colors
**Date**: 2025-09-15
**Status**: UNRESOLVED

### Problem Description:
- Added Link Color and Link Hover Color controls to MSH Injury Cards Grid widget
- Elementor controls generate correct CSS selectors with `!important`
- User-selected colors in Elementor editor are not being applied
- Widget continues to use default/fallback colors despite control settings

### Attempted Solutions:
1. ✗ CSS selectors with various specificity levels
2. ✗ Inline CSS with widget-specific element IDs  
3. ✗ JavaScript event handlers for color manipulation
4. ✗ Multiple CSS injection methods (wp_head, wp_footer)
5. ✗ Removed conflicting theme CSS overrides
6. ✗ Nuclear CSS approaches with maximum specificity

### Current State:
- Widget functions properly with carousel/grid toggle
- Default colors work (dark blue normal, teal hover)
- Color controls appear in Elementor but values not applied
- No console errors or obvious conflicts

### Workaround:
- Users can manually edit the base CSS in the widget file
- Or use global CSS overrides targeting specific widget instances

### Files Affected:
- `/inc/elementor/msh-injury-cards.php` - Main widget file
- `/functions.php` - Removed multiple CSS injection attempts

### Next Steps:
- May require Elementor core debugging
- Consider alternative control implementation methods
- Test on clean Elementor installation to isolate issue