<?php

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get raw JSON
$input = json_decode(file_get_contents('php://input'), true);
$content = $input['content'] ?? null;

if (!$content) {
    http_response_code(400);
    echo json_encode(['error' => 'No content provided']);
    exit;
}

// Your OpenAI API key
$apiKey = '';

$data = [
    "model" => "gpt-4.1-nano-2025-04-14",
    "messages" => [
        ["role" => "system", "content" => "Generate tags for the note content as a comma-separated list."],
        ["role" => "user", "content" => $content]
    ],
    "temperature" => 0.7
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);
$json = json_decode($response, true);

$tags = $json['choices'][0]['message']['content'] ?? '';

echo json_encode(['tags' => array_map('trim', explode(',', $tags))]);
