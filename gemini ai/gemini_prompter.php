
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Replace with your Google Gemini API Key
$gemini_api_key = "AIzaSyB5d10R3knr29EUiRfNd_icQD9Ic9WD9ug";  // Secure this key properly

// Get user input
$user_input = isset($_POST['user_input']) ? trim($_POST['user_input']) : "";

if (empty($user_input)) {
    echo json_encode(["success" => false, "message" => "Input cannot be empty."]);
    exit;
}

// Google Gemini API Request
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$gemini_api_key";

$data = [
    "contents" => [
        ["parts" => [["text" => $user_input]]]
    ],
    "generationConfig" => [
        "temperature" => 0.7,
        "topP" => 0.95,
        "topK" => 40,
        "maxOutputTokens" => 500
    ]
];

$options = [
    "http" => [
        "header"  => "Content-type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["success" => false, "message" => "API request failed."]);
} else {
    $response_data = json_decode($response, true);
    
    if (isset($response_data["candidates"][0]["content"]["parts"][0]["text"])) {
        $generated_text = $response_data["candidates"][0]["content"]["parts"][0]["text"];
        echo json_encode(["success" => true, "message" => trim($generated_text)]);
    } else {
        echo json_encode(["success" => false, "message" => "No response from AI."]);
    }
}
?>

