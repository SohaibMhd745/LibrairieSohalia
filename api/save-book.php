<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

$dataFile = '../data/books.json';
$books = [];

if (file_exists($dataFile)) {
    $books = json_decode(file_get_contents($dataFile), true) ?: [];
}

$existingIndex = -1;
if (isset($input['id'])) {
    foreach ($books as $index => $book) {
        if ($book['id'] == $input['id']) {
            $existingIndex = $index;
            break;
        }
    }
}

if ($existingIndex !== -1) {
    $books[$existingIndex]['date'] = $input['date'] ?? $books[$existingIndex]['date'];
    $books[$existingIndex]['title'] = $input['title'] ?? $books[$existingIndex]['title'];
    $books[$existingIndex]['content'] = $input['content'] ?? $books[$existingIndex]['content'];
    $books[$existingIndex]['images'] = $input['images'] ?? $books[$existingIndex]['images'];
    if (isset($input['isArchived'])) {
        $books[$existingIndex]['isArchived'] = $input['isArchived'];
    }
    $savedBook = $books[$existingIndex];
} else {
    $newId = 1;
    if (!empty($books)) {
        $newId = max(array_column($books, 'id')) + 1;
    }

    $savedBook = [
        'id' => $newId,
        'date' => $input['date'] ?? date('Y-m-d'),
        'title' => $input['title'] ?? 'Untitled',
        'content' => $input['content'] ?? '',
        'images' => $input['images'] ?? [],
        'isRead' => false,
        'isArchived' => false
    ];
    $books[] = $savedBook;
}

if (file_put_contents($dataFile, json_encode($books, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'book' => $savedBook]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to write data']);
}
