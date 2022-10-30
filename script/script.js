let wordElem = document.getElementById('word');
// let words = ["fiets", "hond", "huis", "aarde", "eend", "gans", "hert", "tafel", "telefoon", "pan", "aardappel", "laptop", "werk", "stoel", "voorhoofd", "arm", "wekker", "bed"];
// let words = ["bos"];
let currentArticles = [];
let currentWord = null;
let currentWordInfoFound = false;
let loader = document.getElementById('loader');
const URL = `http://localhost:8181/persoonlijk/wordplay/backend/`;
// const URL = `http://192.168.1.53:8181/wordplay/backend/`;

// function selectRandomWord(){
//     return words[Math.floor(Math.random() * words.length)];
// }

async function fetchWordInfo(wordToFetch){
    loader.style.visibility="visible";
    // let body = {
    //     "word": wordToFetch,
    // };
    //
    // let formData = new FormData();
    // for ( let key in body ) {
    //     formData.append(key, body[key]);
    // }

    await fetch(`${URL}getWord.php`,{
        method:'GET',
        mode: 'cors'
    })
        .then(res => res.json())
        .then(data => {
            console.log(data)
            currentArticles = data['articles'];
            currentWordInfoFound = data['infoFound'];
            currentWord = data['word'];
        });
}
function cleanWord(){
    wordElem.innerText = "";
}
function showWord(wordToShow){
    wordElem.innerText = wordToShow;
}

function decision(decision){
    if(currentArticles.length === 0){
        Swal.fire({
            icon: 'warning',
            title: 'Onbekend',
            html: `Helaas is er geen lidwoord gevonden bij het woord <b><i>${currentWord}</i></b>, echter dit woord is vanaf nu gefilterd!`,
        }).then(() => newChallenge());
    }
    else if(currentArticles.length > 1){
        Swal.fire({
            icon: 'success',
            title: 'Goed gedaan! Je had het goed!',
            html: `Afhankelijk van de betekenis van <i>${currentWord}</i> zijn beide <b><u>${currentArticles[0]}</u></b> en <b><u>${currentArticles[1]}</u></b> goed.`,
        }).then(() => newChallenge());
    }
    else if(decision.toLowerCase() === currentArticles[0]?.toLowerCase()){
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
    cleanWord();
    //check if wordinfo is found
    fetchWordInfo().then(function(){
        console.log(currentWord);
        showWord(currentWord);
        loader.style.visibility="hidden";
    });
}

newChallenge();