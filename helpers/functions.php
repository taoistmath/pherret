<?php

function listFolderFiles($dir, $exclude)
{
    global $localRepo;
    $files = scandir($dir);
    $folder = end(explode('/', $dir));
    foreach ($files as $file) {
        if (is_array($exclude) and !in_array($file, $exclude)) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir . '/' . $file)) {
                    echo '
                    <li>
                        <input type="checkbox">
                        <label>' . $file . '</label>
                        <ul>
                    ';
                } else {
                    echo '
                    <li>
                        <input type="checkbox" name="feature[]" id="feature" value="' . $folder . '/' . $file . '">
                        <a href="' . ltrim($localRepo . '/' . $folder . '/' . $file, './') . '"target="_blank">' . $file . '</a><br />
                    </li>
                    ';
                }
                if (is_dir($dir . '/' . $file))
                {
                    listFolderFiles($dir . '/' . $file, $exclude);
                    echo '</ul>';
                }
            }
        }
    }
}

function noUsername()
{
    echo "
        <div class='container'>
            <h4>Please Return to List and enter your Username.</h4>
        </div>
    ";
}

function commitExecution()
{
    $execution = writeExecutionString();
    $output = shell_exec($execution);
    writeResultsToFile($output);
}

function writeResultsToFile($output)
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

    resultsLink($resultsFile);

}

function resultsLink($resultsFile)
{
    echo "

            <h4>
                <a href='" . $resultsFile . "' target='_blank' style='text-decoration:underline'>Click To See Test Results</a>
            </h4>

    ";

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

        commitExecution(); //Commit the execution string

        removeFilterFromFeature($features); //Remove username from the selected features

    } else {
        noUsername();
    }
}

?>