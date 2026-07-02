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

if (!isset($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing book ID']);
    exit;
}

$dataFile = '../data/books.json';
if (!file_exists($dataFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Data file not found']);
    exit;
}

$books = json_decode(file_get_contents($dataFile), true);
$updated = false;

foreach ($books as &$book) {
    if ($book['id'] == $input['id']) {
        $book['isRead'] = !$book['isRead'];
        $updated = true;
        break;
    }
}

if ($updated) {
    file_put_contents($dataFile, json_encode($books, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Book not found']);
}
