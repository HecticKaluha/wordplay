<?php
error_reporting(E_ERROR | E_PARSE);

$word = $_POST["word"];

$curl = curl_init("https://www.welklidwoord.nl/". $word);
$fp = fopen("../assets/output.txt", "w");

curl_setopt($curl, CURLOPT_FILE, $fp);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, 0);
$result = curl_exec($curl);
if(curl_error($curl)) {
    fwrite($fp, curl_error($curl));
}
curl_close($curl);
fclose($fp);

$dom = new DOMDocument();
$dom->loadHTML($result);

$xpath = new DomXPath($dom);

$class = 'nieuwH2';
$divs = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]");

$articles = [];
foreach($divs as $div) {
    $value = trim(htmlspecialchars($div->nodeValue));
    $article = substr($value, 0,strrpos($value," "));
    array_push($articles, $article);
}

$data = [
    'data' => "test",
    'word' => $word,
    'article' => $articles,
];

echo json_encode($data);