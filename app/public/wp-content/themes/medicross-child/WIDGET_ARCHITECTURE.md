# Medicross Theme Widget Architecture

## Overview
The Medicross theme uses two different approaches for creating Elementor widgets:

## Approach 1: Template-Based Widgets (Complex)
**Used by**: pxl_post_grid, pxl_post_carousel, etc.

### Structure:
```
/elements/widgets/pxl_post_grid.php       # Main widget class & controls
/elements/templates/pxl_post_grid/        # Template files
  - layout-service-1.php
  - layout-service-2.php
  - layout-pxl_product-1.php
/elements/element-templates.php           # Individual item rendering
/elements/element-functions.php           # Helper functions
```

### Data Flow:
1. **Widget Class** (`pxl_post_grid.php`)
   - Defines controls/settings in `register_controls()`
   - In `render()`, calls layout template

2. **Layout Template** (`layout-service-2.php`)
   - Gets settings: `$widget->get_setting('setting_name')`
   - Builds `$load_more` array with all settings
   - Calls `medicross_get_post_grid($posts, $load_more)`

3. **Element Templates** (`element-templates.php`)
   - `medicross_get_post_grid()` receives `$load_more` as `$settings`
   - Individual item rendering functions access `$settings['setting_name']`
   - Renders HTML for each post item

### Adding Controls:
```php
// In widget class (pxl_post_grid.php) - limited access
// OR extend via hooks:
add_action('elementor/element/pxl_post_grid/tab_grid/after_section_start', function($element){
    $element->add_control('my_setting', [...]);
});

// In layout template (layout-service-2.php):
$my_setting = $widget->get_setting('my_setting');
$load_more = array(
    // ... other settings
    'my_setting' => $my_setting,
);

// In element-templates.php:
if (isset($settings['my_setting'])) {
    // Use $settings['my_setting']
}
```

## Approach 2: Custom Widgets (Simple)
**Used by**: MSH Services Grid, custom widgets

### Structure:
```
/inc/elementor/msh-services-grid.php      # Complete widget in one file
```

### Data Flow:
1. **Widget Class** extends `\Elementor\Widget_Base`
2. **Direct Access**: `$settings = $this->get_settings_for_display()`
3. **Direct Rendering**: HTML output directly in `render()` method

### Implementation:
```php
class MSH_Services_Grid_Widget extends Widget_Base {
    protected function register_controls() {
        $this->add_control('title_tag', [
            'type' => Controls_Manager::SELECT,
            'options' => ['h1'=>'H1', 'h2'=>'H2', ...],
            'default' => 'h3',
        ]);
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $title_tag = $settings['title_tag'];
        
        echo "<{$title_tag}>Title</{$title_tag}>";
    }
}
```

## Style Controls
### Template-Based Widgets:
```php
add_action('elementor/element/pxl_post_grid/section_style_title/after_section_end', function($element){
    $element->start_controls_section('my_style_section', [
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'conditions' => [
            'terms' => [
                [
                    'terms' => [
                        ['name' => 'post_type', 'operator' => '==', 'value' => 'pxl_product']
                    ]
                ]
            ],
        ],
    ]);
    
    $element->add_control('my_color', [
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .my-element' => 'color: {{VALUE}};',
        ],
    ]);
});
```

### Custom Widgets:
```php
// In register_controls():
$this->start_controls_section('style_section', [
    'tab' => Controls_Manager::TAB_STYLE,
]);

$this->add_control('title_color', [
    'type' => Controls_Manager::COLOR,
    'selectors' => [
        '{{WRAPPER}} .title' => 'color: {{VALUE}};',
    ],
]);
```

## Key Differences

| Aspect | Template-Based | Custom |
|--------|---------------|--------|
| Complexity | High | Low |
| Extensibility | High (multiple layouts) | Limited |
| Performance | Slower (multiple files) | Faster |
| Maintenance | Complex | Simple |
| Reusability | High | Medium |
| Control Access | Via hooks or core modification | Direct |

## Best Practices
1. **For New Widgets**: Use Custom approach (simpler)
2. **For Existing Widgets**: Use hooks to extend
3. **Style Controls**: Always use proper tab (`TAB_STYLE`)
4. **Conditions**: Use complex conditions structure for template-based widgets
5. **Selectors**: Use `{{WRAPPER}}` for proper scoping

## Common Issues
1. **Settings Not Working**: Check data flow from widget → template → element-templates
2. **Styles Not Applying**: Verify selector specificity and wrapper usage
3. **Conditions Not Working**: Use `conditions` array, not simple `condition`
4. **Server Errors**: Complex control structures can cause issues - simplify

## File Locations
- **Core Widgets**: `/themes/medicross/elements/widgets/`
- **Custom Widgets**: `/themes/medicross-child/inc/elementor/`
- **Widget Extensions**: `/themes/medicross-child/inc/elementor/`
- **CSS**: `/themes/medicross-child/assets/css/`