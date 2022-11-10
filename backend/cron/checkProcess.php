<?php
require("../rb.php");
require("../config.php");
require('../database.php');

$previouswords = file_get_contents('../../assets/currentWords.txt');
$currentWords = R::getRow('SELECT count(*) as count FROM word limit 1;');

if($previouswords == $currentWords['count']){
    exec('php ../curateWords.php');
}
else{
    $myFile = fopen("../../assets/currentWords.txt", "w");
    fwrite($myFile, $currentWords['count']);
    fclose($myFile);
}
//
//echo $previouswords;
//echo $currentWords['count'];