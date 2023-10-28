function getAuthorTitleTable (lingua1, lingua2) {
    let authorLeft = getArticlesArray(lingua1)[0].trim();
    let authorRight = getArticlesArray(lingua2)[0].trim();
    
    let lang1 = getLangByLingua(lingua1);
    let lang2 = getLangByLingua(lingua2);
    
    let authorTitleTable = '<div class="author-title-table">';
    authorTitleTable += '<div lang="' + lang1 + '" class="author-title-table-row author-title-table-row-left"><p class="author-title-table-column">' + authorLeft + '</p><b class = "bold title">' + getTitle(lingua1) + '</b></div>';
    authorTitleTable += '<div lang="' + lang2 + '" class="author-title-table-row"><p class="author-title-table-column">' + authorRight + '</p><b class="bold title">' + getTitle(lingua2) + '</b></div></div>';
    return authorTitleTable;
}

function openReadyBook (lingua1, lingua2, mode = 'cols') {

    let lang1 = getLangByLingua(lingua1);
    let lang2 = getLangByLingua(lingua2);
    
    let articlesArrayLeft = getArticlesArray(lingua1);
    let articlesArrayRight = getArticlesArray(lingua2);
    let maxArticlesCount = Math.max(articlesArrayLeft.length, articlesArrayRight.length);
    
    let titleRestLeft = getTitleRest(lingua1);
    let titleRestRight = getTitleRest(lingua2);
    
    let readyBook = '<div class="ready-book">';
    
    if (titleRestLeft !== '' || titleRestRight !== '') {
        readyBook += '<div lang="' + lang1 + '" class="column left-column">' + titleRestLeft.split('<delimiter>').join('<br class="br">') + '</div>';
        readyBook += '<div lang="' + lang2 + '" class="column right-column">' + titleRestRight.split('<delimiter>').join('<br class="br">') + '</div>';
    }
    
    for (let i = 2; i < maxArticlesCount; i++) {
        if (articlesArrayLeft[i].includes('<img') && articlesArrayLeft[i] === articlesArrayRight[i]) {
            let imgIndex = articlesArrayLeft[i].split('<img')[1].split('>')[0];
            readyBook += '<div class="column ready-book-img-container ready-book-' + mode + '-img"><img class="ready-book-img" src="books/illustrations/' + imgIndex + '.png"></div>';
        
        } else if (articlesArrayLeft[i].includes('<img')) {
            let imgIndex = articlesArrayLeft[i].split('<img')[1].split('>')[0];
            readyBook += '<div class="column left-column"><img src="books/illustrations/' + imgIndex + '.png"></div>';
            readyBook += '<div lang="' + lang2 + '" class="column right-column">' + articlesArrayRight[i].split('<delimiter>').join('<br class="br">') + '</div>';
        
        } else if (articlesArrayRight[i].includes('<img')) {
            let imgIndex = articlesArrayRight[i].split('<img')[1].split('>')[0];
            readyBook += '<div lang="' + lang1 + '" class="column left-column">' + articlesArrayLeft[i].split('<delimiter>').join('<br class="br">') + '</div>';
            readyBook += '<div class="column right-column"><img src="books/illustrations/' + imgIndex + '.png"></div>';
        } else {
            let h1LeftClass = articlesArrayLeft[i].includes('<h1>') ? ' column-heading' : '';
            let h1RightClass = articlesArrayRight[i].includes('<h1>') ? ' column-heading' : '';
            readyBook += '<div lang="' + lang1 + '" class="column left-column' + h1LeftClass + '">' + articlesArrayLeft[i].split('<delimiter>').join('<br class="br">') + '</div>';
            readyBook += '<div lang="' + lang2 + '" class="column right-column' + h1RightClass + '">' + articlesArrayRight[i].split('<delimiter>').join('<br class="br">') + '</div>';
        }
    }

    readyBook += '</div>';
    
    let bookID = document.querySelector('.book-id-input').value;
    let fileName = `${bookID}_${lingua1}_${lingua2}_${mode}`;
    
    let readyBookWindow = window.open();
    readyBookWindow.document.title = fileName;
    readyBookWindow.document.write('<title>' + fileName + '</title><link rel="stylesheet" type="text/css" href="../b-editor/css/ready_book_style_' + mode + '.css" />');
    readyBookWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"></link>');
    readyBookWindow.document.write('<div class="print-page cover-print-page"><img class="ready-book-cover" src="../b-editor/books/_covers/' + lingua1 + '.png" /></div><div class="print-page author-title-print-page">'  + getAuthorTitleTable(lingua1, lingua2) + '</div>' + readyBook);
    readyBookWindow.document.write('<script type="text/javascript" src="../b-editor/scripts/copy_to_buffer.js"></scr' + 'ipt>');
    readyBookWindow.document.write('<script type="text/javascript" src="../b-editor/scripts/create_element.js"></scr' + 'ipt>');
    readyBookWindow.document.write('<script type="text/javascript" src="../b-editor/scripts/process_ready_book_' + mode +'.js"></scr' + 'ipt>');
    readyBookWindow.document.write('<script type="text/javascript" src="../b-editor/scripts/runnings.js"></scr' + 'ipt>');
}