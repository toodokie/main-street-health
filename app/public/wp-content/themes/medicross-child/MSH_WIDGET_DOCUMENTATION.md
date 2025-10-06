# Main Street Health - Widget Documentation

## Table of Contents

### [Part 1: User Manual](#part-1-user-manual)
- [Team Widgets](#team-widgets)
- [Content Widgets](#content-widgets)
- [Service & Healthcare Widgets](#service--healthcare-widgets)
- [Review & Testimonial Widgets](#review--testimonial-widgets)

### [Part 2: Developer Documentation](#part-2-developer-documentation)
- [Widget Architecture](#widget-architecture)
- [File Structure](#file-structure)
- [Custom Post Types](#custom-post-types)
- [Development Guidelines](#development-guidelines)

---

# Part 1: User Manual

This section provides step-by-step instructions for content editors and site administrators on how to use the custom Main Street Health widgets.

## Team Widgets

### Case Team Carousel (Enhanced)
**Location**: Elementor Widgets → Case Team Carousel
**Purpose**: Display team member profiles in a responsive carousel format

#### Usage Instructions:
1. **Add Widget**: Search for "Case Team Carousel" in Elementor
2. **Select Layout**: Choose from 3 available layouts:
   - **Layout 1**: Standard with enhanced navigation (recommended)
   - **Layout 2**: Alternative style
   - **Layout 3**: MSH Style (manual entry)

#### Layout 1 Features (Enhanced):
- **Centered content**: All text (name, position, description) is centered
- **Smart navigation**:
  - With both arrows + pagination: Shows `← • • • →` layout
  - With only arrows: Shows `← →` centered
  - With only pagination: Shows `• • •` dots only
- **No share icons**: Social sharing icons removed from hover
- **Fixed text flow**: Descriptions don't break mid-sentence

#### Settings:
- **Slides to Show**: Desktop (3), Tablet (2), Mobile (1)
- **Autoplay**: Yes/No with speed control
- **Navigation**: Arrows, Pagination, or Both
- **Animation**: Various entrance animations available

#### Content Entry:
- **Image**: Upload team member photo
- **Name**: Enter full name
- **Position**: Job title or role
- **Description**: Bio or description text
- **Link**: Optional link to team member page

---

## Content Widgets

### MSH Single Post Display
**Location**: Elementor Widgets → MSH Single Post Display
**Purpose**: Display a specific post/page with custom styling

#### Usage Instructions:
1. **Select Content Source**: Choose post, page, or custom post type
2. **Pick Specific Item**: Search and select the content to display
3. **Configure Display**: Choose what elements to show:
   - Featured image
   - Title
   - Excerpt/content
   - Categories/tags
   - Read more button

#### Styling Options:
- **Card style**: Background, borders, shadows
- **Typography**: Custom fonts for title, content
- **Colors**: Text, background, accent colors
- **Spacing**: Padding, margins

### MSH Popular Tags
**Location**: Elementor Widgets → MSH Popular Tags
**Purpose**: Display most-used tags with custom styling

#### Settings:
- **Number of Tags**: How many to display
- **Order**: By count, name, or random
- **Style**: Colors, typography, spacing
- **Link Behavior**: Same tab or new tab

---

## Service & Healthcare Widgets

### MSH Services List
**Location**: Elementor Widgets → MSH Services List
**Purpose**: Display services in a clean list format

#### Features:
- **Auto-pulls** from Services custom post type
- **Responsive design** with mobile optimization
- **Custom styling** to match MSH branding
- **SEO optimized** with proper heading structure

#### Settings:
- **Number of Services**: Limit how many show
- **Order**: By date, title, or menu order
- **Display Options**: Show excerpts, icons, links

### MSH Services Grid
**Location**: Elementor Widgets → MSH Services Grid
**Purpose**: Display services in a grid layout with cards

#### Configuration:
- **Columns**: 1-4 columns (responsive)
- **Card Style**: Borders, shadows, hover effects
- **Content**: Title, excerpt, featured image, link
- **Spacing**: Gap between cards

### MSH Injury Cards
**Location**: Elementor Widgets → MSH Injury Cards
**Purpose**: Display injury/condition information in card format

#### Features:
- **Custom icons** for each injury type
- **Responsive grid** layout
- **Hover effects** and animations
- **Link to detail pages**

#### Content Sources:
- **Manual Entry**: Add cards individually
- **Database**: Pull from Injuries custom post type

### MSH Mixed Post Carousel
**Location**: Elementor Widgets → MSH Mixed Post Carousel
**Purpose**: Combined carousel showing Services, Injuries, and other content

#### Advanced Features:
- **Multiple post types** in one carousel
- **Unified styling** across different content types
- **Smart content mixing** and ordering
- **Custom filtering** options

---

## Review & Testimonial Widgets

### MSH Testimonial Carousel (Enhanced)
**Location**: Elementor Widgets → MSH Testimonial Carousel
**Purpose**: Display client testimonials with two data source options

#### NEW: Data Source Options:
1. **Manual Entry (Current)**:
   - Use repeater fields to add testimonials individually
   - Full control over each testimonial
   - Existing widgets continue working unchanged

2. **Reviews Database**:
   - Automatically pulls from Reviews custom post type
   - Centralized management of all reviews
   - Update once, reflects everywhere

#### Database Mode Settings:
- **Number of Reviews**: 1-20 testimonials
- **Order By**: Date, Client Name, Menu Order, or Random
- **Sort Direction**: Newest first or oldest first

#### Manual Mode Settings:
- **Testimonial Content**: Review text
- **Client Name**: Full name
- **Client Position**: Job title/company (optional)
- **Client Image**: Photo
- **Quote Icon**: Show/hide quotation marks

#### Styling Features:
- **Card Design**: Custom backgrounds, borders, shadows
- **Typography**: Control fonts for quotes, names, positions
- **Colors**: Brand-consistent color schemes
- **Carousel Controls**: Autoplay, arrows, dots, speed

---

# Part 2: Developer Documentation

This section provides technical information for developers working with the widget system.

## Widget Architecture

### Widget Categories
All MSH custom widgets are organized under the `'msh-widgets'` category in Elementor for easy identification.

### File Organization
```
/wp-content/themes/medicross-child/
├── elements/widgets/          # Simple widgets (legacy location)
│   ├── msh_doctor_widget.php
│   ├── msh_popular_tags.php
│   ├── msh_single_post_display.php
│   └── msh_team_carousel.php  # Unused, created but not implemented
├── inc/elementor/             # Complex widgets (current location)
│   ├── msh-services-list.php
│   ├── msh-services-grid.php
│   ├── msh-injury-cards.php
│   ├── msh-injury-carousel.php
│   ├── msh-injuries-grid-final.php
│   ├── msh-mixed-post-carousel.php
│   ├── msh-testimonial-carousel.php
│   └── msh-steps.php
└── inc/
    └── register-reviews-post-type.php  # Reviews CPT for testimonials
```

### Modified Theme Files
```
/wp-content/themes/medicross/
└── elements/templates/pxl_team_carousel/
    ├── layout-1.php           # Enhanced with navigation fixes
    └── layout-3.php           # MSH style (manual entry)
```

## Widget Registration System

### Registration Method
Widgets are registered through multiple hooks in `functions.php`:

```php
// Widget inclusion (in msh_include_elementor_widgets function)
if (did_action('elementor/loaded')) {
    $widget_file = get_stylesheet_directory() . '/inc/elementor/widget-name.php';
    if (file_exists($widget_file)) {
        include_once $widget_file;
    }
}

// Widget registration (in msh_register_elementor_widgets function)
if (class_exists('Widget_Class_Name')) {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widget_Class_Name());
}

// Modern registration hook
add_action('elementor/widgets/register', function($widgets_manager) {
    if (class_exists('Widget_Class_Name')) {
        $widgets_manager->register(new \Widget_Class_Name());
    }
});
```

### Widget Base Structure
All MSH widgets extend `\Elementor\Widget_Base`:

```php
class MSH_Widget_Name extends \Elementor\Widget_Base {
    public function get_name() { return 'msh_widget_name'; }
    public function get_title() { return __('Widget Title', 'medicross-child'); }
    public function get_icon() { return 'eicon-icon-name'; }
    public function get_categories() { return ['msh-widgets']; }

    protected function register_controls() { /* Control definitions */ }
    protected function render() { /* Output rendering */ }
}
```

## Custom Post Types

### Reviews Post Type (`msh_review`)
**Purpose**: Centralized management of testimonials and reviews

**Structure**:
- **Title**: Client name
- **Content**: Review text
- **Featured Image**: Client photo
- **Status**: Public (admin only), not publicly queryable

**Admin Features**:
- Custom menu icon (star)
- Simplified admin columns
- Instructions meta box
- Gutenberg editor support

**Usage in Widgets**:
```php
$args = [
    'post_type' => 'msh_review',
    'posts_per_page' => $limit,
    'orderby' => $orderby,
    'order' => $order,
];
$reviews = new WP_Query($args);
```

## Widget Development Guidelines

### Naming Conventions
- **Class Names**: `MSH_Widget_Name` (PascalCase with MSH prefix)
- **Widget IDs**: `msh_widget_name` (snake_case with msh prefix)
- **File Names**: `msh-widget-name.php` (kebab-case with msh prefix)

### Control Organization
Structure controls in logical sections:
1. **Content Section**: Main content settings
2. **Layout Section**: Display and arrangement options
3. **Style Section**: Visual styling controls
4. **Advanced Section**: Technical and animation settings

### Responsive Design
Always include responsive controls for:
- Columns/slides to show
- Typography sizes
- Spacing and margins
- Hide/show elements on different devices

### Data Source Patterns
For widgets that can pull from databases:
```php
// Add data source selector
$this->add_control('data_source', [
    'label' => __('Data Source', 'medicross-child'),
    'type' => Controls_Manager::SELECT,
    'options' => [
        'manual' => __('Manual Entry', 'medicross-child'),
        'database' => __('Database', 'medicross-child'),
    ],
]);

// Conditional controls
$this->add_control('posts_per_page', [
    'condition' => ['data_source' => 'database'],
    // ... other settings
]);
```

### Performance Considerations
- **Caching**: Use `wp_cache_get/set` for expensive queries
- **Lazy Loading**: Implement for image-heavy widgets
- **Query Optimization**: Use specific fields in WP_Query when possible
- **Asset Loading**: Only enqueue scripts/styles when widget is used

### Error Handling
Always include fallbacks:
```php
// Check for data before rendering
if (empty($data)) {
    echo '<p>' . __('No content found.', 'medicross-child') . '</p>';
    return;
}

// Validate settings
$slides_to_show = max(1, intval($settings['slides_to_show'] ?? 3));
```

### Security Best Practices
- **Sanitization**: Use `esc_attr()`, `esc_html()`, `wp_kses_post()`
- **Nonce Verification**: For any AJAX or form submissions
- **Capability Checks**: Verify user permissions
- **SQL Injection Prevention**: Use WP_Query, avoid direct SQL

### Styling Integration
- **CSS Variables**: Use MSH brand colors (`#35332f`, `#faf9f6`, `#daff00`, `#5CB3CC`)
- **Responsive Breakpoints**: 1024px (tablet), 767px (mobile)
- **Animation Classes**: Use `wow.js` classes when available
- **Font System**: Source Sans Pro + Bree (Adobe Fonts)

### Testing Checklist
Before deploying a widget:
- [ ] Test all control combinations
- [ ] Verify responsive behavior
- [ ] Check with no content scenarios
- [ ] Test browser compatibility
- [ ] Validate HTML output
- [ ] Performance test with large datasets
- [ ] Accessibility check (ARIA labels, keyboard navigation)

### Common Patterns

#### Database Query Pattern
```php
private function get_data_from_database($settings) {
    $args = [
        'post_type' => 'custom_type',
        'posts_per_page' => intval($settings['posts_per_page'] ?? 5),
        'orderby' => $settings['orderby'] ?? 'date',
        'order' => $settings['order'] ?? 'DESC',
    ];

    $query = new WP_Query($args);
    $data = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $data[] = [
                'title' => get_the_title(),
                'content' => get_the_content(),
                'image' => get_the_post_thumbnail_url(),
            ];
        }
        wp_reset_postdata();
    }

    return $data;
}
```

#### Carousel JavaScript Pattern
```javascript
// Initialize Swiper carousel
new Swiper('.widget-carousel', {
    slidesPerView: settings.slides_to_show_mobile || 1,
    spaceBetween: 20,
    autoplay: settings.autoplay ? {
        delay: settings.autoplay_speed,
    } : false,
    breakpoints: {
        768: { slidesPerView: settings.slides_to_show_tablet || 2 },
        1024: { slidesPerView: settings.slides_to_show || 3 }
    }
});
```

## Troubleshooting

### Widget Not Appearing
1. Check widget registration in `functions.php`
2. Verify file exists and has no PHP errors
3. Clear Elementor cache (delete `/uploads/elementor-widget/` cached files)
4. Check widget category and permissions

### Styling Issues
1. Verify CSS specificity (use `!important` sparingly)
2. Check responsive breakpoints
3. Clear browser and plugin caches
4. Inspect element for conflicting styles

### Performance Issues
1. Profile database queries with Query Monitor
2. Check for N+1 query problems
3. Implement caching for expensive operations
4. Optimize images and lazy loading

---

## Change Log

### Recent Enhancements

#### Case Team Carousel (Layout 1)
- **Fixed**: Text line breaking mid-sentence
- **Fixed**: Navigation arrows positioning on large screens
- **Enhanced**: Smart navigation layout (arrows + pagination inline)
- **Improved**: Centered content alignment
- **Removed**: Share icons on hover

#### MSH Testimonial Carousel
- **Added**: Reviews database integration
- **Added**: Data source selection (Manual vs Database)
- **Enhanced**: Backward compatibility maintained
- **Improved**: Centralized testimonial management

#### New: Reviews Post Type
- **Created**: `msh_review` custom post type
- **Features**: Title (name), Content (review), Featured Image (photo)
- **Admin**: Custom interface with instructions
- **Integration**: Works with MSH Testimonial Carousel

### File Modifications
- `medicross/elements/templates/pxl_team_carousel/layout-1.php` - Enhanced navigation
- `medicross-child/inc/elementor/msh-testimonial-carousel.php` - Database integration
- `medicross-child/inc/register-reviews-post-type.php` - New CPT
- `medicross-child/functions.php` - Widget registration updates

---

*Last Updated: December 2024*
*Main Street Health Widget System v2.0*