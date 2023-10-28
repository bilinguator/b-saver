<?php
header('Content-Type: application/json');
$path = $_GET['path'];
$bookID = $_GET['bookID'];
$filesList = scandir($path);
$targetFilesList = '';

for ($i = 0; $i < count($filesList); $i++) {
    if (strpos($filesList[$i], $bookID) !== false) {
        $targetFilesList .= $filesList[$i] . ';';
    }
}

$targetFilesList = trim($targetFilesList, ';');

echo $targetFilesList;