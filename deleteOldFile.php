<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/17/13
 * Time: 12:11 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();

//$username = $_SESSION['username'];
$resultsFile = $_SESSION['resultsFile'];

deleteOldFile();

function deleteOldFile()
{
    global $resultsFile;

    if (!$resultsFile == "") {
        shell_exec("rm -f " . $resultsFile);
    }
}

header("Location: /pherret.php");
?>
