<!DOCTYPE html>
<?php
session_start();

if ($_SESSION['username'] == NULL)
    header("Location: http://pherret.local/login.php");

////Get the branch
//$branch = $_GET['gitBranch'];
//if ($branch == NULL)
//    $branch = 'INFRASYS-1913-Stable'; //Set to same branch as repository.

//Set up paths
//$repoLoc = '/var/www/helios/tools/regression'; //Set to absolute path to behat.yml file in your repository
//shell_exec("cp -r " . $repoLoc . " ./tools");

$behatLoc = 'tools/regression/'; //Set to relative path to location of behat.yml file
$featureLoc = 'features/dandb'; //Set to local repo folder that contains features
$localRepo = $behatLoc . $featureLoc; //Set to local repo folder that contains features

//Timestamp for creating HTML file for test results
$resultsFile = date("YmdHms") . ".html";

//Get username
$username = $_SESSION['username'];

//Get the environment
$environment = strtolower($_GET['environment']);

//Get the browser
$browser = strtolower($_GET['browser']);

//Get the selected features
$features = checkmarkValues();

//Append username to the selected features
$temp_file_array = appendFilterToFeature($features);

//Get the execution string
$execution = writeExecutionString();

$output = shell_exec("cd " . $behatLoc . " && " . $execution);

//Remove username from the selected features
removeFilterFromFeature($temp_file_array);

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
                    <li><a href="/logout.php">Sign Out</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <h1>PHERRET</h1>

    <form id="resetFilter" name="resetFilter" method="GET" action="pherret.php">
        <div class="controls controls-row">
                <button class="btn btn-primary" type="submit">Reset Features</button>
        </div>
    </form>

<!--    <form id="updateRepo" name="updateRepo" method="GET" action="update.php">-->
<!--        <div class="controls controls-row">-->
<!--            <button class="btn btn-primary" type="submit">Update Repository</button>-->
<!--        </div>-->
<!--    </form>-->

    <?php
    if ($features != NULL) {
        echo '<div class="results">';
        //Print the output to a file
        writeResultsToFile();

        resultsLink();
        echo '</div>';
    }
    ?>

    <form id="featureFilter" name="featureFilter" method="GET" action="pherret.php">

        <div class="row">
            <div class="span1">
                <p>Environment</p>
            </div>
            <div class="span2">
                <p>Browser</p>
            </div>
        </div>

        <div class="controls controls-row">
            <select class="span1" id="environment" name="environment">
                <option <?php if ($_GET['environment'] == 'QA') { ?>selected="true" <?php }; ?>value="QA">QA
                </option>
                <option <?php if ($_GET['environment'] == 'STG') { ?>selected="true" <?php }; ?>value="STG">STG
                </option>
                <option <?php if ($_GET['environment'] == 'PROD') { ?>selected="true" <?php }; ?>value="PROD">PRD
                </option>
            </select>

            <select class="span2" id="brower" name="browser">
                <option <?php if ($_GET['browser'] == 'Firefox') { ?>selected="true" <?php }; ?>value="Firefox">
                    Firefox
                </option>
                <option <?php if ($_GET['browser'] == 'Chrome') { ?>selected="true" <?php }; ?>value="Chrome">
                    Chrome
                </option>
            </select>
        </div>

<!--            <div class="span2">-->
<!--                <label>Branch</label>-->
<!--                <!--Changing the branch changes where the link points on GitHub.-->
<!--                This does not change which files are shown in the table-->
<!--                <input type="text" value="--><?PHP //print $branch; ?><!--" name="gitBranch">-->
<!--            </div>-->


            <label><br></label>
            <button class="btn btn-primary" type="submit">Start Features</button>
            <label><br></label>

        <?php listFolderFiles($localRepo, array('index.php', 'edit_page.php', 'pages', 'full', 'sanity')); checkmarkValues()?>

        <div class="span2">
            <label><br></label>
            <button class="btn btn-primary" type="submit">Start Features</button>
            <label><br></label>
        </div>
    </form>

</div>

<?php
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

function listFolderFiles($dir, $exclude)
{
    global $localRepo;
    $files = scandir($dir);
    $folder = end(explode('/', $dir));
    foreach ($files as $file) {
        if (is_array($exclude) and !in_array($file, $exclude)) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir . '/' . $file)) {
                    echo '<br />
                    <div name="project" id="project">
                    <strong>' . $file . '</strong>
                    </div>
                    <br />';
                } else {
                    echo '<input type="checkbox" name="feature[]" id="feature" value="' . $folder . '/' . $file . '" >
                        <a href="' . ltrim($localRepo . '/' .$folder . '/' . $file, './') . '"target="_blank">' . $file . '</a><br />';
                }
                if (is_dir($dir . '/' . $file)) listFolderFiles($dir . '/' . $file, $exclude);
            }
        }
    }
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

    $temp_file_array = array();
    foreach ($features as $feature) {
        $path_to_file = $localRepo . "/" . $feature;
        $temp_file =$path_to_file.date("YmdHms").".feature";
        file_put_contents($temp_file, str_replace("Scenario", "@" . $username . "\nScenario", file_get_contents($path_to_file)));
        array_push($temp_file_array,$temp_file);
    }

    $temp_behat_loc = $behatLoc."behat.yml";
    file_put_contents($temp_behat_loc, str_replace("~@mixed", "@".$username, file_get_contents($temp_behat_loc)));

    return $temp_file_array;
}

function removeFilterFromFeature($temp_file_array)
{
    global $behatLoc, $username;

    foreach($temp_file_array as $temp_file){
        if (is_File($temp_file))
        {
            unlink($temp_file);
        }
    }

    $temp_behat_loc = $behatLoc."behat.yml";
    file_put_contents($temp_behat_loc, str_replace("@".$username, "~@mixed", file_get_contents($temp_behat_loc)));

}

function writeExecutionString()
{
    global $environment, $browser, $username, $featureLoc;
    $executionString = "bin/behat --profile " . $environment . "_" . $browser . " " . $featureLoc;

    return $executionString;
}

?>

<!-- /container -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap-transition.js"></script>
<script src="../assets/js/bootstrap-alert.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<script src="../assets/js/bootstrap-dropdown.js"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrap-tab.js"></script>
<script src="../assets/js/bootstrap-tooltip.js"></script>
<script src="../assets/js/bootstrap-popover.js"></script>
<script src="../assets/js/bootstrap-button.js"></script>
<script src="../assets/js/bootstrap-collapse.js"></script>
<script src="../assets/js/bootstrap-carousel.js"></script>
<script src="../assets/js/bootstrap-typeahead.js"></script>

</body>
</html>
