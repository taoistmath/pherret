<!DOCTYPE html>

<?php include('includes/head.php'); ?>

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 6/12/13
 * Time: 9:48 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();

?>

<body>

<?php include('includes/header.php'); ?>

<div class="container">

    <h2>PHERRET</h2>


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

<?php include('includes/footer.php'); ?>

</body>
</html>

<?php

    exportFile();

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