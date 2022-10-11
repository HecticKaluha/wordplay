let wordElem = document.getElementById('word');
let words = ["fiets", "hond", "huis", "aarde", "eend", "gans", "hert", "tafel", "telefoon", "frietpan", "pan", "aardappel", "laptop", "werk", "stoel", "voorhoofd", "arm", "wekker", "bed"];
let currentArticle = null;
let currentWord = null;


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
            currentArticle = data['article'][0];
            });
}
function showWord(wordToShow){
    wordElem.innerText = wordToShow;
}

function decision(decision){
    if(currentArticle === "Het of de"){
        Swal.fire({
            icon: 'success',
            title: 'Goed gedaan! Je had het goed!',
            html: `Afhankelijk van de betekenis van <i>${currentWord}</i> zijn beide <b><u>${currentArticle}</u></b> goed.`,
        }).then(() => newChallenge());
    }
    else if(decision === currentArticle){
        Swal.fire({
            icon: 'success',
            title: 'Goed gedaan! Je had het goed!',
            html: `Het juist lidwoord is <b><u>${currentArticle}</u></b> <i>${currentWord}</i>`,
        }).then(() => newChallenge());
    }
    else{
        Swal.fire({
            icon: 'error',
            title: 'Oepsie... dat is het foute antwoord :(',
            html: `Het juiste antwoord zou moeten zijn <b><U>${currentArticle}</U></b> <i>${currentWord}</i>`,
        }).then(() => newChallenge());
    }

}

function newChallenge(){
    currentWord = selectRandomWord();
    fetchWordInfo(currentWord);
    showWord(currentWord);
}
newChallenge()