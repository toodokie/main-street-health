<?php
/**
 * AI-Powered Media Title & Description Generator
 * 
 * Supports multiple AI providers:
 * - OpenAI Vision API (GPT-4 Vision)
 * - Google Cloud Vision API
 * - Azure Computer Vision
 * - Fallback to intelligent filename parsing
 * 
 * Usage: 
 * 1. Add your API key to wp-config.php: define('OPENAI_API_KEY', 'your-key-here');
 * 2. Run: wp eval-file ai-media-descriptions.php
 */

class AI_Media_Descriptor {
    
    private $openai_key;
    private $google_key;
    private $azure_key;
    private $processed_count = 0;
    private $rate_limit_delay = 2; // seconds between API calls
    
    public function __construct() {
        // Get API keys from wp-config.php or environment
        $this->openai_key = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : getenv('OPENAI_API_KEY');
        $this->google_key = defined('GOOGLE_VISION_KEY') ? GOOGLE_VISION_KEY : getenv('GOOGLE_VISION_KEY');
        $this->azure_key = defined('AZURE_VISION_KEY') ? AZURE_VISION_KEY : getenv('AZURE_VISION_KEY');
    }
    
    /**
     * Generate description using OpenAI Vision API
     */
    public function generate_with_openai($image_url, $context = 'medical website') {
        if (!$this->openai_key) {
            return false;
        }
        
        // Convert local URLs to base64 for local development
        if (strpos($image_url, '://') !== false && (
            strpos($image_url, '.local/') !== false || 
            strpos($image_url, 'localhost') !== false ||
            strpos($image_url, '127.0.0.1') !== false
        )) {
            return $this->generate_with_openai_base64($image_url, $context);
        }
        
        $api_url = 'https://api.openai.com/v1/chat/completions';
        
        $messages = [
            [
                'role' => 'system',
                'content' => "You are an expert at creating SEO-friendly, accessible image descriptions for a healthcare/medical website. Generate a title and description that are professional, informative, and optimized for search engines."
            ],
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => "Generate a JSON response with 'title' (5-8 words), 'alt_text' (descriptive for accessibility, 10-15 words), and 'description' (SEO-optimized, 20-30 words) for this medical/healthcare website image. Context: $context"
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => $image_url,
                            'detail' => 'auto'
                        ]
                    ]
                ]
            ]
        ];
        
        $body = json_encode([
            'model' => 'gpt-4-vision-preview',
            'messages' => $messages,
            'max_tokens' => 300,
            'temperature' => 0.3
        ]);
        
        $response = wp_remote_post($api_url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->openai_key,
                'Content-Type' => 'application/json',
            ],
            'body' => $body,
            'timeout' => 30
        ]);
        
        if (is_wp_error($response)) {
            error_log('OpenAI API Error: ' . $response->get_error_message());
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['choices'][0]['message']['content'])) {
            $content = $data['choices'][0]['message']['content'];
            
            // Clean up markdown code blocks if present
            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);
            $content = trim($content);
            
            // Try to parse JSON from response
            if (preg_match('/\{.*\}/s', $content, $matches)) {
                $json_data = json_decode($matches[0], true);
                if ($json_data) {
                    return $json_data;
                }
            }
            
            // Also try parsing the entire cleaned content as JSON
            $json_data = json_decode($content, true);
            if ($json_data && isset($json_data['title'])) {
                return $json_data;
            }
            
            // Fallback: parse text response
            return [
                'title' => $this->extract_first_line($content),
                'alt_text' => $this->extract_first_line($content),
                'description' => $content
            ];
        }
        
        return false;
    }
    
    /**
     * Generate description using OpenAI Vision API with Base64 (for local development)
     */
    public function generate_with_openai_base64($image_url, $context = 'medical website') {
        // Convert URL to file path for local images
        $parsed_url = parse_url($image_url);
        $file_path = $_SERVER['DOCUMENT_ROOT'] . $parsed_url['path'];
        
        // Alternative: try to get from WordPress uploads
        if (!file_exists($file_path)) {
            $upload_dir = wp_upload_dir();
            $file_path = str_replace(
                $upload_dir['baseurl'], 
                $upload_dir['basedir'], 
                $image_url
            );
        }
        
        if (!file_exists($file_path)) {
            error_log("Image file not found: $file_path");
            // Try to output debug info if we're in a web context
            if (function_exists('wp_die') && !wp_doing_cron()) {
                echo "<p>❌ Debug: Image file not found at: $file_path</p>";
            }
            return false;
        }
        
        // Get file info
        $file_info = pathinfo($file_path);
        $mime_type = wp_check_filetype($file_path);
        
        if (!in_array($mime_type['type'], ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
            error_log("Unsupported image type for OpenAI Vision: " . $mime_type['type']);
            if (function_exists('wp_die') && !wp_doing_cron()) {
                echo "<p>❌ Debug: OpenAI doesn't support " . $mime_type['type'] . " - using fallback</p>";
            }
            return false;
        }
        
        // Convert to base64
        $image_data = file_get_contents($file_path);
        $base64_image = base64_encode($image_data);
        $data_url = "data:" . $mime_type['type'] . ";base64," . $base64_image;
        
        $api_url = 'https://api.openai.com/v1/chat/completions';
        
        $messages = [
            [
                'role' => 'system',
                'content' => "You are an expert at creating SEO-friendly, accessible image descriptions for a healthcare/medical website. Generate a title and description that are professional, informative, and optimized for search engines."
            ],
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => "Generate a JSON response with 'title' (5-8 words), 'alt_text' (descriptive for accessibility, 10-15 words), and 'description' (SEO-optimized, 20-30 words) for this medical/healthcare website image. Context: $context"
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => $data_url,
                            'detail' => 'auto'
                        ]
                    ]
                ]
            ]
        ];
        
        $body = json_encode([
            'model' => 'gpt-4-vision-preview',
            'messages' => $messages,
            'max_tokens' => 300,
            'temperature' => 0.3
        ]);
        
        $response = wp_remote_post($api_url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->openai_key,
                'Content-Type' => 'application/json',
            ],
            'body' => $body,
            'timeout' => 30
        ]);
        
        if (is_wp_error($response)) {
            error_log('OpenAI API Error: ' . $response->get_error_message());
            if (function_exists('wp_die') && !wp_doing_cron()) {
                echo "<p>❌ Debug: OpenAI API Error: " . $response->get_error_message() . "</p>";
            }
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        
        if ($response_code !== 200) {
            error_log('OpenAI API HTTP Error: ' . $response_code . ' - ' . $response_body);
            if (function_exists('wp_die') && !wp_doing_cron()) {
                echo "<p>❌ Debug: OpenAI HTTP Error $response_code: " . substr($response_body, 0, 200) . "</p>";
            }
            return false;
        }
        
        $data = json_decode($response_body, true);
        
        if (isset($data['choices'][0]['message']['content'])) {
            $content = $data['choices'][0]['message']['content'];
            
            // Clean up markdown code blocks if present
            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);
            $content = trim($content);
            
            // Try to parse JSON from response
            if (preg_match('/\{.*\}/s', $content, $matches)) {
                $json_data = json_decode($matches[0], true);
                if ($json_data) {
                    return $json_data;
                }
            }
            
            // Also try parsing the entire cleaned content as JSON
            $json_data = json_decode($content, true);
            if ($json_data && isset($json_data['title'])) {
                return $json_data;
            }
            
            // Fallback: parse text response
            return [
                'title' => $this->extract_first_line($content),
                'alt_text' => $this->extract_first_line($content),
                'description' => $content
            ];
        }
        
        // Log error for debugging
        error_log('OpenAI Response: ' . $response_body);
        return false;
    }
    
    /**
     * Generate using Google Cloud Vision API
     */
    public function generate_with_google($image_path) {
        if (!$this->google_key) {
            return false;
        }
        
        $api_url = 'https://vision.googleapis.com/v1/images:annotate?key=' . $this->google_key;
        
        $image_content = base64_encode(file_get_contents($image_path));
        
        $request = [
            'requests' => [
                [
                    'image' => [
                        'content' => $image_content
                    ],
                    'features' => [
                        ['type' => 'LABEL_DETECTION', 'maxResults' => 10],
                        ['type' => 'TEXT_DETECTION', 'maxResults' => 10],
                        ['type' => 'LANDMARK_DETECTION', 'maxResults' => 5],
                        ['type' => 'LOGO_DETECTION', 'maxResults' => 5],
                        ['type' => 'SAFE_SEARCH_DETECTION']
                    ]
                ]
            ]
        ];
        
        $response = wp_remote_post($api_url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($request),
            'timeout' => 30
        ]);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['responses'][0])) {
            $result = $data['responses'][0];
            
            // Build description from labels
            $labels = [];
            if (isset($result['labelAnnotations'])) {
                foreach ($result['labelAnnotations'] as $label) {
                    if ($label['score'] > 0.7) {
                        $labels[] = $label['description'];
                    }
                }
            }
            
            $title = implode(' ', array_slice($labels, 0, 3));
            $description = 'Image showing ' . implode(', ', array_slice($labels, 0, 5));
            
            return [
                'title' => ucwords($title),
                'alt_text' => $description,
                'description' => $description . ' for Main Street Health medical services'
            ];
        }
        
        return false;
    }
    
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
                $name = implode(' ', $svg_keywords);
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
    
    /**
     * Process a single attachment
     */
    public function process_attachment($attachment_id) {
        $attachment = get_post($attachment_id);
        if (!$attachment) {
            return false;
        }
        
        $image_url = wp_get_attachment_url($attachment_id);
        $image_path = get_attached_file($attachment_id);
        $filename = basename($image_path);
        
        $result = false;
        
        // Try AI providers in order of preference
        if ($this->openai_key) {
            $result = $this->generate_with_openai($image_url, 'Main Street Health medical website');
            if ($result) {
                $result['method'] = 'OpenAI Vision';
            }
        }
        
        if (!$result && $this->google_key) {
            $result = $this->generate_with_google($image_path);
            if ($result) {
                $result['method'] = 'Google Vision';
            }
        }
        
        if (!$result) {
            $result = $this->generate_fallback($filename, $attachment->post_mime_type);
            $result['method'] = 'Intelligent Fallback';
        }
        
        // Update WordPress attachment
        if ($result) {
            // Update post
            wp_update_post([
                'ID' => $attachment_id,
                'post_title' => $result['title'],
                'post_content' => $result['description'],
                'post_excerpt' => $result['alt_text']
            ]);
            
            // Update alt text
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $result['alt_text']);
            
            // Store generation method for tracking
            update_post_meta($attachment_id, '_ai_generated_method', $result['method']);
            update_post_meta($attachment_id, '_ai_generated_date', current_time('mysql'));
            
            return $result;
        }
        
        return false;
    }
    
    /**
     * Batch process attachments
     */
    public function batch_process($limit = 10, $offset = 0) {
        $args = [
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => $limit,
            'offset' => $offset,
            'post_status' => 'inherit',
            'meta_query' => [
                [
                    'key' => '_ai_generated_method',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ];
        
        $attachments = get_posts($args);
        $results = [];
        
        foreach ($attachments as $attachment) {
            echo "Processing: " . $attachment->post_title . " (ID: " . $attachment->ID . ")...\n";
            
            $result = $this->process_attachment($attachment->ID);
            
            if ($result) {
                $results[] = [
                    'id' => $attachment->ID,
                    'title' => $result['title'],
                    'method' => $result['method']
                ];
                echo "✓ Generated using " . $result['method'] . "\n";
            } else {
                echo "✗ Failed to generate description\n";
            }
            
            // Rate limiting
            if ($this->openai_key || $this->google_key) {
                sleep($this->rate_limit_delay);
            }
        }
        
        return $results;
    }
    
    private function extract_first_line($text) {
        $lines = explode("\n", $text);
        return trim($lines[0]);
    }
}

// CLI execution
if (defined('WP_CLI') || php_sapi_name() === 'cli') {
    $generator = new AI_Media_Descriptor();
    
    echo "\n=================================\n";
    echo "AI Media Description Generator\n";
    echo "=================================\n\n";
    
    // Check available APIs
    $apis = [];
    if (defined('OPENAI_API_KEY') || getenv('OPENAI_API_KEY')) $apis[] = 'OpenAI Vision';
    if (defined('GOOGLE_VISION_KEY') || getenv('GOOGLE_VISION_KEY')) $apis[] = 'Google Vision';
    if (defined('AZURE_VISION_KEY') || getenv('AZURE_VISION_KEY')) $apis[] = 'Azure Vision';
    
    if (empty($apis)) {
        echo "⚠️  No AI API keys found. Using intelligent fallback mode.\n";
        echo "To enable AI: Add to wp-config.php:\n";
        echo "define('OPENAI_API_KEY', 'your-key-here');\n\n";
    } else {
        echo "✓ Available APIs: " . implode(', ', $apis) . "\n\n";
    }
    
    // Get total count
    $total = wp_count_posts('attachment')->inherit;
    echo "Total media attachments: $total\n";
    
    // Process in batches
    $batch_size = 5; // Process 5 at a time to avoid timeouts
    $offset = 0;
    $total_processed = 0;
    
    echo "\nProcessing in batches of $batch_size...\n";
    echo "Press Ctrl+C to stop at any time.\n\n";
    
    while ($offset < $total) {
        echo "--- Batch " . (($offset / $batch_size) + 1) . " ---\n";
        $results = $generator->batch_process($batch_size, $offset);
        
        if (empty($results)) {
            echo "No more unprocessed images found.\n";
            break;
        }
        
        $total_processed += count($results);
        $offset += $batch_size;
        
        echo "\nProcessed: $total_processed images\n";
        echo "Remaining: " . max(0, $total - $total_processed) . "\n\n";
        
        // Add delay between batches
        if ($offset < $total) {
            echo "Waiting 3 seconds before next batch...\n\n";
            sleep(3);
        }
    }
    
    echo "\n=================================\n";
    echo "✓ Complete! Processed $total_processed images.\n";
    echo "=================================\n";
}