<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$dataFile = '../data/books.json';

if (!file_exists($dataFile)) {
    echo json_encode([]);
    exit;
}

$data = file_get_contents($dataFile);
$books = json_decode($data, true) ?: [];

if (!isset($_GET['all']) || $_GET['all'] !== '1') {
    $books = array_values(array_filter($books, function($book) {
        return empty($book['isArchived']);
    }));
}

echo json_encode($books);
