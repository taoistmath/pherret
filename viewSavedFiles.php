<!DOCTYPE html>

<?php include('includes/head.php'); ?>

<?php

session_start();
$_SESSION['viewSavedFiles'] = $_GET['viewSavedFiles'];
?>

<body>


<?php include('includes/header.php'); ?>


<div class="container">

    <form id="saveFile" name="saveFile" method="GET">
        <div class="form-horizontal">
            <div class="row">
                <div class="span4">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input class="span2" type="text" id="viewSavedFiles" name="viewSavedFiles" placeholder="Username" value="<?php print $_GET["viewSavedFiles"]; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="submit" onclick="return validateField(this.form,'viewSavedFiles','viewSavedFiles.php')">View Saved Files</button>
                                <br>
                                <span style="color:red;" id="viewSavedFilesError"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php

    displaySavedFiles();

    if ($_SESSION['username'] == 'admin') {
        echo '<td class="span3">
                    <form id="deleteOldFile" name="deleteOldFile" method="GET" action="deleteOldFile.php">
                        <div class="controls controls-row">
                            <br>
                            <button class="btn btn-danger" type="submit" onclick="return verifyDelete(\'deleteOldFile\',\'deleteOldFile.php\')">Delete Result Files</button>
                        </div>
                    </form>
                </td>';
    } 
    else
        echo '<br>';

?>

    <form id="returnToList" name="returnToList" method="GET" action="pherret.php">
        <div class="controls controls-row">
            <button class="btn btn-primary" type="submit">Return to List</button>
        </div>
    </form>

</div>

<?php include('includes/footer.php'); ?>

</body>
</html>

<?php

function displaySavedFiles()
{
    $viewSavedFiles = $_GET["viewSavedFiles"];

        $files = scandir("./");
        foreach ($files as $file) {
            if (strstr($file, $viewSavedFiles)) {
                echo '<a href="' . ltrim($file, './') . '"target="_blank">' . $file . '</a><br />';
            }
        }
}

?>