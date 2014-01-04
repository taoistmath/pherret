<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/17/13
 * Time: 12:11 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();

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
