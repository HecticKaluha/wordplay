<?php
 require("rb.php");
 require("config.php");
 require('database.php');

header('Access-Control-Allow-Origin: '.ORIGIN);
error_reporting(E_ERROR | E_PARSE);

function addWordToCurated($curatedWord, $article){
    $article = R::findOrCreate('article', ['article' => $article]);
    $curatedWord = R::findOrCreate('curatedword', ['word' => $curatedWord]);
    $curatedWord->sharedArticleList[] = $article;
    return R::store($curatedWord);
}

do{
    //$word = $_POST["word"];
    $word = R::getRow('SELECT * FROM word where word NOT IN (SELECT word FROM curatedword) ORDER BY RAND() LIMIT 1;');
//    $word = R::getRow('SELECT * FROM curatedword ORDER BY RAND() LIMIT 1;');
    $word = $word['word'];
    R::trashAll(R::find('word', 'word = ?', [$word]));
    $elements = [];
    $infoFound = true;
    $possibleArticles = ['de', 'het'];

    $curl = curl_init("https://www.woorden.org/woord/". $word);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    $result = curl_exec($curl);
    curl_close($curl);
    try{
        $dom = new DOMDocument();
        $dom->loadHTML($result);
        $xpath = new DomXPath($dom);

        $style = 'font-size:8pt';
        $elements = $xpath->query("//*[contains(@style, '$style')]");

        $articles = [];
        foreach($elements as $elem) {
            $value = trim(htmlspecialchars($elem->nodeValue));
            if(in_array($value, $possibleArticles)){
                array_push($articles, $value);
                $key = array_search($value, $possibleArticles);
                addWordToCurated($word, $possibleArticles[$key]);
            }
        }
        $articles = array_unique($articles);

        $infoFound = empty($articles) ? false : true;
    }
    catch(Exception $e){
        return;
    }
} while(!$infoFound);

$data = [
    'word' => $word,
    'infoFound' => $infoFound,
    'articles' => $articles,
];

echo json_encode($data);