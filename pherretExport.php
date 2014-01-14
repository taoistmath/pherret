<!DOCTYPE html>

<?php include('includes/head.php'); ?>

<?php

sec_session_start();

?>

<body>

<?php include('includes/header.php'); ?>

<?php if (login_check($mysqli) == true) : ?>

<div class="container">

<?php

    exportFile();

?>

    <table>
        <tbody>
        <tr>
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

<?php else : ?>
    <p>
        <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
    </p>
<?php endif; ?>

<?php include('includes/footer.php'); ?>

</body>
</html>

<?php    

function exportFile()
{
    // Check to see if exportFilename contains '.csv' - leave alone if it does, otherwise add it.
    if (strpos($_GET["exportFilename"],'.csv') !== false) {
        $saveFileName = $_GET["exportFilename"];
    }
    else {
        $saveFileName = $_GET["exportFilename"].'.csv';
    }
    $features = checkmarkValues();

    $file = fopen($saveFileName,"w");

    foreach ($features as $feature)
    {
        fputcsv($file,explode(',',$feature));
    }

    fclose($file);

    echo "
        <h4>
            Your Test Suite '$saveFileName' has been saved
        </h4>
        ";

}

function checkmarkValues()
{
    if (isset($_GET['feature'])) {
        $feature = $_GET['feature'];
        return $feature;
    }
}

?>