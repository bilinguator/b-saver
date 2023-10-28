function saveEpub (lingua1, lingua2) {

    let author1 = getAuthor(lingua1);
    let author2 = getAuthor(lingua2);

    if (author1 !== '<delimiter>' && author2 !== '<delimiter>') {
        authorsCouple = author1 + ' / ' + author2;
    } else if (author1 !== '<delimiter>' && author2 === '<delimiter>') {
        authorsCouple = author1;
    } else if (author1 === '<delimiter>' && author2 !== '<delimiter>') {
        authorsCouple = author2;
    } else {
        authorsCouple = '';
    }
    
    let title1 = getTitle(lingua1);
    let title2 = getTitle(lingua2);
    
    let set1 = getArticlesArray(lingua1);
    let set2 = getArticlesArray(lingua2);
    
    let lang1 = getLangByLingua(lingua1);
    let lang2 = getLangByLingua(lingua2);
    
    //Номера абзацев с заголовками
    let h1Array = [];
    
    for (let i = 2; i < set1.length; i++) {
        if (set1[i].includes('<h1>') && set1[i].includes('</h1>'))
            h1Array.push(i);
    }
    h1Array.push(set1.length);
    
    let chaptersArray1 = [];
    let chaptersArray2 = [];
    let ii = 0;
    
    if (h1Array.length > 1) {
        for (let i = 0; i < h1Array.length - 1; i++) {
            chaptersArray1[i] = set1.slice(h1Array[i], h1Array[i + 1]);
            chaptersArray2[i] = set2.slice(h1Array[i], h1Array[i + 1]);
        }
        if (h1Array[0] != 2) {
            ii = 1;
            chaptersArray1.unshift(set1.slice(2, h1Array[0]));
            chaptersArray2.unshift(set2.slice(2, h1Array[0]));
            h1Array.unshift(2);
        }
    }
    
    if (h1Array.length == 1) {
        chaptersArray1[0] = set1.slice(2, set1.length);
        chaptersArray2[0] = set2.slice(2, set2.length);
        ii = 1;
    }
    
    let coverhtml = '<html xmlns="http://www.w3.org/1999/xhtml">\n<head>\n	<title>' + title1 + ' / ' + title2 + '</title>\n	<link rel="stylesheet" href="style.css" type="text/css" />\n</head>\n<body>\n	<h1>' + title1 + ' / ' + title2 + '</h1>\n	<img src="cover.png">\n</body>\n</html>';
    
    let chaptershtml = [];
    let imgCount = 0;
    
    //Если в первой главе нет заголовка,..
    if (ii == 1) {
        chaptershtml[0] = '<html xmlns="http://www.w3.org/1999/xhtml" lang="' + lang1 + '">\n<head>\n	<title></title>\n	<link rel="stylesheet" href="style.css" type="text/css" />\n</head>\n<body>\n';
        
        for (let i = 0; i < chaptersArray1[0].length; i++) {
            
            if (chaptersArray1[0][i].includes('<img') && chaptersArray1[0][i] === chaptersArray2[0][i]) {
                let imgIndex = chaptersArray1[0][i].split('<img')[1].split('>')[0];
                chaptershtml[0] += '	<img src="' + imgIndex + '.png">\n	<br />\n';
                imgCount++;
            } else {
                if (chaptersArray1[0][i].includes('<img')) {
                    let imgIndex = chaptersArray1[0][i].split('<img')[1].split('>')[0];
                    chaptershtml[0] += '	<img src="' + imgIndex + '.png">\n	<br />\n';
                    imgCount++;
                } else {
                    chaptershtml[0] += '	<p lang="' + lang1 + '">' + chaptersArray1[0][i] + '</p>\n	<br />\n';
                }
            
                if (chaptersArray2[0][i].includes('<img')) {
                    let imgIndex = chaptersArray2[0][i].split('<img')[1].split('>')[0];
                    chaptershtml[0] += '	<img src="' + imgIndex + '.png">\n	<br />\n';
                    imgCount++;
                } else {
                    chaptershtml[0] += '	<p lang="' + lang2 + '">' + chaptersArray2[0][i] + '</p>\n	<br />\n';
                }
            }
            
        }
        chaptershtml[0] += '</body>\n</html>';
    }
    
    let t;
    for (let i = ii; i < chaptersArray1.length; i++) {
        t = chaptersArray1[i][0].split('<h1>').join('').split('</h1>').join('') + ' / ' + chaptersArray2[i][0].split('<h1>').join('').split('</h1>').join('');
        chaptershtml[i] = '<html xmlns="http://www.w3.org/1999/xhtml">\n<head>\n	<title>' + t + '</title>\n	<link rel="stylesheet" href="style.css" type="text/css" />\n</head>\n<body>\n	<h1>' + t + '</h1>\n	<br />\n';
        
        for (let j = 1; j < chaptersArray1[i].length; j++) {
            if (chaptersArray1[i][j].includes('<img') && chaptersArray1[i][j] === chaptersArray2[i][j]) {
                let imgIndex = chaptersArray1[i][j].split('<img')[1].split('>')[0];
                chaptershtml[i] += '	<img src="' + imgIndex + '.png">\n	<br />\n';
                imgCount++;
            } else {
                if (chaptersArray1[i][j].includes('<img')) {
                    let imgIndex = chaptersArray1[i][j].split('<img')[1].split('>')[0];
                    chaptershtml[i] += '	<img src="' + imgIndex + '.png">\n	<br />\n';
                    imgCount++;
                } else {
                    chaptershtml[i] += '	<p lang="' + lang1 + '">' + chaptersArray1[i][j] + '</p>\n	<br />\n';
                }
            
                if (chaptersArray2[i][j].includes('<img')) {
                    let imgIndex = chaptersArray2[i][j].split('<img')[1].split('>')[0];
                    chaptershtml[i] += '	<img src="' + imgIndex + '.png">\n	<br />\n';
                    imgCount++;
                } else {
                    chaptershtml[i] += '	<p lang="' + lang2 + '">' + chaptersArray2[i][j] + '</p>\n	<br />\n';
                }
            }
            
        }
        
        chaptershtml[i] += '</body>\n</html>';
    }
    
    //Удаление <delimiter>
    for (let i = 0; i < chaptershtml.length; i++) {
        chaptershtml[i] = chaptershtml[i].split('\n	<p><delimiter></p>\n').join('\n');
        chaptershtml[i] = chaptershtml[i].split('<delimiter>').join('<br />');
    }
    
    chaptershtml[chaptershtml.length - 1] = chaptershtml[chaptershtml.length - 1].split('</body>\n</html>').join('<br />\n	<a href="https://bilinguator.com"><img src="logo.png"></a>\n	<br />');
    
    for (let i = 0; i < lastPagePhrases.length; i++) {
        chaptershtml[chaptershtml.length - 1] += '\n	<p>' + lastPagePhrases[i] + ' <a href="https://bilinguator.com">bilinguator.com</a></p>\n';
    }
    
    let now = new Date();
    chaptershtml[chaptershtml.length - 1] += '	<br />\n	<p>' + now.getFullYear() + '</p>\n';
    chaptershtml[chaptershtml.length - 1] += '</body>\n</html>';
    
    
    chaptershtml[0] = chaptershtml[0].split('<body>\n').join('<body>\n	<p>' + getTitleRest(lingua1) + '</p>\n	<br />\n	<p>' + getTitleRest(lingua2) + '</p>\n	<br />\n');
    
    for (let i = 0; i < chaptershtml.length; i++) {
        chaptershtml[i] = chaptershtml[i].split('	<p></p>\n	<br />\n').join('');
    }
    
    
    //toc.ncx
    let date = now.getFullYear() + '-' + now.getMonth() + "-" + now.getDate();
    let ident = 'B-LING-' + date + '-' + now.getHours() + '-' + now.getMinutes();
    
    let tocncx = '<\?xml version="1.0" encoding="utf-8"\?>\n<!DOCTYPE ncx PUBLIC "-//NISO//DTD ncx 2005-1//EN" "http://www.daisy.org/z3986/2005/ncx-2005-1.dtd">\n<ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1">\n';
    tocncx += '	<head>\n		<meta name="dtb:uid" content="' + ident + '"/>\n		<meta name="dtb:depth" content="1"/>\n		<meta name="dtb:totalPageCount" content="0"/>\n		<meta name="dtb:maxPageNumber" content="0"/>\n	</head>\n';
    tocncx += '	<docTitle>\n		<text>' + title1 + ' / ' + title2 + '</text>\n	</docTitle>\n';
    tocncx += '	<navMap>\n		<navPoint id="point-1" playOrder="1">\n';
    tocncx += '			<navLabel>\n				<text>' + title1 + ' / ' + title2 + '</text>\n			</navLabel>\n';
    tocncx += '			<content src="cover.html"/>\n		</navPoint>\n';

    let num = 1;
    for (let i = ii; i < chaptersArray1.length; i++) {
        num++;
        tocncx += '		<navPoint id="point-' + num + '" playOrder="' + num + '">\n			<navLabel>\n';
        tocncx += '				<text>' + chaptersArray1[i][0].split('<h1>').join('').split('</h1>').join('') + ' / ' + chaptersArray2[i][0].split('<h1>').join('').split('</h1>').join('') +'</text>\n';
        tocncx += '			</navLabel>\n			<content src="chapter' + (i + 1) + '.html"/>\n		</navPoint>\n';
    }
    
    tocncx += '	</navMap>\n</ncx>';
    
    
    //content.opf
    let contentopf = '<\?xml version="1.0" encoding="UTF-8"\?>\n<package xmlns="http://www.idpf.org/2007/opf" unique-identifier="BookId" version="2.0">\n';
    contentopf += '	<metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:opf="http://www.idpf.org/2007/opf">\n';
    contentopf += '		<dc:title>' + title1 + ' / ' + title2 + '</dc:title>\n';
    contentopf += '		<dc:creator>' + authorsCouple + '</dc:creator>\n';
    contentopf += '		<dc:identifier id="BookId">' + ident + '</dc:identifier>\n';
    contentopf += '		<dc:date>' + date + '</dc:date>\n';
    contentopf += '		<dc:publisher>Bilinguator</dc:publisher>\n';
    contentopf += '		<dc:language></dc:language>\n';
    contentopf += '		<dc:language></dc:language>\n';
    contentopf += '		<meta name="cover" content="cover-image"/>\n	</metadata>\n';
    contentopf += '	<manifest>\n		<item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml" />\n';
    contentopf += '		<item id="cover-image" href="cover.png" media-type="image/png" />\n';
    contentopf += '		<item id="cover" href="cover.html" media-type="application/xhtml+xml" />\n';
    contentopf += '		<item id="style" href="style.css" media-type="text/css" />\n';

    for (let i = 0; i < chaptersArray1.length; i++) {
        contentopf += '		<item id="chapter' + (i + 1) + '" href="chapter' + (i + 1) + '.html" media-type="application/xhtml+xml" />\n';
    }
    
    for (let i = 1; i <= imgCount; i++) {
        contentopf += '		<item id="picture' + i + '" href="' + i + '.png" media-type="image/png" />\n';
    }
    
    contentopf += '		<item id="logo" href="logo.png" media-type="image/png" />\n	</manifest>\n	<spine toc="ncx">\n		<itemref idref="cover" linear="no" />\n';
    
    for (let i = 0; i < chaptersArray1.length; i++) {
        contentopf += '		<itemref idref="chapter' + (i + 1) + '" />\n';
    }
    
    contentopf += '	</spine>\n	<guide>\n		<reference href="cover.html" title="Cover" type="cover"/>\n	</guide>\n</package>';

    let bookID = document.querySelector('.book-id-input').value;
    let fileName = `${bookID}_${lingua1}_${lingua2}`;
    let dirAddress = `../b-editor/books/_saved/${fileName}/`;
    
        $.ajax({
        url: "../b-editor/save-epub.php",
        type: "POST",
        data: ({dirAddress: dirAddress, fileName: fileName, contentopf: contentopf, tocncx: tocncx, coverhtml: coverhtml, chaptershtml: chaptershtml, linguaFirst: lingua1, imgCount: imgCount}),
        dataType: "html"
    });
}