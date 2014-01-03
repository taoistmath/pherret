<!DOCTYPE html>

<?php 
include('includes/head.php'); 
?>

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/5/13
 * Time: 1:56 PM
 * To change this template use File | Settings | File Templates.
 */

session_start();

date_default_timezone_set('America/Los_Angeles');

?>

<body>

<?php include('includes/header.php'); ?>

<div class="container">

    <h2>PHERRET</h2>

</div>

<div class="container">

    <?php

    runRegression();

    ?>

    <table>
        <tbody>
        <tr>
            <td class="span2">
                <form id="saveFile" name="saveFile" method="GET">
                    <p>Please enter a new name to save your file.</p>
                    <input type="text" class="form-control" id="saveResults" name="saveResults" value="<?php echo $_SESSION['resultsFile'] ?>">
                    <div class="controls controls-row">
                        <button class="btn btn-success" type="submit" onclick="return validateField(this.form,'saveResults','saveFile.php')">Save Results File</button>
                    </div>
                </form>
            </td>
        </tr>
        <tr>
            <td class="span2">
                <form id="featureFilter" name="featureFilter" method="GET" action="pherret.php">
                    <div class="controls controls-row">
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
