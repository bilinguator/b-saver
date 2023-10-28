function saveFb2 (lingua1, lingua2) {


    let now = new Date();
    let date = now.getDate() + "." + now.getMonth() + "." + now.getFullYear();

    let title1 = getTitle(lingua1);
    let title2 = getTitle(lingua2);
    
    let titleRest1 = getTitleRest(lingua1);
    let titleRest2 = getTitleRest(lingua2);
    
    let author1 = getArticlesArray(lingua1)[0];
    let author2 = getArticlesArray(lingua2)[0];
    let authorsCouple;
    let authorTitle;

    if (author1 !== '<delimiter>' && author2 !== '<delimiter>') {
        authorsCouple = author1 + ' / ' + author2;
        authorTitle = '<p>' + authorsCouple + '</p>';
    } else if (author1 !== '<delimiter>' && author2 === '<delimiter>') {
        authorsCouple = author1;
        authorTitle = '<p>' + authorsCouple + '</p>';
    } else if (author1 === '<delimiter>' && author2 !== '<delimiter>') {
        authorsCouple = author2;
        authorTitle = '<p>' + authorsCouple + '</p>';
    } else {
        authorsCouple = 'No author';
        authorTitle = '';
    }

    let set1 = getArticlesArray(lingua1);
    let set2 = getArticlesArray(lingua2);
    
    let srcLang = getLangByLingua(lingua1);
    let lang = getLangByLingua(lingua2);
    
    let fbContent = '<\?xml version="1.0" encoding="UTF-8"\?>\n';
    fbContent += '<FictionBook xmlns="http://www.gribuser.ru/xml/fictionbook/2.0" xmlns:l="http://www.w3.org/1999/xlink">\n';
    fbContent += '<description>\n    <title-info>\n        <genre>antique</genre>\n';
    fbContent += '        <author><nickname>' + authorsCouple + '</nickname></author>\n';
    fbContent += '        <book-title>' + title1 + ' / ' + title2 + '</book-title>\n';
    fbContent += '        <coverpage><image l:href="#cover"/></coverpage>\n';
    fbContent += '        <lang>' + lang + '</lang>\n';
    fbContent += '        <src-lang>' + srcLang +'</src-lang>';
    fbContent += '\n    </title-info>\n    <document-info>\n';
    fbContent += '        <author><nickname>' + authorsCouple + '</nickname></author>\n';
    fbContent += '        <program-used>B-Editor</program-used>\n        <date>' + date + '</date>\n';
    fbContent += '        <id>8a0fff4d-49af-4b54-a76c-acda3d83811d</id>\n        <version>1.0</version>\n    </document-info>\n    <publish-info>\n';
    fbContent += '		<book-name>' + title1 + ' / ' + title2 + '</book-name>\n';
    fbContent += '		<publisher>Bilinguator</publisher>\n		<year>' + now.getFullYear() + '</year>\n    </publish-info>\n</description>\n<body>\n';
    fbContent += '<title>' + authorTitle;
    fbContent += '<p>' + title1 + ' / ' + title2 + '</p></title>\n';

    if (titleRest1 !== '') {
        fbContent += '<empty-line/>\n<p>' + titleRest1 + '</p>\n';
    }
    
    if (titleRest2 !== '') {
        fbContent += '<empty-line/>\n<p>' + titleRest2 + '</p>\n';
    }
    
    let imgCount = 0;
    
    for (let i = 2; i < set1.length; i++) {
        if (set1[i].includes('<img') && set1[i] === set2[i]) {
            let imgIndex = set1[i].split('<img')[1].split('>')[0];
            fbContent += '<empty-line/>\n<p><image l:href="#' + imgIndex + '"/></p>\n';
            imgCount++;
        } else {
            if (set1[i].includes('<img')) {
                let imgIndex = set1[i].split('<img')[1].split('>')[0];
                fbContent += '<empty-line/>\n<p><image l:href="#' + imgIndex + '"/></p>\n';
                imgCount++;
            } else {
                fbContent += '<empty-line/>\n<p>' + set1[i] + '</p>\n';
            }
            
            if (set2[i].includes('<img')) {
                let imgIndex = set2[i].split('<img')[1].split('>')[0];
                fbContent += '<empty-line/>\n<p><image l:href="#' + imgIndex + '"/></p>\n';
                imgCount++;
            } else {
                fbContent += '<empty-line/>\n<p>' + set2[i] + '</p>\n';
            }
        }
    }
    
    fbContent = fbContent.split('<i>').join('<emphasis>').split('</i>').join('</emphasis>');
    fbContent = fbContent.split('</h1></p>\n<empty-line/>\n<p><h1>').join(' / ');
    fbContent = fbContent.replace('<p><h1>', '<section>\n<title>');
    fbContent = fbContent.split('<p><h1>').join('</section>\n<section>\n<title>');
    fbContent = fbContent.split('</h1></p>').join('</title>');
    fbContent += '<empty-line/>\n';
    fbContent += '<p><a l:href="https://bilinguator.com"><image l:href="#logo"/></a></p>\n<empty-line/>\n';
    for (let i = 0; i < lastPagePhrases.length; i++) {
        fbContent += '<p>' + lastPagePhrases[i] + ' <a l:href="https://bilinguator.com">bilinguator.com</a></p>\n';
    }
    fbContent += '<empty-line/>\n<p>' + now.getFullYear() + '</p>\n';
    
    fbContent += '</section>\n</body>\n';
    fbContent = fbContent.split('<delimiter>').join('</p><p>').split('<b>').join('<strong>').split('</b>').join('</strong>').split('<p></p>\n<empty-line/>\n').join('');
    
    let count1 = (fbContent.split('<section>').length - 1);
    let count2 = (fbContent.split('</section>').length - 1);
    
    if (count2 == 1 && count1 == 0) {
        fbContent = fbContent.split('</section>').join('\n');
        alert('Удалён один тег </section>.');
    }
    
    let tags = ['strong', 'section', 'title', 'p', 'body', 'emphasis'];
    let tagOpen;
    let tagClose;
    
    for (let i = 0; i < tags.length; i++) {
        tagOpen = '<' + tags[i] + '>';
        tagClose = '</' + tags[i] + '>';
        count1 = (fbContent.split(tagOpen).length - 1);
        count2 = (fbContent.split(tagClose).length - 1);
        if (count1 > count2)
            alert ('Лишний тэг ' + tagOpen + '.');
        if (count1 < count2)
            alert ('Лишний тэг ' + tagClose + '.');
    }
    
    let bookID = document.querySelector('.book-id-input').value;
    let fileAddress = `../b-editor/books/_saved/${bookID}_${lingua1}_${lingua2}.fb2`;
    
    $.ajax({
        url: "../b-editor/save-fb2.php",
        type: "POST",
        data: ({text: fbContent, fileAddress: fileAddress, linguaFirst: lingua1, imgCount: imgCount}),
        dataType: "html"
    });
}