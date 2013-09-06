<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/5/13
 * Time: 1:56 PM
 * To change this template use File | Settings | File Templates.
 */

//Timestamp for creating HTML file for test results
$behatLoc = 'tools/regression/'; //Set to relative path to location of behat.yml file
$featureLoc = 'features/dandb'; //Set to local repo folder that contains features
$localRepo = $behatLoc . $featureLoc; //Set to local repo folder that contains features
$resultsFile = date("YmdHms") . ".html";

//Get username
$username = $_GET["username"];
if($username == "")
{
    echo "Please Return to List and enter your Username.";
} else {

//Get the environment
if(isset($_GET['environment']))
{
    $environment = strtolower($_GET['environment']);
}

//Get the browser
if(isset($_GET['browser']))
{
    $browser = strtolower($_GET['browser']);
}

//Get the selected features
$features = checkmarkValues();

//Append username to the selected features
$temp_file_array = appendFilterToFeature($features);

//Get the execution string
$execution = writeExecutionString();

$output = shell_exec("cd " . $behatLoc . " && " . $execution);

//Remove username from the selected features
removeFilterFromFeature($features);

echo '<div class="results">';
//Print the output to a file

writeResultsToFile();

resultsLink();
echo '</div>';
}

function resultsLink()
{
    global $resultsFile;
    echo "
    <h4>
    <a href='" . $resultsFile . "' target='_blank' style='text-decoration:underline'>Click To See Test Results</a>
    </h4>
    ";

}

function writeResultsToFile()
{
    global $resultsFile, $output;
    $fo = fopen($resultsFile, 'x');

    fwrite($fo, "<!DOCTYPE html><pre>$output</pre>");
    fclose($fo);

}

function writeExecutionString()
{
    global $environment, $browser, $featureLoc;
    $executionString = "bin/behat --profile " . $environment . "_" . $browser . " " . $featureLoc;

    return $executionString;
}

function checkmarkValues()
{
    if (isset($_GET['feature'])) {
        $feature = $_GET['feature'];
        return $feature;
    }
}

function appendFilterToFeature($features)
{
    global $localRepo, $username, $behatLoc;

    foreach ($features as $feature) {
        $path_to_file = $localRepo . "/" . $feature;
        file_put_contents($path_to_file, str_replace("Scenario", "@" . $username . "\nScenario", file_get_contents($path_to_file)));
    }

    $temp_behat_loc = $behatLoc."behat.yml";
    file_put_contents($temp_behat_loc, str_replace("~@mixed", "@".$username, file_get_contents($temp_behat_loc)));

}

function removeFilterFromFeature($features)
{
    global $localRepo, $behatLoc, $username;

    foreach ($features as $feature) {
        $path_to_file = $localRepo . "/" . $feature;
        file_put_contents($path_to_file, str_replace("@" . $username."\n", "", file_get_contents($path_to_file)));
    }

    $temp_behat_loc = $behatLoc."behat.yml";
    file_put_contents($temp_behat_loc, str_replace("@".$username, "~@mixed", file_get_contents($temp_behat_loc)));

}

?>

<form id="featureFilter" name="featureFilter" method="GET" action="pherret.php">

    <div class="span2">
        <label><br></label>
        <button class="btn btn-primary" type="submit">Return to List</button>
        <label><br></label>
    </div>

</form>


