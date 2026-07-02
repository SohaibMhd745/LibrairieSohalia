<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$dataFile = '../data/books.json';

if (!file_exists($dataFile)) {
    echo json_encode([]);
    exit;
}

$data = file_get_contents($dataFile);
echo $data;
