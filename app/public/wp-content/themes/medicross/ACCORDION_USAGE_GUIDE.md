# Medical Accordion Widget - Usage Guide

## Quick Start

1. **Add Widget**: In Elementor, search for "Medical Accordion"
2. **Configure Items**: Add accordion items with titles and descriptions
3. **Choose Content Type**: Select how to add content (Widget Area, Shortcode, etc.)
4. **Add Content**: Based on your chosen content type
5. **Style**: Customize colors, typography, and spacing

## Content Types Explained

### 1. Widget Area (Recommended for Complex Content)
- **Best for**: Complex layouts with multiple widgets
- **How it works**: Creates a widget area you can manage in Appearance → Widgets
- **Perfect for**: Contact forms, image galleries, product showcases

**Steps:**
1. Set Content Type to "Widget Area"
2. Save the page
3. Go to **Appearance → Widgets**
4. Find "Medical Accordion - [Your Title] - Item X"
5. Drag widgets into this area
6. Widgets appear inside accordion when opened

### 2. Shortcode (Best for Quick Content)
- **Best for**: Simple content with shortcodes
- **How it works**: Processes WordPress shortcodes
- **Perfect for**: Contact forms, buttons, columns

**Example:**
```
[contact-form-7 id="123" title="Product Inquiry"]

[pxl_button text="Learn More" link="/products"]

<div class="pxl-accordion-two-columns">
    <div class="pxl-accordion-column">
        <h4>Common reasons for wrist bracing:</h4>
        <ul>
            <li>ACL, MCL, or meniscus injuries</li>
            <li>Post-surgical rehabilitation</li>
            <li>Chronic instability or joint pain</li>
        </ul>
    </div>
    <div class="pxl-accordion-column">
        <h4>Hand and wrist braces help to:</h4>
        <ul>
            <li>Prevent or reduce ligament damage</li>
            <li>Protect against re-injury during sports</li>
            <li>Support proper joint alignment</li>
        </ul>
    </div>
</div>
```

### 3. Rich Text Editor
- **Best for**: Simple text content with formatting
- **How it works**: Standard WordPress WYSIWYG editor
- **Perfect for**: Formatted text, simple lists

### 4. Mixed (Widget Area + Shortcode)
- **Best for**: Maximum flexibility
- **How it works**: Combines widget area with shortcode content
- **Perfect for**: Complex pages with multiple content types

## Pre-Built Layouts

### Two-Column Layout (for comparison content)
```html
<div class="pxl-accordion-two-columns">
    <div class="pxl-accordion-column">
        <h4>Column Title</h4>
        <ul>
            <li>List item 1</li>
            <li>List item 2</li>
        </ul>
    </div>
    <div class="pxl-accordion-column">
        <h4>Column Title</h4>
        <ul>
            <li>List item 1</li>
            <li>List item 2</li>
        </ul>
    </div>
</div>
```

### Simple List Layout
```html
<p>Among our selection of knee braces:</p>
<ul>
    <li>Compression sleeves and supports</li>
    <li>Hinged or wraparound braces</li>
    <li>Post-surgical stabilization braces</li>
    <li>ACL, MCL, and PCL-specific protection</li>
</ul>
```

## Style Options

### Layout Styles
- **Medical Clean**: Clean lines, subtle borders (matches your image)
- **Medical Card**: Card-style with shadows and rounded corners
- **Medical Minimal**: Minimal borders, clean typography

### Customization Options
- **Colors**: Header/content backgrounds, text colors, toggle colors
- **Typography**: Separate controls for titles and descriptions
- **Spacing**: Item gaps, padding, border radius
- **Animations**: Speed control for expand/collapse

## Advanced Usage

### Direct Linking
Add an "Item ID" to any accordion item to enable direct linking:
- Item ID: `wrist-braces`
- Link: `yoursite.com/page#wrist-braces`
- Accordion will auto-open to that item

### Keyboard Navigation
- **Tab**: Navigate between accordion headers
- **Enter/Space**: Toggle accordion item
- **Arrow Keys**: Move between items
- **Home/End**: Jump to first/last item

### JavaScript Events
```javascript
// Listen for accordion events
document.addEventListener('pxl:accordion:opened', function(e) {
    console.log('Accordion opened:', e.detail.index);
});

// Programmatically control accordion
pxlMedicalAccordion.openItem('accordion-id', 0);
pxlMedicalAccordion.closeItem('accordion-id', 1);
```

## Best Practices

### Content Structure
1. **Keep titles concise** - Use 2-4 words when possible
2. **Descriptions should preview content** - Help users decide whether to open
3. **Organize logically** - Group related items together
4. **Use consistent formatting** - Same pattern for similar content

### Performance
- **Widget Areas load lazily** - Only when accordion opens
- **Limit items per accordion** - 5-8 items maximum for best UX
- **Optimize images** - Use appropriate sizes for accordion content

### Accessibility
- Widget follows ARIA accordion patterns
- Full keyboard navigation support
- Screen reader friendly
- High contrast support

## Troubleshooting

### Widget Area Not Showing
1. Save the Elementor page first
2. Check Appearance → Widgets for the accordion widget area
3. Make sure "Content Type" is set to "Widget Area" or "Mixed"

### Content Not Displaying
1. Check if shortcodes are valid
2. Ensure plugins for shortcodes are active
3. Try switching content types to isolate the issue

### Styling Issues
1. Check if CSS is loading (view page source)
2. Try different layout styles
3. Use browser dev tools to inspect elements

### JavaScript Not Working
1. Check browser console for errors
2. Ensure jQuery is loaded
3. Try refreshing the page

## Examples in Action

### Medical Product Information
```
Title: "Wrist Braces"
Description: "Support options for wrist injuries and recovery"
Content Type: Mixed
Widget Area: Contact Form 7 widget
Shortcode Content: Two-column comparison layout
```

### Service Offerings
```
Title: "Physical Therapy"
Description: "Comprehensive rehabilitation services"  
Content Type: Widget Area
Widgets: Text widget, Image gallery, Contact form
```

### FAQ Section
```
Title: "Insurance Coverage"
Description: "What insurance plans do we accept?"
Content Type: Rich Text Editor  
Content: Formatted text with insurance list
```

Remember: The accordion is designed to handle complex medical content while maintaining clean, professional appearance that matches your brand.