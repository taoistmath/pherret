<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'helpers/variables.php';

sec_session_start();

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
