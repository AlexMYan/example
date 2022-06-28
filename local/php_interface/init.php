<?php

function includeFile($relPath)
{
    $filePath = $_SERVER['DOCUMENT_ROOT'] . $relPath;
    if (is_file($filePath)) {
        require $filePath;
    }
}

//classes loader
includeFile('/local/php_interface/include/classLoader.php');

//handlers
includeFile('/local/php_interface/include/handlers.php');
?>