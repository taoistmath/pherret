<!DOCTYPE html>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 9/5/13
 * Time: 1:56 PM
 * To change this template use File | Settings | File Templates.
 */

session_start();

//Timestamp for creating HTML file for test results
$behatLoc = 'tools/regression/'; //Set to relative path to location of behat.yml file
$featureLoc = 'features/dandb'; //Set to local repo folder that contains features
$localRepo = $behatLoc . $featureLoc; //Set to local repo folder that contains features

//Get username
$username = $_GET["username"];
if($username == "")
{
    echo "Please Return to List and enter your Username.";
} else {

    $_SESSION['username'] = $username;

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

//Commit the execution string
    commitExecution();

//Remove username from the selected features
    removeFilterFromFeature($features);

}

?>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>PHERRET</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#">PHp Engineering Repository REgression Tool (PHERRET)</a>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <!--                    <li class="active"><a href="#">Home</a></li>-->
                    <!--                    <li><a href="#about">About</a></li>-->
                    <!--                    <li><a href="#contact">Contact</a></li>-->
                    <!--                    <li><a href="/logout.php">Sign Out</a></li>-->
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <h1>PHERRET</h1>
<div class="btn-group">
<p>
    <form id="featureFilter" name="featureFilter" method="GET" action="pherret.php">
        <div class="span2">
            <label><br></label>
            <button class="btn btn-primary" type="submit">Return to List</button>
            <label><br></label>
        </div>
    </form>

    <form id="deleteOldFile" name="deleteOldFile" method="GET" action="deleteOldFile.php">
        <div class="span2">
            <label><br></label>
            <button class="btn btn-danger" type="submit">Delete Old Files</button>
            <label><br></label>
        </div>
    </form>
    </p>
    </div>
</div>


<?php

function commitExecution()
{
    global $behatLoc;
    $execution = writeExecutionString();
    $output = shell_exec("cd " . $behatLoc . " && " . $execution);
    writeResultsToFile($output);
}

function writeResultsToFile($output)
{

    global $username;

    $resultsFile = $username . date("YmdH") . ".html";

    $fo = fopen($resultsFile, 'w+');

    fwrite($fo, "<!DOCTYPE html><pre>$output</pre>");
    fclose($fo);

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

