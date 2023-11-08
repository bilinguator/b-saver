<?php
    $booksPath = 'books/';
    $bookFiles = array_diff(scandir($booksPath), ['.', '..']);
    foreach($bookFiles as $key => $value) {
        if (substr($value, -4) !== '.txt') {
            unset($bookFiles[$key]);
        }
    }

    $bookIDArray = Array();

    foreach ($bookFiles as $kay => $value) {
        $fileName = explode('.txt', $value)[0];
        $fileBase = explode(' ', $fileName)[0];
        $lastUnderscoreIndex = strrpos($fileBase, '_');
        $fileLingua = substr($fileBase, $lastUnderscoreIndex + 1);
        $fileID = substr($fileBase, 0, strlen($fileBase) - strlen($fileLingua) - 1);

        if (!@$bookIDArray[$fileID]) {
            $bookIDArray[$fileID] = Array();
        }

        array_push($bookIDArray[$fileID], $fileLingua);
    }

    $bookLinguasArray = max($bookIDArray);
    $bookID = array_search($bookLinguasArray, $bookIDArray);
    
    $fileNamesArray = Array();
    foreach($bookFiles as $value) {
        if (strpos($value, $bookID) !== false) {
            $fileName = explode('.txt', $value)[0];
            $fileBase = explode(' ', $fileName)[0];
            $lastUnderscoreIndex = strrpos($fileBase, '_');
            $lingua = substr($fileBase, $lastUnderscoreIndex + 1);
            $fileNamesArray[$lingua] = $value;
        }
    }

    $linguaCoversArray = Array();
    foreach ($bookLinguasArray as $lingua) {
        if (file_exists($booksPath . 'covers/' . $lingua . '.png')){
            $linguaCoversArray[$lingua] = true;
        } else {
            $linguaCoversArray[$lingua] = false;
        }
    }

?>
<html>
    <head>
        <title>B-Saver</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="shortcut icon" href="img/icon.svg" />
        <link rel="preconnect" href="https://fonts.gstatic.com">
	    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
        <script defer src="scripts/jquery-3.6.0.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"></link>
        <script type="text/javascript" src="print-bilingual-pdf/scripts/print_bilingual_pdf.js"></script>
    </head>

    <body>
        <input type="text" class="invisible-input invisible-input-general">
        <div class="sidebar">
            <a href="." class="logo-link">
                <img src="img/logo.svg" alt="B-Saver" class="logo">
            </a>
            <div class="panel panel-main">
                <div class="subpanel subpanel-book-id">
                    <label class="book-id-label">
                        Book ID
                        <input type="text" class="book-id-input" id="book-id" value="<?=$bookID?>">
                    </label>
                    <img src="img/launch.svg" alt="Launch" class="launch main-launch">
                </div>
                <div class="subpanel subpanel-formats-checkboxes">
                    <?php
                        foreach(['txt', 'fb2', 'epub', 'pdf-rows', 'pdf-cols'] as $format):
                    ?>
                        <label for="<?=$format?>" class="format-label">
                            <?=$format?>
                            <input type="checkbox" class="checkbox format-checkbox format-checkbox-<?=$format?>" id="<?=$format?>" checked>
                        </label>
                    <?php
                        endforeach;
                    ?>
                </div>
                <div class="subpanel subpanel-update-info">
                    <img src="img/update.svg" alt="Update" class="update-img">
                    <p class="info languages-info">
                    </p>
                    <p class="info couples-info">
                    </p>
                    <p class="info files-info">
                    </p>
                </div>

            </div>
            <?php
                foreach($linguaCoversArray as $lingua => $isCover):
                    $cover = $isCover ? 'src="img/cover.svg" alt="Cover is there"' : 'src="img/alarm.svg" alt="No cover"';
                    $coverMessage = $isCover ? 'cover is there' : 'no cover';
            ?>
                <div class="panel lingua-panel">
                    <label for="<?=$lingua?>" class="lingua-label">
                        <input type="checkbox" class="checkbox lingua-checkbox lingua-checkbox-<?=$lingua?>" id="<?=$lingua?>" title="<?=$fileNamesArray[$lingua]?>" checked>
                        <?=$lingua?>
                    </label>
                    <div class="cover-container">
                        <img class="cover-img" <?=$cover?>>
                        <p class="cover-message"><?=$coverMessage?></p>
                    </div>
                    <img src="img/launch.svg" alt="Launch" class="launch lingua-launch" lingua="<?=$lingua?>">
                </div>
            <?php
                endforeach;
            ?>
        
        </div>

        <div class="result-field">

        </div>
    </body>
</html>
<script type="text/javascript">
    const booksDir = 'books';
    const coversDir = 'books/covers';
    const illustrationsDir = 'books/illustrations';

    function updateLinguasCount () {
        let linguasCount = document.querySelectorAll('.lingua-checkbox').length;
        document.querySelector('.languages-info').innerHTML = linguasCount + ' languages';
    }
    updateLinguasCount();

    function getCouplesCount() {
        let allLinguaCheckboxes = document.querySelectorAll('.lingua-checkbox');
        let allLinguaCheckboxesCount = document.querySelectorAll('.lingua-checkbox').length;

        let checkedLinguaCheckboxesCount = 0;
        allLinguaCheckboxes.forEach(element => {
            if (element.checked) {
                checkedLinguaCheckboxesCount++;
            }
        });

        let uncheckedLinguaCheckboxesCount = allLinguaCheckboxesCount - checkedLinguaCheckboxesCount;
        let allPossibleCouplesCount = allLinguaCheckboxesCount * (allLinguaCheckboxesCount - 1);
        let actualCouplesCount = allPossibleCouplesCount - uncheckedLinguaCheckboxesCount * (uncheckedLinguaCheckboxesCount - 1);

        return actualCouplesCount;
    }
    
    function updateCouplesCount () {
        document.querySelector('.couples-info').innerHTML = getCouplesCount() + ' couples';
    }
    updateCouplesCount();

    function updateFilesCount () {
        let couplesCount = getCouplesCount();
        
        let allFormatCheckboxes = document.querySelectorAll('.format-checkbox');

        let checkedFormatCheckboxesCount = 0;
        allFormatCheckboxes.forEach(element => {
            if (element.checked) {
                checkedFormatCheckboxesCount++;
            }
        });

        let filesCount = couplesCount * checkedFormatCheckboxesCount;
        document.querySelector('.files-info').innerHTML = filesCount + ' files';
    }
    updateFilesCount();
    
    function updateResultField () {
        let resultField = document.querySelector('.result-field');
        resultField.innerHTML = '';

        let allFormatCheckboxes = document.querySelectorAll('.format-checkbox');
        let allFormatsArray = [];

        allFormatCheckboxes.forEach(element => {
            if (element.checked) {
                allFormatsArray.push(element.id);
            }
        });
        
        let allLinguaCheckboxes = document.querySelectorAll('.lingua-checkbox');
        let allLinguasArray = [];
        let uncheckedLinguasArray = [];

        allLinguaCheckboxes.forEach(element => {
            allLinguasArray.push(element.id);
            if (!element.checked) {
                uncheckedLinguasArray.push(element.id);
            }
        });

        let bookID = document.querySelector('.book-id-input').value;
        let fullFileNamesArray = '';

        for (let i = 0; i < allLinguasArray.length; i++) {
            for (let j = 0; j < allLinguasArray.length; j++) {
                if (!(uncheckedLinguasArray.includes(allLinguasArray[i]) && uncheckedLinguasArray.includes(allLinguasArray[j])) && allLinguasArray[i] !== allLinguasArray[j]) {
                    let fileName = bookID + '_' + allLinguasArray[i] + '_' + allLinguasArray[j];
                    let resultFieldItem = '<div class="result-field-item">';
                    resultFieldItem += '<p class="result-field-item-title" id="' + allLinguasArray[i] + '-' + allLinguasArray[j] + '">' + fileName + '</p>';
                    resultFieldItem += '<div class="result-field-item-formats">';

                    let linguasArguments = `\'${allLinguasArray[i]}\', \'${allLinguasArray[j]}\'`;
                    let lingua1 = allLinguasArray[i];
                    let lingua2 = allLinguasArray[j];

                    allFormatsArray.forEach(format => {
                        let actualFileName = format.includes('pdf') ? fileName + '_' + format.split('pdf-')[1] : fileName;
                        let fileExtension = format.includes('pdf') ? 'pdf' : format;
                        let formatFunction = '';

                        if (fileExtension === 'txt') {
                            formatFunction = 'saveTxt(' + linguasArguments + ');setTimeout(() => {updateFormatStatuses(this)}, 100);';
                        } else if (fileExtension === 'fb2') {
                            formatFunction = 'saveFb2(' + linguasArguments + ');setTimeout(() => {updateFormatStatuses(this)}, 100);';
                        } else if (fileExtension === 'epub') {
                            formatFunction = 'saveEpub(' + linguasArguments + ');setTimeout(() => {updateFormatStatuses(this)}, 100);';
                        } else if ('pdf') {
                            let mode = format.split('-')[1];
                            formatFunction = `savePDF(${linguasArguments}, '${mode}')`;
                        }

                        let fullFileName = `${actualFileName}.${fileExtension}`;
                        fullFileNamesArray += fullFileName + ';';

                        resultFieldItem += `<div class="result-format result-format-absent result-format-${lingua1}\
                         result-format-${lingua1}-${lingua2} result-format-${fileExtension}"\
                         langs="${lingua1}-${lingua2}"\
                         onclick="${formatFunction}" title="${fullFileName}">\
                        <img class="result-format-reflection" src="img/reflection.svg">\
                        
                        <p class="result-format-title">${format}</p>\
                        
                        <img class="result-format-reflection result-format-reflection-right" src="img/reflection.svg">\
                        </div>`;
                        
                    });

                    resultFieldItem += '</div></div>';

                    resultField.insertAdjacentHTML('beforeend', resultFieldItem);
                }
            }
            setSavingByLinguaCouple();
        }
        updateFormatStatuses();

        document.querySelectorAll('.invisible-input-content').forEach(element => {
            element.remove();
        });

        document.querySelectorAll('.lingua-checkbox').forEach(linguaCheckbox => {
            getBookContent(linguaCheckbox.id);
        });
    }
    updateResultField();

    function updateFormatStatuses (format = undefined) {
        
        getFilesList('books/saved/').then(function() {
            let savedFilesString = document.querySelector('.invisible-input-general').value;
            let resultFormats = format !== undefined ? [format] : document.querySelectorAll('.result-format');

            resultFormats.forEach((item) => {
                if (savedFilesString.includes(item.title)) {
                    item.classList.remove('result-format-absent');
                } else {
                    item.classList.add('result-format-absent');
                }
            });
        });
    }

    function setLaunchHover () {
        document.querySelectorAll('.launch').forEach(element => {
            element.addEventListener('mouseover', () => {
                element.src = "img/launch_hover.svg";
            });
            element.addEventListener('mouseout', () => {
                element.src = "img/launch.svg";
            });
        });
    }
    setLaunchHover();

    async function getFilesList (path) {
        let bookID = document.querySelector('.book-id-input').value;

        let response = await fetch('get_files_list.php?path=' + path + '&bookID=' + bookID);
        let text = await response.text();
        document.querySelector('.invisible-input-general').value = text;
    }

    let bookIDGlobal = document.querySelector('.book-id-input').value;

    async function getTextToUpdateLinguaPanels (bookID) {
        
        document.querySelectorAll('.lingua-panel').forEach(elem => {
            elem.remove();
        });

        let response = await fetch('update_lingua_panels.php');
        let text = await response.text();
        document.querySelector('.invisible-input-general').value = text;
    }

    function updateLinguaPanels () {
        let bookID = document.querySelector('.book-id-input').value;
        getTextToUpdateLinguaPanels(bookID).then(function() {
            document.querySelector('.sidebar').insertAdjacentHTML('beforeend', document.querySelector('.invisible-input-general').value)
            setLaunchHover();
            setSavingByLingua();
            setSavingByLinguaCouple();
            updateLinguasCount();
            updateCouplesCount();
            updateFilesCount();
            updateResultField();
            setCheckboxesEventListener();
        });
    }

    async function getBookContent (lingua) {
        let invisibleInput = `<p type="text" class="invisible-input invisible-input-content invisible-input-${lingua}"></p>`;
        document.querySelector('.sidebar').insertAdjacentHTML('afterbegin', invisibleInput);

        let fileName = document.querySelector('.lingua-checkbox-' + lingua).title;
        
        let response = await fetch('books/' + fileName);
        let text = await response.text();

        document.querySelector(`.invisible-input-${lingua}`).innerText = text.split('\n').join('<br>');
    }

    document.querySelectorAll('.lingua-checkbox').forEach(linguaCheckbox => {
        getBookContent(linguaCheckbox.id);
    });

    function setCheckboxesEventListener () {
        document.querySelectorAll('.checkbox').forEach(checkbox => checkbox.addEventListener('change', () => {
            updateCouplesCount();
            updateFilesCount();
            updateResultField();
            setSavingByLingua();
            setSavingByLinguaCouple();
        }));
    }
    setCheckboxesEventListener();

    document.querySelector('.update-img').addEventListener('click', () => {
        updateResultField();
        updateLinguaPanels();
    });

    document.querySelectorAll('.result-format').forEach(element => {
        element.addEventListener('click', () => {
            updateFormatStatuses(element);
        });
    });

    document.querySelector('.main-launch').addEventListener('click', () => {
        document.querySelectorAll('.result-format').forEach(element => {
            element.click();
        });
    });

    function setSavingByLingua () {
        document.querySelectorAll('.lingua-launch').forEach(element => {
            element = removeAllEventListeners(element);
            element.addEventListener('click', () => {
                let lingua = element.getAttribute('lingua');
                document.querySelectorAll('.result-format-' + lingua).forEach(format => {
                    format.click();
                });
            })
        });
    }
    setSavingByLingua();

    function setSavingByLinguaCouple () {
        document.querySelectorAll('.result-field-item-title').forEach(element => {
            element = removeAllEventListeners(element);
            element.addEventListener('click', () => {
                let linguasCouple = element.id;
                document.querySelectorAll('.result-format-' + linguasCouple).forEach(format => {
                    format.click();
                });
            })
        });
    }
    setSavingByLinguaCouple();

    function saveTxt (lang1, lang2) {
        let bookID = document.querySelector('.book-id-input').value;
        $.ajax({
            url: "save_bilingual_formats.php",
            type: "POST",
            data: ({format: 'txt',
                    address1: `books/${bookID}_${lang1}.txt`,
                    address2: `books/${bookID}_${lang2}.txt`,
                    outputPath: `books/saved/${bookID}_${lang1}_${lang2}.txt`
            }),
            dataType: "html"
        });
    }

    function saveFb2 (lang1, lang2) {
        let bookID = document.querySelector('.book-id-input').value;
        $.ajax({
            url: "save_bilingual_formats.php",
            type: "POST",
            data: ({format: 'fb2',
                    address1: `books/${bookID}_${lang1}.txt`,
                    address2: `books/${bookID}_${lang2}.txt`,
                    outputPath: `books/saved/${bookID}_${lang1}_${lang2}.fb2`,
                    coverPath: `${coversDir}/${lang1}.png`,
                    illustrationsDir: illustrationsDir,
                    lang1: lang1,
                    lang2: lang2,
                    bookID: bookID
            }),
            dataType: "html"
        });
    }

    function saveEpub (lang1, lang2) {
        let bookID = document.querySelector('.book-id-input').value;
        $.ajax({
            url: "save_bilingual_formats.php",
            type: "POST",
            data: ({format: 'epub',
                    address1: `books/${bookID}_${lang1}.txt`,
                    address2: `books/${bookID}_${lang2}.txt`,
                    outputPath: `books/saved/${bookID}_${lang1}_${lang2}.epub`,
                    coverPath: `${coversDir}/${lang1}.png`,
                    illustrationsDir: illustrationsDir,
                    lang1: lang1,
                    lang2: lang2,
                    bookID: bookID
            }),
            dataType: "html"
        });
    }

    function savePDF (lang1, lang2, mode) {
        let bookID = document.querySelector('.book-id-input').value;
        
        let text1 = document.querySelector(`.invisible-input-${lang1}`).innerText;
        let text2 = document.querySelector(`.invisible-input-${lang2}`).innerText;
        text1 = text1.replaceAll('<br>', '\n');
        text2 = text2.replaceAll('<br>', '\n');

        let coverPath = `${coversDir}/${lang1}.png`;
        let fileName = `${bookID}_${lang1}_${lang2}_${mode}`;

        printBilingualPDF(text1, text2,
                            lang1, lang2,
                            mode, coverPath,
                            fileName,
                            illustrationsDir);
    }

    function getArticlesArray (lingua) {
        let articles = document.querySelector('.invisible-input-' + lingua);

        if (articles) {
            return articles.innerText.split('<br>');
        } else {
            return false;
        }
    }

    function removeAllEventListeners (element) {
        clonedElement = element.cloneNode(true);
        element.parentNode.replaceChild(clonedElement, element);
        return clonedElement;
    }
</script>