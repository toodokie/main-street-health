<?php
/**
 * Analyze Specific Image with OpenAI Vision
 */

if (!defined('OPENAI_API_KEY')) {
    $envKey = getenv('OPENAI_API_KEY');
    if ($envKey === false) {
        $envKey = '';
    }
    define('OPENAI_API_KEY', $envKey);
}

$image_path = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads/2025/09/acupucture-scaled.png';

echo "=== OpenAI Vision Analysis for acupucture-scaled.png ===\n\n";

if (!file_exists($image_path)) {
    die("Image not found at: $image_path\n");
}

echo "File found: " . basename($image_path) . "\n";
echo "File size: " . number_format(filesize($image_path)) . " bytes\n";
echo "MIME type: " . mime_content_type($image_path) . "\n\n";

// Convert to base64
$image_data = file_get_contents($image_path);
$base64_image = base64_encode($image_data);
$mime_type = mime_content_type($image_path);
$data_url = "data:$mime_type;base64,$base64_image";

echo "Processing with OpenAI Vision API...\n\n";

$request = [
    'model' => 'gpt-4o-mini',
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                [
                    'type' => 'text',
                    'text' => 'Analyze this medical/healthcare website image and provide SEO-optimized metadata:

1. A 5-8 word SEO-friendly title (what would people search for?)
2. A 10-15 word alt text for accessibility (describe what you see)
3. A 20-30 word description for SEO (include Main Street Health context)

Context: This is for Main Street Health medical website. Focus on what the image shows and its medical/healthcare relevance.

Respond ONLY with valid JSON in this exact format:
{"title": "...", "alt_text": "...", "description": "..."}'
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
        
        echo "✓ OpenAI Response:\n";
        echo $content . "\n\n";
        
        // Clean up markdown formatting
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/\s*```/', '', $content);
        $content = trim($content);
        
        // Parse JSON
        $json_data = json_decode($content, true);
        
        if ($json_data && isset($json_data['title'], $json_data['alt_text'], $json_data['description'])) {
            echo "=== PROPER SEO METADATA ===\n";
            echo "Title: " . $json_data['title'] . "\n";
            echo "Alt Text: " . $json_data['alt_text'] . "\n";
            echo "Description: " . $json_data['description'] . "\n\n";
            
            echo "=== WordPress Update Fields ===\n";
            echo "Title field: " . $json_data['title'] . "\n";
            echo "Alternative Text field: " . $json_data['alt_text'] . "\n";
            echo "Description field: " . $json_data['description'] . "\n";
        } else {
            echo "❌ Could not parse JSON response\n";
            echo "Raw content: $content\n";
        }
    } else {
        echo "❌ No content in response\n";
        echo "Full response: $response\n";
    }
} else {
    echo "❌ API Error (Status: $http_code)\n";
    echo "Response: $response\n";
    
    if ($http_code === 429) {
        echo "\n💡 API quota exceeded. The generic fallback metadata is:\n";
        echo "Title: Frame 330 1 3 1\n";
        echo "Alt Text: Frame 330 1 3 1\n";
        echo "Description: Frame 330 1 3 1 in modern medical environment at Main Street Health\n";
    }
}
?>
