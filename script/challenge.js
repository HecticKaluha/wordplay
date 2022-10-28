async function fetchWordInfo(wordToFetch){
    let body = {
        "word": wordToFetch,
    };

    let formData = new FormData();
    for ( let key in body ) {
        formData.append(key, body[key]);
    }

    await fetch("http://localhost/sites/persoonlijk/wordplay/backend/getWord.php",{
        method:'POST',
        body:formData
    })
        .then(res => res.json())
        .then(data => {
            console.log(data)
            currentArticles = data['articles'];
            currentWordInfoFound = data['infoFound'];
            currentWord = data['word'];
        });
}