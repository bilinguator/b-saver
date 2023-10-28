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
        $fileLingua = substr($fileBase, -3);
        $fileLingua = substr($fileBase, strrpos($fileBase, '_') + 1);
        $fileId = substr($fileBase, 0, -4);

        if (!@$bookIDArray[$fileId]) {
            $bookIDArray[$fileId] = Array();
        }

        array_push($bookIDArray[$fileId], $fileLingua);
    }

    $bookLinguasArray = max($bookIDArray);
    $bookID = array_search($bookLinguasArray, $bookIDArray);
    
    $fileNamesArray = Array();
    foreach($bookFiles as $value) {
        if(strpos($value, $bookID) !== false) {
            $lingua = substr(explode($bookID, $value)[1], 1, 3);
            $fileNamesArray[$lingua] = $value;
        }
    }

    $linguaCoversArray = Array();
    foreach ($bookLinguasArray as $lingua) {
        if (file_exists($booksPath . '_covers/' . $lingua . '.png')){
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
                    <img src="img/launch.svg" alt="Launch" class="launch lingua-launch" id="<?=$lingua?>">
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
                    

                    allFormatsArray.forEach(format => {
                        let actualFileName = format.includes('pdf') ? fileName + '_' + format.split('pdf-')[1] : fileName;
                        let fileExtention = format.includes('pdf') ? 'pdf' : format;
                        let formatFunction = '';

                        if (format === 'txt') {
                            formatFunction = 'saveTxt(' + linguasArguments + ');setTimeout(() => {updateFormatStatuses(this)}, 100);';
                        } else if (format === 'fb2') {
                            formatFunction = 'saveFb2(' + linguasArguments + ');setTimeout(() => {updateFormatStatuses(this)}, 100);';
                        } else if (format === 'epub') {
                            formatFunction = 'saveEpub(' + linguasArguments + ');setTimeout(() => {updateFormatStatuses(this)}, 100);';
                        } else if (format.includes('pdf')) {
                            formatFunction = `openReadyBook(${linguasArguments}, `;
                            formatFunction += format.includes('cols') ? `\'cols\')` : `\'rows\')`;
                        }


                        let fullFileName = `${actualFileName}.${fileExtention}`;
                        fullFileNamesArray += fullFileName + ';';

                        resultFieldItem += '<div class="result-format result-format-absent result-format-' + allLinguasArray[i] + ' result-format-' + allLinguasArray[i] + '-' + allLinguasArray[j] + '"';
                        resultFieldItem += ' onclick="' + formatFunction + '" title="' + fullFileName + '">';
                        resultFieldItem += '<img class="result-format-reflection" src="img/reflection.svg">';

                        resultFieldItem += '<p class="result-format-title">' + format + '</p>';

                        resultFieldItem += '<img class="result-format-reflection result-format-reflection-right" src="img/reflection.svg">';
                        resultFieldItem += '</div>';
                        
                    });

                    resultFieldItem += '</div></div>';

                    resultField.insertAdjacentHTML('beforeend', resultFieldItem);
                }
            }
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

            }
        );
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
        getTextToUpdateLinguaPanels('manifesto_de_prago').then(function() {
            document.querySelector('.sidebar').insertAdjacentHTML('beforeend', document.querySelector('.invisible-input-general').value)
            setLaunchHover();
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

    

    document.querySelectorAll('.checkbox').forEach(checkbox => checkbox.addEventListener('change', () => {
        updateCouplesCount();
        updateFilesCount();
        updateResultField();
    }));

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

    document.querySelectorAll('.lingua-launch').forEach(element => {
        element.addEventListener('click', () => {
            let lingua = element.id;
            document.querySelectorAll('.result-format-' + lingua).forEach(format => {
                format.click();
            });
        })
    });

    document.querySelectorAll('.result-field-item-title').forEach(element => {
        element.addEventListener('click', () => {
            let linguasCouple = element.id;
            document.querySelectorAll('.result-format-' + linguasCouple).forEach(format => {
                format.click();
            });
        })
    });

    let lastPagePhrases = ['Больш кніг-білінгв на',
							'More bilingual books on',
							'Więcej dwujęzycznych książek na',
							'Больше книг-билингв на',
							'Більше книг-білінгв на'];
</script>
<!-- <script type="text/javascript" src="..\b-editor\scripts\lingua_lang.js"></script> -->
<script type="text/javascript" src="scripts/additional_functions.js"></script>
<script type="text/javascript" src="scripts/save_txt.js"></script>
<script type="text/javascript" src="scripts/save_fb2.js"></script>
<script type="text/javascript" src="scripts/save_epub.js"></script>
<script type="text/javascript" src="scripts/open_ready_book.js"></script>