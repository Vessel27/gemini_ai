<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Secure API Key in environment variables (never expose it publicly)
$gemini_api_key = "AIzaSyB5d10R3knr29EUiRfNd_icQD9Ic9WD9ug"; 

$user_input = isset($_POST['user_input']) ? trim($_POST['user_input']) : "";

if (empty($user_input)) {
    echo json_encode(["success" => false, "message" => "Input cannot be empty."]);
    exit;
}

// API URL
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$gemini_api_key";

// Gemini API request body (single request, larger response)
$data = [
    "contents" => [
        ["parts" => [["text" => $user_input]]]
    ],
    "generationConfig" => [
        "temperature" => 0.7,
        "topP" => 0.95,
        "topK" => 40,
        "maxOutputTokens" => 700  // Get more content in one request to reduce delay
    ]
];

$options = [
    "http" => [
        "header"  => "Content-type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
        "timeout" => 5  // Reduce timeout to 5 seconds for faster responses
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["success" => false, "message" => "API request failed."]);
    exit;
}

$response_data = json_decode($response, true);

// Extract AI-generated response
$generated_text = isset($response_data["candidates"][0]["content"]["parts"][0]["text"]) 
    ? trim($response_data["candidates"][0]["content"]["parts"][0]["text"]) 
    : "No response from AI.";

echo json_encode(["success" => true, "message" => $generated_text]);
?>
