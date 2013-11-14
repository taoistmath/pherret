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

$behatLoc = 'tools/regression/'; //Set to relative path to location of behat.yml file
$featureLoc = 'features/dandb'; //Set to local repo folder that contains features
$localRepo = $behatLoc . $featureLoc; //Set to local repo folder that contains features
date_default_timezone_set('America/Los_Angeles');

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
    <link href="./style.css" rel="stylesheet">

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
                    <li><a href="/logout.php">Sign Out</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

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
                <form id="saveFile" name="saveFile" method="GET" action="saveFile.php">
                    <p>Please enter a new name to save your file.</p>
                    <input type="text" class="form-control" id="saveResults" name="saveResults" value="<?php echo $_SESSION['resultsFile'] ?>">
                    <div class="controls controls-row">
                        <button class="btn btn-success" type="submit">Save Results File</button>
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

<?php


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

    if (strpos($output,'screenshot')) {
        $fo = fopen($resultsFile, 'w+');

        fwrite($fo, "<!DOCTYPE html><h4 style='color:red'>FAILURE</h4><p style='color:red'>Please see bottom of page for results</p>");
        fclose($fo);
    } elseif(strpos($output, 'passed)')) {
        $fo = fopen($resultsFile, 'w+');

        fwrite($fo, "<!DOCTYPE html><h4 style='color:green'>SUCCESS</h4><p style='color:green'>Please see bottom of page for results</p>");
        fclose($fo);
    } else {
        $fo = fopen($resultsFile, 'w+');
        fclose($fo);
    }


    $fo = fopen($resultsFile, 'a+');

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
    global $behatLoc, $featureLoc;
    $executionString = "cd " . $behatLoc . " && bin/behat --profile " . strtolower($_GET['environment']) . "_" . strtolower($_GET['browser']) . " " . $featureLoc;

    return $executionString;
}

function importFilename($filename)
{

    return file($filename.'.csv',FILE_IGNORE_NEW_LINES);
}

function checkmarkValues()
{
    if (!$_GET["importFilename"] == NULL) {
        $feature = file($_GET["importFilename"].'.csv',FILE_IGNORE_NEW_LINES);
       return $feature;
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

