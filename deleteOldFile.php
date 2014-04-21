<?php
include_once 'includes/head.php';

$viewSavedFiles = $_SESSION["viewSavedFiles"];

deleteOldFile();

function deleteOldFile()
{
    global $viewSavedFiles;

    if (!$viewSavedFiles == "") {
        shell_exec("rm -f *" . $viewSavedFiles.'*');
    }
}

header("Location: /viewSavedFiles.php");
?>
