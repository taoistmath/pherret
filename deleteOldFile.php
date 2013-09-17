<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/17/13
 * Time: 12:11 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();

$username = $_SESSION['username'];

deleteOldFile();

function deleteOldFile()
{
    global $username;

    if (!$username == "") {
        shell_exec("rm -f " . $username . "*");
    }
}

header("Location: /pherret.php");
?>
