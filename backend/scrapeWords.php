// <?php
//
// require('rb.php');
// require("config.php");
// require('database.php');
//
// $handle = fopen("../assets/wordlist.txt", "r");
// if ($handle) {
//     while (($line = fgets($handle)) !== false) {
//         $word = R::dispense('word');
//         if($line != ""){
//             $word->word = str_replace(array("\r", "\n"), '', $line);
//         }
//         R::store($word);
//     }
//     fclose($handle);
// }
//
// echo 'Done!';