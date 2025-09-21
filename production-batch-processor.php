<?php
/**
 * Production AI Media Description Batch Processor
 * Works via WordPress admin or direct execution
 */

// Force standalone mode to avoid database connection issues
echo "⚠️  Running in standalone mode\n";
if (!defined('OPENAI_API_KEY')) {
    $envKey = getenv('OPENAI_API_KEY');
    if ($envKey === false) {
        $envKey = '';
    }
    define('OPENAI_API_KEY', $envKey);
}

class Production_AI_Media_Processor {
    private $processed_count = 0;
    private $failed_count = 0;
    private $sample_results = [];
    
    /**
     * Process single image with OpenAI Vision
     */
    public function process_with_openai($image_path, $context = 'Main Street Health medical website') {
        if (!file_exists($image_path)) {
            return false;
        }
        
        $mime_type = mime_content_type($image_path);
        
        // Skip SVG files for OpenAI Vision
        if ($mime_type === 'image/svg+xml') {
            return $this->generate_fallback(basename($image_path), $mime_type, $image_path);
        }
        
        // Check file size (OpenAI limit is 20MB, but keep under 5MB for speed)
        if (filesize($image_path) > 5 * 1024 * 1024) {
            echo "  ⚠️  File too large, using fallback\n";
            return $this->generate_fallback(basename($image_path), $mime_type, $image_path);
        }
        
        // Convert to base64
        $image_data = file_get_contents($image_path);
        $base64_image = base64_encode($image_data);
        $data_url = "data:$mime_type;base64,$base64_image";
        
        $request = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => "Analyze this medical website image and provide:\n1. A 5-8 word SEO-friendly title\n2. A 10-15 word alt text for accessibility\n3. A 20-30 word description for SEO\n\nContext: $context\n\nRespond ONLY with valid JSON in this exact format:\n{\"title\": \"...\", \"alt_text\": \"...\", \"description\": \"...\"}"
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $data_url
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 300
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . OPENAI_API_KEY,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($http_code === 200) {
            $data = json_decode($response, true);
            
            if (isset($data['choices'][0]['message']['content'])) {
                $content = trim($data['choices'][0]['message']['content']);
                
                // Clean up any markdown formatting
                $content = preg_replace('/```json\s*/', '', $content);
                $content = preg_replace('/\s*```/', '', $content);
                $content = trim($content);
                
                // Parse JSON
                $json_data = json_decode($content, true);
                
                if ($json_data && isset($json_data['title'], $json_data['alt_text'], $json_data['description'])) {
                    return [
                        'title' => $json_data['title'],
                        'alt_text' => $json_data['alt_text'],
                        'description' => $json_data['description'],
                        'method' => 'openai_vision'
                    ];
                } else {
                    echo "  ⚠️  Invalid JSON from OpenAI, using fallback\n";
                    return $this->generate_fallback(basename($image_path), $mime_type, $image_path);
                }
            }
        } elseif ($http_code === 429) {
            echo "  ⚠️  API quota exceeded, using fallback\n";
            return $this->generate_fallback(basename($image_path), $mime_type, $image_path);
        } else {
            echo "  ⚠️  API error ($http_code), using fallback\n";
            return $this->generate_fallback(basename($image_path), $mime_type, $image_path);
        }
        
        return false;
    }
    
    /**
     * Enhanced fallback system
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
        
        // Healthcare context mapping
        $healthcare_context = [
            'doctor' => ['medical professional', 'healthcare provider', 'physician'],
            'patient' => ['healthcare recipient', 'client', 'individual'],
            'team' => ['medical staff', 'healthcare professionals', 'care team'],
            'service' => ['medical service', 'healthcare solution', 'treatment'],
            'clinic' => ['medical facility', 'healthcare center', 'treatment center'],
            'care' => ['medical care', 'healthcare service', 'patient care'],
            'health' => ['wellness', 'medical', 'healthcare'],
            'treatment' => ['medical procedure', 'therapeutic service', 'care solution']
        ];
        
        // Enhance title with medical context
        $title_words = explode(' ', strtolower($name));
        $enhanced_words = [];
        
        foreach ($title_words as $word) {
            if (isset($healthcare_context[$word])) {
                $enhanced_words[] = $healthcare_context[$word][0]; // Use first synonym
            } else {
                $enhanced_words[] = $word;
            }
        }
        
        $enhanced_title = ucwords(implode(' ', $enhanced_words));
        
        // Generate description variations
        $descriptions = [
            "Professional $enhanced_title at Main Street Health medical facility",
            "$enhanced_title providing quality healthcare services at Main Street Health",
            "Main Street Health $enhanced_title for comprehensive patient care",
            "$enhanced_title in modern medical environment at Main Street Health",
            "High-quality $enhanced_title services at Main Street Health facility"
        ];
        
        return [
            'title' => $name,
            'alt_text' => $enhanced_title,
            'description' => $descriptions[array_rand($descriptions)],
            'method' => 'enhanced_fallback'
        ];
    }
    
    /**
     * Process batch of images
     */
    public function process_batch($limit = 10) {
        echo "=== AI Media Description Batch Processor ===\n";
        echo "Processing up to $limit images\n\n";
        
        if (function_exists('get_posts')) {
            // WordPress mode
            return $this->process_wordpress_batch($limit);
        } else {
            // Standalone mode
            return $this->process_standalone_batch($limit);
        }
    }
    
    private function process_wordpress_batch($limit) {
        // Get unprocessed images
        $args = [
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => $limit,
            'post_status' => 'inherit',
            'meta_query' => [
                [
                    'key' => '_ai_generated_method',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ];
        
        $attachments = get_posts($args);
        
        if (empty($attachments)) {
            echo "No unprocessed images found!\n";
            return;
        }
        
        echo "Found " . count($attachments) . " images to process\n\n";
        
        foreach ($attachments as $attachment) {
            $filename = basename(get_attached_file($attachment->ID));
            $upload_dir = wp_upload_dir();
            $file_url = wp_get_attachment_url($attachment->ID);
            $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $file_url);
            
            echo "Processing: $filename... ";
            
            $result = $this->process_with_openai($file_path);
            
            if ($result) {
                // Update WordPress attachment
                wp_update_post([
                    'ID' => $attachment->ID,
                    'post_title' => $result['title'],
                    'post_content' => $result['description']
                ]);
                
                update_post_meta($attachment->ID, '_wp_attachment_image_alt', $result['alt_text']);
                update_post_meta($attachment->ID, '_ai_generated_method', $result['method']);
                update_post_meta($attachment->ID, '_ai_generated_date', current_time('mysql'));
                
                echo "✓ " . $result['method'] . "\n";
                $this->processed_count++;
                
                // Store sample
                if (count($this->sample_results) < 3) {
                    $this->sample_results[] = [
                        'filename' => $filename,
                        'title' => $result['title'],
                        'alt_text' => $result['alt_text'],
                        'description' => $result['description'],
                        'method' => $result['method']
                    ];
                }
            } else {
                echo "❌ Failed\n";
                $this->failed_count++;
            }
            
            // Rate limiting - pause between requests
            if ($result && $result['method'] === 'openai_vision') {
                usleep(100000); // 0.1 second pause
            }
        }
        
        $this->show_summary();
    }
    
    private function process_standalone_batch($limit) {
        echo "Standalone mode - scanning upload directory\n";
        
        $upload_base = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads';
        
        // Use recursive directory iterator instead of glob for better reliability
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($upload_base)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                    $files[] = $file->getRealPath();
                }
            }
        }
        
        // Shuffle to get varied sample
        shuffle($files);
        
        if (empty($files)) {
            echo "No image files found!\n";
            return;
        }
        
        $files = array_slice($files, 0, $limit);
        echo "Found " . count($files) . " images to process\n\n";
        
        foreach ($files as $file_path) {
            $filename = basename($file_path);
            echo "Processing: $filename... ";
            
            $result = $this->process_with_openai($file_path);
            
            if ($result) {
                echo "✓ " . $result['method'] . "\n";
                $this->processed_count++;
                
                // Store sample
                if (count($this->sample_results) < 3) {
                    $this->sample_results[] = [
                        'filename' => $filename,
                        'title' => $result['title'],
                        'alt_text' => $result['alt_text'],
                        'description' => $result['description'],
                        'method' => $result['method']
                    ];
                }
            } else {
                echo "❌ Failed\n";
                $this->failed_count++;
            }
            
            // Rate limiting
            if ($result && $result['method'] === 'openai_vision') {
                usleep(100000);
            }
        }
        
        $this->show_summary();
    }
    
    private function show_summary() {
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "Processing Complete!\n";
        echo "Processed: " . $this->processed_count . " images\n";
        echo "Failed: " . $this->failed_count . " images\n\n";
        
        if (!empty($this->sample_results)) {
            echo "Sample Results:\n";
            echo str_repeat("-", 40) . "\n";
            
            foreach ($this->sample_results as $sample) {
                echo "File: " . $sample['filename'] . "\n";
                echo "Method: " . $sample['method'] . "\n";
                echo "Title: " . $sample['title'] . "\n";
                echo "Alt Text: " . $sample['alt_text'] . "\n";
                echo "Description: " . $sample['description'] . "\n";
                echo str_repeat("-", 40) . "\n";
            }
        }
    }
}

// Run if executed directly
if (php_sapi_name() === 'cli' || !isset($_SERVER['HTTP_HOST'])) {
    $processor = new Production_AI_Media_Processor();
    $processor->process_batch(10); // Process 10 images as test
}
?>
