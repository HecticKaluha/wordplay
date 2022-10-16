<?php
error_reporting(E_ERROR | E_PARSE);

$word = $_POST["word"];
$elements = ['empty'];
$infoFound = true;

    //get new word!!

    $curl = curl_init("https://www.woorden.org/woord/". $word);
//    $curl = curl_init("https://www.welklidwoord.nl/". $word);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    $result = curl_exec($curl);
    curl_close($curl);

    $dom = new DOMDocument();
    $dom->loadHTML($result);

    $xpath = new DomXPath($dom);

//    $class = 'nieuwH2';
    $style = 'font-size:8pt';
//    $elements = $xpath->query("//*[contains(@class, '$class')]");
    $elements = $xpath->query("//*[contains(@style, '$style')]");



$articles = [];
foreach($elements as $elem) {
    $value = trim(htmlspecialchars($elem->nodeValue));
//    $article = substr($value, 0,strrpos($value," "));
    if(in_array($value, ['de', 'het'])){
        $article = $value;
        array_push($articles, $article);
    }
}
$articles = array_unique($articles);
//var_dump($articles);

$infoFound = empty($articles) ? false : true;

$data = [
    'word' => $word,
    'infoFound' => $infoFound,
    'articles' => $articles,
];

echo json_encode($data);