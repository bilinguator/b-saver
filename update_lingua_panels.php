<?php

$booksPath = 'books/';
$bookFiles = array_diff(scandir($booksPath), ['.', '..']);
foreach ($bookFiles as $key => $value) {
    if (substr($value, -4) !== '.txt') {
        unset($bookFiles[$key]);
    }
}

$bookIDArray = Array();

foreach ($bookFiles as $key => $value) {
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
$bookId = array_search($bookLinguasArray, $bookIDArray);

$fileNamesArray = Array();
foreach ($bookFiles as $value) {
    if (strpos($value, $bookId) !== false) {
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