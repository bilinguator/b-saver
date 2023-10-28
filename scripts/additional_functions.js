function getArticlesArray (lingua) {
    let articles = document.querySelector('.invisible-input-' + lingua);

    if (articles) {
        return articles.innerText.split('<br>');
    } else {
        return false;
    }
}

function getAuthor (lingua) {
    let authorArray = getArticlesArray(lingua)[0].split(",");
    let authorSplit = [];
    let author = "";
    for (let i = 0; i < authorArray.length; i++) {
        authorSplit = authorArray[i].split(" ");
        author = author + authorSplit[authorSplit.length - 1];
        for (let j = 0; j < authorSplit.length - 1; j++) {
            author = author.trim() + ' ' + authorSplit[j];
        }
        if (i !== authorArray.length - 1) {
            author = author +  ", ";
        }
    }
    
    return author;
}

function getTitle (lingua) {
    let titleArray = getArticlesArray(lingua)[1].split("<delimiter>");
    let title = titleArray[0].split("<h1>").join("").split("</h1>").join("");
    return title;
}

function getTitleRest (lingua) {
    let titleRest = getArticlesArray(lingua)[1].split("<delimiter>")[1];
    
    if (!titleRest) {
        titleRest = '';
    }
    
    return titleRest;
}