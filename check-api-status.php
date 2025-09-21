<?php
/**
 * Check OpenAI API Status
 */

if (!defined('OPENAI_API_KEY')) {
    $envKey = getenv('OPENAI_API_KEY');
    if ($envKey === false) {
        $envKey = '';
    }
    define('OPENAI_API_KEY', $envKey);
}

echo "=== OpenAI API Status Check ===\n\n";

// Check API key format
echo "API Key Analysis:\n";
echo "Starts with: " . substr(OPENAI_API_KEY, 0, 7) . "...\n";
echo "Length: " . strlen(OPENAI_API_KEY) . " characters\n";
echo "Format: " . (str_starts_with(OPENAI_API_KEY, 'sk-') ? '✓ Valid format' : '❌ Invalid format') . "\n\n";

// Test 1: Check available models (simple API call)
echo "Test 1: Checking available models...\n";
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.openai.com/v1/models',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . OPENAI_API_KEY,
    ],
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Status: $http_code\n";
if ($http_code === 200) {
    echo "✓ API Key is valid and working\n";
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        echo "Available models: " . count($data['data']) . "\n";
    }
} else {
    echo "❌ API Error\n";
    echo "Response: $response\n";
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 2: Check usage/billing (if available)
echo "Test 2: Checking usage...\n";
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.openai.com/v1/usage',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . OPENAI_API_KEY,
    ],
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Status: $http_code\n";
if ($http_code === 200) {
    echo "✓ Usage endpoint accessible\n";
    echo "Response: $response\n";
} else {
    echo "Usage endpoint response: $response\n";
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 3: Simple text completion (cheaper than vision)
echo "Test 3: Simple text completion test...\n";
$request = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        [
            'role' => 'user',
            'content' => 'Say "API test successful" in exactly those words.'
        ]
    ],
    'max_tokens' => 10
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
    CURLOPT_TIMEOUT => 15
]);

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Status: $http_code\n";
if ($http_code === 200) {
    echo "✓ Text completion working\n";
    $data = json_decode($response, true);
    if (isset($data['choices'][0]['message']['content'])) {
        echo "Response: " . $data['choices'][0]['message']['content'] . "\n";
    }
} else {
    echo "❌ Text completion failed\n";
    echo "Response: $response\n";
    
    // Parse error for more details
    $error_data = json_decode($response, true);
    if (isset($error_data['error'])) {
        echo "\nError Details:\n";
        echo "Type: " . ($error_data['error']['type'] ?? 'unknown') . "\n";
        echo "Code: " . ($error_data['error']['code'] ?? 'unknown') . "\n";
        echo "Message: " . ($error_data['error']['message'] ?? 'unknown') . "\n";
    }
}

echo "\n=== Recommendations ===\n";
echo "1. Check your OpenAI account billing at: https://platform.openai.com/account/billing\n";
echo "2. Verify your API usage at: https://platform.openai.com/account/usage\n";
echo "3. Check if the API key needs regeneration at: https://platform.openai.com/api-keys\n";
?>
