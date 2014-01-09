<!DOCTYPE html>

<?php include('includes/head.php'); ?>

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

    <?php

    runRegression();

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

function noUsername()
{
    echo "
        <div class='container'>
            <h4 style='color:red'>Please Return to List and enter your Username.</h4>
        </div>
        ";
}

function testSuiteNotExist()
{
    echo "
        <div class='container'>
            <h4 style='color:red'>The entered Test Suite does not exist!</h4>
        </div>
        ";
}

function displayResults($resultsFile)
{
    echo '
        <h4>
            <a href="' . $resultsFile . '" target="_blank" style="text-decoration:underline">Click To See Test Results</a>
        </h4>

        <p>Please enter a new name to save your file.</p>

        <form id="saveFile" name="saveFile" method="GET">
            <div class="form-horizontal">
                <div class="row">
                    <div class="span4">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="saveResults" name="saveResults" value="'.$_SESSION["resultsFile"].'">
                                <span class="input-group-btn">
                                    <button class="btn btn-success" type="submit" onclick="return validateField(this.form,\'saveResults\',\'saveFile.php\')">Save Results File</button>
                                    <span style="color:red;" id="saveResultsError"></span>
                                    <br>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        ';
}

function commitExecution($features)
{
    $execution = writeExecutionString();
    $output = shell_exec($execution);
    writeResultsToFile($output,$features);
}

function writeResultsToFile($output,$features)
{

    $resultsFile = $_SESSION["username"] . date("YmdH") . ".html";
    $_SESSION['resultsFile'] = $resultsFile;

    touch($resultsFile);
    chmod($resultsFile, 0666);

    if (strpos($output,'screenshot')) {
        $fo = fopen($resultsFile, 'w');

        fwrite($fo, "<!DOCTYPE html><h4 style='color:red'>FAILURE</h4><p style='color:red'>Please see bottom of page for results</p><pre>$output</pre>");
        fclose($fo);
    } elseif(strpos($output, 'passed)')) {
        $fo = fopen($resultsFile, 'w');

        fwrite($fo, "<!DOCTYPE html><h4 style='color:green'>SUCCESS</h4><p style='color:green'>Please see bottom of page for results</p><pre>$output</pre>");
        fclose($fo);
    } else {
        $fo = fopen($resultsFile, 'w');

        fwrite($fo, "<!DOCTYPE html><pre>$output</pre>");
        fclose($fo);
    }

    resultsLink($resultsFile,$features);

}

function resultsLink($resultsFile,$features)
{
    // $feature = file($_GET["importFilename"],FILE_IGNORE_NEW_LINES);
    if ($features == NULL)
        testSuiteNotExist();
    else
        displayResults($resultsFile);
}

function writeExecutionString()
{
    global $behatLoc, $featureLoc;
    //only use parallel flag if set to higher than 1
    if ($_GET['parallel'] > 1) {
        $executionString = "cd " . $behatLoc . " && bin/behat --profile " . strtolower($_GET['environment']) . "_" . strtolower($_GET['browser']) . " --parallel " . $_GET['parallel'] . " " . $featureLoc;
    }
    else {
        $executionString = "cd " . $behatLoc . " && bin/behat --profile " . strtolower($_GET['environment']) . "_" . strtolower($_GET['browser']) . " " . $featureLoc;
    }

    return $executionString;
}

function checkmarkValues()
{
    if (!$_GET["importFilename"] == NULL) {
        // Check to see if importFilename contains '.csv' - leave alone if it does, otherwise add it.
        if (strpos($_GET["importFilename"],'.csv') !== false) {
            $feature = file($_GET["importFilename"],FILE_IGNORE_NEW_LINES);
            return $feature;
        } 
        else {
            $feature = file($_GET["importFilename"].'.csv',FILE_IGNORE_NEW_LINES);
            return $feature;
        }
    } elseif (isset($_GET['feature'])) {
        $feature = $_GET['feature'];
        return $feature;
    }
}

function appendFilterToFeature($features)
{
    global $localRepo, $behatLoc;

    foreach ($features as $feature) {
        $path_to_file = $localRepo . "/" . $feature;

        file_put_contents($path_to_file, preg_replace("/#\s*Scenario/", "#CommentedOut", file_get_contents($path_to_file)));

        file_put_contents($path_to_file, str_replace("Scenario", "@" . $_SESSION["username"] . "\nScenario", file_get_contents($path_to_file)));
    }

    $temp_behat_loc = $behatLoc . "behat.yml";
    file_put_contents($temp_behat_loc, str_replace("~@mixed", "@" . $_SESSION["username"], file_get_contents($temp_behat_loc)));

}

function removeFilterFromFeature($features)
{
    global $localRepo, $behatLoc;

    foreach ($features as $feature) {
        $path_to_file = $localRepo . "/" . $feature;
        file_put_contents($path_to_file, str_replace("@" . $_SESSION["username"] . "\n", "", file_get_contents($path_to_file)));
        file_put_contents($path_to_file, str_replace("#CommentedOut", "#  Scenario", file_get_contents($path_to_file)));
    }

    $temp_behat_loc = $behatLoc . "behat.yml";
    file_put_contents($temp_behat_loc, str_replace("@" . $_SESSION["username"], "~@mixed", file_get_contents($temp_behat_loc)));

}

function runRegression()
{
    //Get username
    $username = $_SESSION["username"];
    if (!$username == "") {

        $features = checkmarkValues(); //Get the selected features

        appendFilterToFeature($features); //Append username to the selected features

        commitExecution($features); //Commit the execution string

        removeFilterFromFeature($features); //Remove username from the selected features

    } else {
        noUsername();
    }
}

?>
