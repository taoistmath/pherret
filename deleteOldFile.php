<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/17/13
 * Time: 12:11 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();

$viewUsername = $_SESSION["viewUsername"];
$resultsFile = $_SESSION['resultsFile'];

deleteOldFile();

function deleteOldFile()
{
    global $viewUsername;

    if (!$viewUsername == "") {
        shell_exec("rm -f " . $viewUsername.'*');
    }
}

header("Location: /pherret.php");
?>
