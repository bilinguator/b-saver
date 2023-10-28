function saveTxt (lang1, lang2) {
    let bookID = document.querySelector('.book-id-input').value;
    let fileName = `${bookID}_${lang1}_${lang2}`;
    let fileAddress = '../b-editor/books/_saved/' + fileName + '.txt';
    let articlesArrayLeft = document.querySelector('.invisible-input-' + lang1).innerText.split('<br>');
    let articlesArrayRight = document.querySelector('.invisible-input-' + lang2).innerText.split('<br>');
    let maxArticlesCount = Math.max(articlesArrayLeft.length, articlesArrayRight.length);
    
    let txtContent = '';
    
    for (let i = 0; i < maxArticlesCount; i++) {
        if (!articlesArrayLeft[i].includes('<img') && articlesArrayLeft[i]) {
            txtContent += articlesArrayLeft[i].split('<delimiter>').join('\n') + '\n\n';
        }
        
        if (!articlesArrayRight[i].includes('<img') && articlesArrayRight[i]) {
            txtContent += articlesArrayRight[i].split('<delimiter>').join('\n') + '\n\n';
        }
    }
    
    txtContent = txtContent.split('<h1>').join('');
    txtContent = txtContent.split('</h1>').join('');
    txtContent = txtContent.split('<b>').join('');
    txtContent = txtContent.split('</b>').join('');
    
    txtContent += '\n\n';
    
    for (let i = 0; i < lastPagePhrases.length; i++) {
        txtContent += lastPagePhrases[i] + ' bilinguator.com' + '\n';
    }
    
    let now = new Date();
    txtContent += '\n' + now.getFullYear();
    
    $.ajax({
        url: "../b-editor/save-txt.php",
        type: "POST",
        data: ({text: txtContent, fileAddress: fileAddress}),
        dataType: "html"
    });

}