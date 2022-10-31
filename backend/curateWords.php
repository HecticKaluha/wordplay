<?php
require("rb.php");
require("config.php");
require('database.php');

header('Access-Control-Allow-Origin: '.ORIGIN);
error_reporting(E_ERROR | E_PARSE);

$possibleArticles = ['de', 'het'];
$style = 'font-size:8pt';

function addWordToCurated($curatedWord, $article){
    $article = R::findOrCreate('article', ['article' => $article]);
    $curatedWord = R::findOrCreate('curatedword', ['word' => $curatedWord]);
    $curatedWord->sharedArticleList[] = $article;
    return R::store($curatedWord);
}

$word = R::getRow('SELECT * FROM word where word NOT IN (SELECT word FROM curatedword) ORDER BY RAND() LIMIT 1;');
$word = $word['word'];

$failed = false;
$error = null;
do{
    $elements = [];
    $infoFound = false;
    sleep(1);
    $curl = curl_init("https://www.woorden.org/woord/". $word);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    try{
        $result = curl_exec($curl);
        if(curl_errno($curl))
        {
            $error = curl_error($curl);
            echo 'Curl error: ' . $error;
            $failed = true;
            break;
        }
    }
    catch(Exception $e){
        echo "error line 33" . $e;
        $failed = true;
        $error = "Couldn't exec curl request (line 44)";
    }
    finally{
        curl_close($curl);
    }

    try{
        $dom = new DOMDocument();
        $dom->loadHTML($result);
        $xpath = new DomXPath($dom);

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
//        $articles = array_unique($articles);
//        $infoFound = empty($articles) ? false : true;
    }
    catch(Exception $e){
        echo "error line 59 " . $e;
        $failed = true;
        $error = "Couldn't load DOM  from curl (line 72)";
    }
    finally{
        R::trashAll(R::find('word', 'word = ?', [$word]));
    }
    $word = R::getRow('SELECT * FROM word where word NOT IN (SELECT word FROM curatedword) ORDER BY RAND() LIMIT 1;');
    $word = $word['word'];
} while($word);

if($failed){
    echo 'Failed with error :' . $error;
}
else{
    echo 'done!';
}
