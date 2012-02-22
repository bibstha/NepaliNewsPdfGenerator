<?php
require_once('../bootstrap.php');
$inFileDirPath = FILE_PATH;
$dir = dir($inFileDirPath);
while (false !== ($entry = $dir->read())) {
    print $entry . "<br/>";
}