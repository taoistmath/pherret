<!DOCTYPE html>

<?php include('includes/head.php'); ?>

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/18/13
 * Time: 12:45 PM
 * To change this template use File | Settings | File Templates.
 */

session_start();
$_SESSION['viewUsername'] = $_GET['viewUsername'];
?>

<body>


<?php include('includes/header.php'); ?>


<div class="container">

    <h2>PHERRET</h2>

    <table>
        <tbody>
        <tr>
            <form id="featureFilter" name="featureFilter" method="GET" action="viewSavedFiles.php">
                <div class="controls controls-row">
                    <input class="span2" type="text" id="viewUsername" name="viewUsername" placeholder="Username" value="<?php print $_GET["viewUsername"]; ?>">
                </div>
        </tr>

        <tr>
            <td class="span2">
                <button class="btn btn-success" type="submit">View Saved Files</button>
                </form>
            </td>
            <td class="span2">
                <form id="returnToList" name="returnToList" method="GET" action="pherret.php">
                    <div class="controls controls-row">
                        <br>
                        <button class="btn btn-primary" type="submit">Return to List</button>
                    </div>
                </form>
            </td>
        </tr>

        </tbody>
    </table>
</div>

<?php
    displaySavedFiles();

    if ($_SESSION['username'] == 'admin') {
        echo '<td class="span3">
                    <form id="deleteOldFile" name="deleteOldFile" method="GET" action="deleteOldFile.php">
                        <div class="controls controls-row">
                            <br>
                            <button class="btn btn-danger" type="submit">Delete Result Files</button>
                        </div>
                    </form>
                </td>';
    } else {
        echo '<br />';
                
    }

function displaySavedFiles()
{
    $viewUsername = $_GET["viewUsername"];

    if (!$viewUsername == "") {
        $files = scandir("./");
        foreach ($files as $file) {
            if (strstr($file, $viewUsername)) {
                echo '<a href="' . ltrim($file, './') . '"target="_blank">' . $file . '</a><br />';
            }
        }
    } else {
        echo "
        <h4>
            Please enter a username to view saved files.
        </h4>
        ";
    }
}

?>

<?php include('includes/footer.php'); ?>