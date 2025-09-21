# Custom Widgets Technical Specifications

This document tracks all custom widgets created for the Main Street Health / Medicross theme.

---

## Widget Index

1. [Medical Accordion](#medical-accordion) - Advanced accordion with widget areas and medical styling
2. [MSH Services List](#msh-services-list) - *(To be documented)*
3. [Custom Testimonials](#custom-testimonials) - *(To be documented)*

---

## Medical Accordion

### Overview
Advanced accordion widget designed for medical content with support for nested widgets, shortcodes, and complex layouts within accordion items.

### Technical Specifications

#### Widget Details
- **File**: `elements/widgets/pxl_medical_accordion.php`
- **Template**: `elements/templates/pxl_medical_accordion/layout-1.php`
- **CSS**: `assets/css/medical-accordion.css`
- **JS**: `assets/js/medical-accordion.js`
- **Icon**: `eicon-accordion`
- **Category**: `pxltheme-core`

#### Key Features
- ✅ **Widget Areas Integration**: Each accordion item has its own widget area
- ✅ **Shortcode Support**: Full shortcode parsing in content areas
- ✅ **Medical-Focused Design**: Clean, professional styling for healthcare content
- ✅ **Responsive Layout**: Mobile-optimized accordion behavior
- ✅ **Highly Customizable**: Extensive styling options
- ✅ **Smooth Animations**: CSS transitions for expand/collapse
- ✅ **Accessibility**: ARIA labels and keyboard navigation support

#### Widget Settings Structure

```php
'sections' => [
    'layout_section' => [
        'layout_style' => 'select', // Options: 'medical-clean', 'medical-card', 'medical-minimal'
        'animation_speed' => 'number', // Default: 300ms
        'close_others' => 'switcher', // Auto-close other items when opening
        'first_item_open' => 'switcher', // Open first item by default
    ],
    'items_section' => [
        'accordion_items' => 'repeater' => [
            'item_title' => 'text',
            'item_description' => 'textarea', // Closed state description
            'content_type' => 'select', // 'widgets', 'shortcode', 'editor', 'mixed'
            'widget_area_id' => 'text', // Auto-generated unique ID
            'shortcode_content' => 'textarea',
            'editor_content' => 'wysiwyg',
            'item_icon' => 'icons',
            'item_id' => 'text', // For anchor linking
        ]
    ],
    'styling_section' => [
        'header_styling' => [
            'header_bg_color' => 'color',
            'header_text_color' => 'color', 
            'header_border' => 'border',
            'header_padding' => 'dimensions',
            'title_typography' => 'typography',
            'description_typography' => 'typography',
        ],
        'content_styling' => [
            'content_bg_color' => 'color',
            'content_text_color' => 'color',
            'content_border' => 'border', 
            'content_padding' => 'dimensions',
            'content_typography' => 'typography',
        ],
        'toggle_styling' => [
            'toggle_color' => 'color',
            'toggle_hover_color' => 'color',
            'toggle_size' => 'slider', // 14-30px
            'toggle_position' => 'select', // 'right', 'left'
        ],
        'spacing_styling' => [
            'items_gap' => 'slider', // 0-50px
            'border_radius' => 'dimensions',
        ]
    ]
]
```

#### Content Types Support

1. **Widget Areas** (`content_type: 'widgets'`)
   - Dynamically creates widget area: `medical_accordion_item_{item_id}`
   - Users drag widgets into accordion via Appearance > Widgets
   - Supports any theme/plugin widgets

2. **Shortcode** (`content_type: 'shortcode'`) 
   - Full shortcode parsing with `do_shortcode()`
   - Supports nested shortcodes
   - Contact forms, buttons, columns, etc.

3. **WYSIWYG Editor** (`content_type: 'editor'`)
   - Rich text editor with media support
   - HTML content support
   - Automatic paragraph formatting

4. **Mixed Content** (`content_type: 'mixed'`)
   - Widget area + shortcode content combined
   - Maximum flexibility for complex layouts

#### CSS Classes Structure

```css
.pxl-medical-accordion {
    /* Main wrapper */
}

.pxl-accordion-item {
    /* Individual accordion item */
}

.pxl-accordion-header {
    /* Clickable header area */
}

.pxl-accordion-title {
    /* Main title text */
}

.pxl-accordion-description {
    /* Subtitle/description text */
}

.pxl-accordion-toggle {
    /* +/- icon */
}

.pxl-accordion-content {
    /* Expandable content area */
}

.pxl-accordion-widget-area {
    /* Widget area wrapper */
}

.pxl-accordion-shortcode-content {
    /* Shortcode content wrapper */
}

/* State classes */
.pxl-accordion-item.active {
    /* Open state */
}

.pxl-accordion-item.collapsed {
    /* Closed state */
}

/* Style variants */
.pxl-medical-accordion.style-medical-clean { }
.pxl-medical-accordion.style-medical-card { }
.pxl-medical-accordion.style-medical-minimal { }
```

#### JavaScript Functionality

```javascript
// Core features
- Smooth expand/collapse animations
- Auto-close other items (optional)
- Keyboard accessibility (Enter, Space, Arrow keys)
- ARIA state management
- Hash URL support for direct linking
- Mobile touch optimization

// Events
- pxl:accordion:opened
- pxl:accordion:closed  
- pxl:accordion:ready
```

#### Design Specifications (Based on Visual Reference)

**Closed State:**
- Clean header with title + description
- Subtle border separator
- Right-aligned toggle icon (+)
- Hover effects on entire header
- Typography: Title (bold), Description (regular, muted)

**Open State:**
- Smooth slide-down animation
- Content area with padding
- Support for complex layouts:
  - Paragraphs
  - Bulleted lists
  - Two-column layouts
  - Widget areas for any content
- Subtle background color change
- Toggle icon changes to (-)

**Styling Options:**
- Multiple color schemes
- Typography controls for all text elements
- Spacing and padding controls
- Border and shadow options
- Animation speed controls
- Mobile-responsive breakpoints

#### Widget Area Management

```php
// Dynamic widget area registration
function register_medical_accordion_widget_areas() {
    // Scan all Medical Accordion widgets on site
    // Register widget areas for each accordion item
    // Format: "Medical Accordion - {Widget Title} - Item {N}"
}

// Widget area cleanup
function cleanup_unused_accordion_areas() {
    // Remove widget areas for deleted accordion items
    // Prevent widget area bloat
}
```

#### Accessibility Features

- **ARIA Labels**: Proper accordion ARIA patterns
- **Keyboard Navigation**: Tab, Enter, Space, Arrow keys
- **Screen Reader Support**: Announces state changes
- **Focus Management**: Clear focus indicators
- **High Contrast**: Compatible with accessibility themes

#### Browser Support

- Modern browsers (Chrome 70+, Firefox 65+, Safari 12+, Edge 79+)
- IE 11 with polyfills
- Mobile browsers (iOS Safari, Chrome Mobile)
- Progressive enhancement for older browsers

#### Performance Considerations

- **Lazy Loading**: Widget areas only load when accordion opens
- **CSS Optimization**: Minimal CSS footprint
- **JS Optimization**: Event delegation, debounced animations
- **No jQuery Dependency**: Pure JavaScript implementation

---

## Usage Examples

### Basic Medical Accordion
```php
// Simple text-based accordion
[pxl_medical_accordion]
[accordion_item title="Wrist Braces" description="Support for wrist injuries"]
Content here...
[/accordion_item]
[/pxl_medical_accordion]
```

### Advanced with Widget Areas
1. Add Medical Accordion widget to page
2. Configure accordion items in Elementor
3. Go to Appearance > Widgets
4. Find "Medical Accordion - Item 1" widget area
5. Drag widgets into the area
6. Widgets appear inside accordion when opened

### Integration Notes

- **Theme Integration**: Uses existing Medicross color schemes
- **Plugin Compatibility**: Works with Contact Form 7, WooCommerce widgets
- **Elementor Integration**: Full Elementor widget support in accordion areas
- **Performance**: Minimal impact, loads only necessary resources

---

## Development Roadmap

### Phase 1: Core Implementation ✅
- Basic accordion functionality
- Widget area integration
- Core styling options

### Phase 2: Advanced Features
- [ ] Animation variants (slide, fade, scale)
- [ ] Advanced layout templates
- [ ] Export/import accordion configurations
- [ ] Global accordion style presets

### Phase 3: Enhancement
- [ ] Nested accordion support
- [ ] Search functionality within accordions
- [ ] Print-friendly layouts
- [ ] Analytics integration for interaction tracking

---

*Last Updated: $(date)*
*Version: 1.0.0*
*Maintainer: Claude Code Assistant*