<?php
require("rb.php");
require("config.php");
require('database.php');

$words = R::getAssoc('SELECT * FROM word ORDER BY RAND() LIMIT 10;');

foreach($words as $id => $word){
    echo $word;
}

$data = [
    'words' => $words
];
echo json_encode($data);