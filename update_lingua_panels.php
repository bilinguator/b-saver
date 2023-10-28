<?php

$booksPath = 'books/';
$bookFiles = array_diff(scandir($booksPath), ['.', '..']);
foreach($bookFiles as $key => $value) {
    if(substr($value, -4) !== '.txt') {
        unset($bookFiles[$key]);
    }
}

$bookIdArray = Array();

foreach ($bookFiles as $key => $value) {
    $fileName = explode('.txt', $value)[0];
    $fileBase = explode(' ', $fileName)[0];
    $fileLingua = substr($fileBase, -3);
    $fileId = substr($fileBase, 0, -4);

    if (!@$bookIdArray[$fileId]) {
        $bookIdArray[$fileId] = Array();
    }

    array_push($bookIdArray[$fileId], $fileLingua);
}

$bookLinguasArray = max($bookIdArray);
$bookId = array_search($bookLinguasArray, $bookIdArray);

$fileNamesArray = Array();
foreach($bookFiles as $value) {
    if(strpos($value, $bookId) !== false) {
        $lingua = substr(explode($bookId, $value)[1], 1, 3);
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
        <img src="img/launch.svg" alt="Launch" class="launch lingua-launch">
    </div>
<?php
    endforeach;
?>