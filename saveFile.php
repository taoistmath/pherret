<!DOCTYPE html>

<?php include('includes/head.php'); ?>

<?php

sec_session_start();

$resultsFile = $_SESSION['resultsFile'];
$saveResults = $_GET['saveResults'];

?>

<body>

<?php include('includes/header.php'); ?>

<?php if (login_check($mysqli) == true) : ?>

<div class="container">

    <?php

    saveFile();

    ?>

    <div class="btn-group">
        <p>

        <form id="returnToList" name="returnToList" method="GET" action="pherret.php">
            <div class="controls controls-row">
                <button class="btn btn-primary" type="submit">Return to List</button>
            </div>
        </form>

        </p>
    </div>

</div>

<?php else : ?>
    <p>
        <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
    </p>
<?php endif; ?>

<?php include('includes/footer.php'); ?>

</body>
</html>

<?php

function saveFile()
{
    global $resultsFile, $saveResults;

    if (strpos($saveResults,'.html') === false)
            $saveResults = $saveResults.'.html';

    if ($resultsFile == $saveResults) {

        $extension_pos = strrpos($resultsFile, '.'); // find position of the last dot, so where the extension starts
        $savedFile = substr($resultsFile, 0, $extension_pos) . generateRandomString() . substr($resultsFile, $extension_pos);

        file_put_contents($savedFile, file_get_contents($resultsFile));//write the contents of the result file to the saved file

        echo "
        <h4>
            Because you did not specify a new name, your saved file is: " . $savedFile . "
        </h4>
        ";
    } elseif($resultsFile != $saveResults) {

        file_put_contents($saveResults, file_get_contents($resultsFile));//write the contents of the result file to the saved file

        echo "
        <h4>
            Your saved file is: " . $saveResults . "
        </h4>
        ";
    } elseif (file_get_contents($resultsFile) == "") {
        echo "
        <h4>
            You must a run a test to save a file.
        </h4>
        ";
    }
}

function generateRandomString($length = 4)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

?>