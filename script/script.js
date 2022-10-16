let wordElem = document.getElementById('word');
let words = ["fiets", "hond", "huis", "aarde", "eend", "gans", "hert", "tafel", "telefoon", "pan", "aardappel", "laptop", "werk", "stoel", "voorhoofd", "arm", "wekker", "bed"];
// let words = ["bos"];
let currentArticles = [];
let currentWord = null;
let currentWordInfoFound = false;

function selectRandomWord(){
    return words[Math.floor(Math.random() * words.length)];
}

function fetchWordInfo(wordToFetch){
    let body = {
        "word": wordToFetch,
    };

    let formData = new FormData();
    for ( let key in body ) {
        formData.append(key, body[key]);
    }

    fetch("http://localhost/sites/persoonlijk/wordplay/backend/getWord.php",{
        method:'POST',
        body: formData,
    })
        .then((res) => res.json())
        .then(data => {
            console.log(data)
            currentArticles = data['articles'];
            currentWordInfoFound = data['infoFound'];
            });
}
function showWord(wordToShow){
    wordElem.innerText = wordToShow;
}

function decision(decision){
    console.log(currentArticles);
    if(currentArticles.length > 1){
        Swal.fire({
            icon: 'success',
            title: 'Goed gedaan! Je had het goed!',
            html: `Afhankelijk van de betekenis van <i>${currentWord}</i> zijn beide <b><u>${currentArticles[0]}</u></b> en <b><u>${currentArticles[1]}</u></b> goed.`,
        }).then(() => newChallenge());
    }
    else if(decision.toLowerCase() === currentArticles[0].toLowerCase()){
        Swal.fire({
            icon: 'success',
            title: 'Goed gedaan! Je had het goed!',
            html: `Het juist lidwoord is <b><u>${currentArticles[0]}</u></b> <i>${currentWord}</i>`,
        }).then(() => newChallenge());
    }
    else{
        Swal.fire({
            icon: 'error',
            title: 'Oepsie... dat is het foute antwoord :(',
            html: `Het juiste antwoord zou moeten zijn <b><U>${currentArticles[0]}</U></b> <i>${currentWord}</i>`,
        }).then(() => newChallenge());
    }

}

function newChallenge(){
    currentWord = selectRandomWord();
    fetchWordInfo(currentWord);
    showWord(currentWord);
}

newChallenge()