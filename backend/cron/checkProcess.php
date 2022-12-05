<?php
require("../rb.php");
require("../config.php");
require('../database.php');

$previouswords = file_get_contents('../../assets/currentWords.txt');
$currentWords = R::getRow('SELECT count(*) as count FROM word limit 1;');

if($previouswords > $currentWords['count']){
    $myFile = fopen("../../assets/currentWords.txt", "w");
    fwrite($myFile, $currentWords['count']);
    fclose($myFile);
}
else{
    $myFile = fopen("../../assets/currentWords.txt", "w");
    fwrite($myFile, $currentWords['count']);
    fclose($myFile);
    exec('php ../curateWords.php');
}
//
//echo $previouswords;
//echo $currentWords['count'];