<!DOCTYPE html>
<?php
session_start();
//
//if ($_SESSION['username'] == NULL)
//    header("Location: http://pherret.local/login.php");

////Get the branch
//$branch = $_GET['gitBranch'];
//if ($branch == NULL)
//    $branch = 'INFRASYS-1913-Stable'; //Set to same branch as repository.

//Set up paths
//$repoLoc = '/var/www/helios/tools/regression/features/dandb/mixed_stack'; //Set to absolute path to behat.yml file in your repository
//shell_exec("cp -r " . $repoLoc . " ./tools/regression/features/dandb");

$behatLoc = 'tools/regression/'; //Set to relative path to location of behat.yml file
$featureLoc = 'features/dandb'; //Set to local repo folder that contains features
$localRepo = $behatLoc . $featureLoc; //Set to local repo folder that contains features

//Get username
$username = $_GET["username"];

//Get the environment
if (isset($_GET['environment'])) {
    $environment = strtolower($_GET['environment']);
}

//Get the browser
if (isset($_GET['browser'])) {
    $browser = strtolower($_GET['browser']);
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

    <table>
        <tbody>
        <tr>
            <td class="span2">
                <form id="resetFilter" name="resetFilter" method="GET" action="pherret.php">
                    <div class="controls controls-row">
                        <button class="btn btn-primary" type="submit">Reset Features</button>
                    </div>
                </form>
            </td>
            <td class="span2">
                <form id="savedFiles" name="savedFiles" method="GET" action="viewSavedFiles.php">
                    <div class="controls controls-row">
                        <button class="btn btn-primary" type="submit">View Saved Files</button>
                    </div>
                </form>
            </td>
        </tr>
        </tbody>
    </table>

    <!--    <form id="updateRepo" name="updateRepo" method="GET" action="updateRepo.php">-->
    <!--        <div class="controls controls-row">-->
    <!--            <button class="btn btn-primary" type="submit">Update Repository - doesn't work</button>-->
    <!--        </div>-->
    <!--    </form>-->

    <form id="featureFilter" name="featureFilter" method="GET" action="pherretResults.php">

        <div class="row">
            <div class="span2">
                <p>Environment</p>
            </div>
            <div class="span2">
                <p>Browser</p>
            </div>
            <div class="span2">
                <p>Username</p>
            </div>
        </div>

        <div class="controls controls-row">
            <select class="span2" id="environment" name="environment">
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
                <!--                <option --><?php //if ($_GET['browser'] == 'Chrome') { ?><!--selected="true" -->
                <?php //}; ?><!--value="Chrome">-->
                <!--                    Chrome-->
                <!--                </option>-->
            </select>

            <input class="span2" type="text" id="username" name="username" placeholder="Username"
                   value="<?php print $_GET["username"]; ?>">

        </div>

        <!--            <div class="span2">-->
        <!--                <label>Branch</label>-->
        <!--                <!--Changing the branch changes where the link points on GitHub.-->
        <!--                This does not change which files are shown in the table-->
        <!--                <input type="text" value="--><?PHP //print $branch; ?><!--" name="gitBranch">-->
        <!--            </div>-->


        <label><br></label>
        <button class="btn btn-success" type="submit">Start Features</button>
        <label><br></label>

        <?php listFolderFiles($localRepo, array('index.php', 'edit_page.php', 'pages', 'full', 'sanity')); //checkmarkValues()?>

        <div class="span2">
            <label><br></label>
            <button class="btn btn-success" type="submit">Start Features</button>
            <label><br></label>
        </div>
    </form>

</div>

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
                    echo '<br />
                    <div name="project" id="project">
                    <strong>' . $file . '</strong>
                    </div>
                    <br />';
                } else {
                    echo '<input type="checkbox" name="feature[]" id="feature" value="' . $folder . '/' . $file . '" >
                        <a href="' . ltrim($localRepo . '/' . $folder . '/' . $file, './') . '"target="_blank">' . $file . '</a><br />';
                }
                if (is_dir($dir . '/' . $file)) listFolderFiles($dir . '/' . $file, $exclude);
            }
        }
    }
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
