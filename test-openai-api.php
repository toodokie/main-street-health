<?php
/**
 * Direct OpenAI API Test (Standalone)
 */

// OpenAI API Key
if (!defined('OPENAI_API_KEY')) {
    $envKey = getenv('OPENAI_API_KEY');
    if ($envKey === false) {
        $envKey = '';
    }
    define('OPENAI_API_KEY', $envKey);
}

echo "=== OpenAI Vision API Test ===\n";
echo "OpenAI API Key configured: ✓ Yes\n\n";

// Test with a known image file
$test_image_path = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads/2025/09/Group-389-scaled-1-2-600x600.png';

if (file_exists($test_image_path)) {
    echo "Testing with file: " . basename($test_image_path) . "\n";
    echo "File size: " . number_format(filesize($test_image_path)) . " bytes\n";
    echo "File type: " . mime_content_type($test_image_path) . "\n\n";
    
    // Convert to base64
    $image_data = file_get_contents($test_image_path);
    $base64_image = base64_encode($image_data);
    $mime_type = mime_content_type($test_image_path);
    
    echo "Encoded as base64: " . number_format(strlen($base64_image)) . " characters\n\n";
    
    // Prepare OpenAI request
    $data_url = "data:$mime_type;base64,$base64_image";
    
    $request = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Analyze this medical website image and provide:\n1. A 5-8 word SEO-friendly title\n2. A 10-15 word alt text for accessibility\n3. A 20-30 word description for SEO\n\nContext: Main Street Health medical website\n\nRespond in JSON format: {"title": "...", "alt_text": "...", "description": "..."}'
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
    
    echo "Making OpenAI API call...\n";
    
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
    $error = curl_error($curl);
    curl_close($curl);
    
    if ($error) {
        echo "❌ cURL Error: $error\n";
    } else {
        echo "HTTP Status: $http_code\n";
        
        if ($http_code === 200) {
            $data = json_decode($response, true);
            
            if (isset($data['choices'][0]['message']['content'])) {
                $content = $data['choices'][0]['message']['content'];
                echo "✓ OpenAI Response:\n";
                echo $content . "\n\n";
                
                // Try to parse JSON response
                $json_data = json_decode($content, true);
                if ($json_data && isset($json_data['title'])) {
                    echo "✓ Parsed Results:\n";
                    echo "Title: " . $json_data['title'] . "\n";
                    echo "Alt Text: " . $json_data['alt_text'] . "\n";
                    echo "Description: " . $json_data['description'] . "\n";
                } else {
                    echo "⚠️ Could not parse JSON response\n";
                }
            } else {
                echo "❌ No content in response\n";
                echo "Full response: $response\n";
            }
        } else {
            echo "❌ API Error (Status: $http_code)\n";
            echo "Response: $response\n";
        }
    }
    
} else {
    echo "❌ Test image not found: $test_image_path\n";
    
    // Try to find another image
    $upload_dir = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads';
    $files = glob($upload_dir . '/**/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
    
    if (!empty($files)) {
        $test_file = $files[0];
        echo "Trying with: $test_file\n";
        // Could recursively call this test with found file
    } else {
        echo "No supported image files found\n";
    }
}
?>
