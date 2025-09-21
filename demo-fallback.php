<?php
/**
 * Demo Enhanced Fallback System (Standalone)
 * Shows how the enhanced fallback would work
 */

// Include the AI class definition
class AI_Media_Descriptor_Demo {
    /**
     * Enhanced SVG analysis
     */
    public function analyze_svg_content($file_path) {
        if (!file_exists($file_path)) {
            return null;
        }
        
        $svg_content = file_get_contents($file_path);
        $keywords = [];
        
        // Extract text elements
        if (preg_match_all('/<text[^>]*>([^<]+)<\/text>/i', $svg_content, $matches)) {
            foreach ($matches[1] as $text) {
                $text = trim(strip_tags($text));
                if (!empty($text) && strlen($text) > 2) {
                    $keywords[] = $text;
                }
            }
        }
        
        // Check for common medical/health SVG patterns
        $medical_patterns = [
            'stethoscope' => 'medical stethoscope icon',
            'heart' => 'heart health symbol',
            'cross' => 'medical cross symbol',
            'pill' => 'medication pill icon',
            'doctor' => 'healthcare professional icon',
            'hospital' => 'hospital building icon',
            'ambulance' => 'emergency ambulance icon',
            'syringe' => 'medical injection icon',
            'shield' => 'health protection symbol',
            'calendar' => 'appointment scheduling icon',
            'phone' => 'contact communication icon',
            'location' => 'facility location marker',
            'user' => 'patient or staff icon',
            'group' => 'medical team symbol',
            'check' => 'health verification icon',
            'arrow' => 'process flow indicator',
            'circle' => 'healthcare process step',
            'square' => 'medical information block',
            'chart' => 'health data visualization',
            'graph' => 'medical statistics display'
        ];
        
        foreach ($medical_patterns as $pattern => $description) {
            if (stripos($svg_content, $pattern) !== false) {
                $keywords[] = $description;
            }
        }
        
        // Analyze SVG structure for additional context
        $element_patterns = [
            '<path' => 'vector graphic element',
            '<rect' => 'rectangular graphic element',
            '<circle' => 'circular graphic element',
            '<polygon' => 'geometric shape element',
            '<g' => 'grouped graphic elements',
            'transform=' => 'positioned graphic element'
        ];
        
        $element_count = 0;
        foreach ($element_patterns as $element => $description) {
            if (substr_count($svg_content, $element) > 0) {
                $element_count += substr_count($svg_content, $element);
            }
        }
        
        // If it's a complex SVG (many elements), describe as such
        if ($element_count > 10) {
            $keywords[] = 'complex medical illustration';
        } elseif ($element_count > 5) {
            $keywords[] = 'medical graphic design';
        } elseif ($element_count > 0) {
            $keywords[] = 'healthcare icon';
        }
        
        // Check for common CSS classes or IDs that might indicate purpose
        if (preg_match('/class="[^"]*icon[^"]*"/i', $svg_content) || 
            preg_match('/id="[^"]*icon[^"]*"/i', $svg_content)) {
            $keywords[] = 'healthcare interface icon';
        }
        
        return array_unique($keywords);
    }
    
    /**
     * Intelligent fallback based on filename and context
     */
    public function generate_fallback($filename, $mime_type, $file_path = null) {
        // Clean filename
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/-scaled.*$/', '', $name);
        $name = preg_replace('/-\d+x\d+/', '', $name);
        $name = preg_replace('/-\d+$/', '', $name);
        $name = str_replace(['-', '_'], ' ', $name);
        $name = ucwords(strtolower($name));
        $name = preg_replace('/\s+/', ' ', $name);
        $name = trim($name);
        
        // Enhanced analysis for SVG files
        if ($mime_type === 'image/svg+xml' && $file_path && file_exists($file_path)) {
            $svg_keywords = $this->analyze_svg_content($file_path);
            if (!empty($svg_keywords)) {
                $name = implode(' ', array_slice($svg_keywords, 0, 4));
            }
        }
        
        // Healthcare context mapping
        $healthcare_context = [
            'doctor' => ['physician', 'medical professional', 'healthcare provider'],
            'patient' => ['client', 'healthcare recipient', 'individual'],
            'team' => ['medical staff', 'healthcare professionals', 'care providers'],
            'service' => ['medical service', 'healthcare solution', 'treatment option'],
            'clinic' => ['medical facility', 'healthcare center', 'treatment center'],
            'care' => ['medical care', 'healthcare service', 'patient treatment'],
            'health' => ['wellness', 'medical', 'healthcare'],
            'treatment' => ['medical procedure', 'therapeutic service', 'care solution']
        ];
        
        // Build contextual description
        $title_words = explode(' ', strtolower($name));
        $enhanced_words = [];
        
        foreach ($title_words as $word) {
            if (isset($healthcare_context[$word])) {
                $enhanced_words[] = $healthcare_context[$word][array_rand($healthcare_context[$word])];
            } else {
                $enhanced_words[] = $word;
            }
        }
        
        $enhanced_title = ucwords(implode(' ', $enhanced_words));
        
        // Generate variations
        $descriptions = [
            "Professional $enhanced_title at Main Street Health medical facility",
            "$enhanced_title providing quality healthcare services",
            "Main Street Health $enhanced_title for patient care",
            "$enhanced_title in modern medical environment",
            "High-quality $enhanced_title for healthcare services"
        ];
        
        return [
            'title' => $name,
            'alt_text' => $enhanced_title,
            'description' => $descriptions[array_rand($descriptions)]
        ];
    }
}

echo "=== Enhanced Fallback System Demo ===\n\n";

$generator = new AI_Media_Descriptor_Demo();

// Test files from the uploads directory
$test_files = [
    'Group-328-4-1-1.svg' => 'image/svg+xml',
    'Group-389-scaled-1-2-600x600.png' => 'image/png',
    'doctor-consultation-room-2.jpg' => 'image/jpeg',
    'medical-team-staff.webp' => 'image/webp',
    'patient-care-service.png' => 'image/png'
];

$upload_base = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads';

foreach ($test_files as $filename => $mime_type) {
    echo "Processing: $filename ($mime_type)\n";
    echo str_repeat("-", 50) . "\n";
    
    // Find the actual file if it exists
    $found_files = [];
    if ($mime_type === 'image/svg+xml') {
        $found_files = glob($upload_base . '/**/*.svg', GLOB_BRACE);
    } else {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $found_files = glob($upload_base . "/**/*.$ext", GLOB_BRACE);
    }
    
    $file_path = null;
    if (!empty($found_files)) {
        $file_path = $found_files[0]; // Use first found file
        $filename = basename($file_path);
        $mime_type = mime_content_type($file_path);
    }
    
    $result = $generator->generate_fallback($filename, $mime_type, $file_path);
    
    echo "Original filename: $filename\n";
    if ($file_path) {
        echo "Found file: " . basename($file_path) . "\n";
        echo "File exists: ✓\n";
        echo "File size: " . number_format(filesize($file_path)) . " bytes\n";
    } else {
        echo "File exists: ✗ (using filename only)\n";
    }
    
    echo "\n";
    echo "Generated Results:\n";
    echo "Title: " . $result['title'] . "\n";
    echo "Alt Text: " . $result['alt_text'] . "\n";
    echo "Description: " . $result['description'] . "\n";
    echo "\n" . str_repeat("=", 50) . "\n\n";
}

// Show SVG analysis example if SVG file found
$svg_files = glob($upload_base . '/**/*.svg', GLOB_BRACE);
if (!empty($svg_files)) {
    echo "=== SVG Analysis Example ===\n";
    $svg_file = $svg_files[0];
    echo "Analyzing: " . basename($svg_file) . "\n";
    
    $keywords = $generator->analyze_svg_content($svg_file);
    if (!empty($keywords)) {
        echo "Extracted keywords:\n";
        foreach ($keywords as $keyword) {
            echo "- $keyword\n";
        }
    } else {
        echo "No specific keywords found\n";
    }
    
    echo "\n";
    $svg_result = $generator->generate_fallback(basename($svg_file), 'image/svg+xml', $svg_file);
    echo "Final SVG Description:\n";
    echo "Title: " . $svg_result['title'] . "\n";
    echo "Alt Text: " . $svg_result['alt_text'] . "\n";
    echo "Description: " . $svg_result['description'] . "\n";
}
?>