<!DOCTYPE html>

<?php include('includes/head.php'); ?>

<?php
session_start();

if ($_SESSION['username'] == NULL)
    header("Location: /login.php");

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
$username = $_SESSION["username"];

//Get the environment
if (isset($_GET['environment'])) {
    $environment = strtolower($_GET['environment']);
}

//Get the browser
if (isset($_GET['browser'])) {
    $browser = strtolower($_GET['browser']);
}

//Set parallel
if (isset($_GET['parallel'])) {
    $parallel = strtolower($_GET['parallel']);
}
?>

<body>

<?php include('includes/header.php'); ?>

<div class="container">

    <h2>PHERRET</h2>

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

    <form id="featureFilter" name="featureFilter" method="GET">

        <div class="row">
            <div class="span2">
                <p>Environment</p>
            </div>
            <div class="span2">
                <p>Browser</p>
            </div>
            <div class="span2">
                <p># of Browsers</p>
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
                <option <?php if ($_GET['browser'] == 'Chrome') { ?>selected="true"  <?php }; ?>value="Chrome">
                    Chrome
                </option>
            </select>

           <input class="span2" type="text" id="parallel" name="parallel" value="1">

        </div>

        <label><br></label>
        <button class="btn btn-success" type="submit" onclick="submitForm('pherretResults.php')">Start Features</button>
        <label><br></label>

        <ul class="tree" style="margin-left: 15px;">
            <?php listFolderFiles($localRepo, array('index.php', 'edit_page.php', 'pages', 'full', 'sanity')); //checkmarkValues()?>
        </ul>

        <label><br></label>
        <button class="btn btn-success" type="submit" onclick="submitForm('pherretResults.php')">Start Features</button>
        <label><br></label>

        <table>
            <tbody>
            <tr>
                <td class="span2">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="importFilename" name="importFilename" placeholder="Test Suite Filename">
                                <span class="input-group-btn">
                                  <button class="btn btn-default" type="submit" onclick="return validateImport()">Run Test Suite</button>
                                </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->
                </td>

                <td class="span2">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="exportFilename" name="exportFilename" placeholder="Test Suite Filename">
                                <span class="input-group-btn">
                                  <button class="btn btn-default" type="submit" onclick="return validateExport()">Save Test Suite</button>
                                </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->
                </td>
            </tr>
            </tbody>
        </table>

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

?>

<?php include('includes/footer.php'); ?>

<script>
$(function () {
    $('ul.tree').checkTree();
});

function submitForm(action)
{
    document.getElementById('featureFilter').action = action;
    document.getElementById('featureFilter').submit();
}

function validateExport()
{
    var filename = document.forms["featureFilter"]["exportFilename"].value;
    
    // Check if empty of not
    if (filename === null || filename === ""){
        alert("Test Suite name cannot be blank");
        return false;
    }

    //Check if contains Special Chars
    else if (/^[a-zA-Z0-9_.]*$/.test(filename) == false) {
        alert('Test Suite name contains illegal characters.\n Only AlphaNumeric characters are allowed.');
        return false;
    }

    //submit the form for export
    else
        submitForm('pherretExport.php')
}

function validateImport()
{
    var filename = document.forms["featureFilter"]["importFilename"].value;
    
    // Check if empty of not
    if (filename === null || filename === ""){
        alert("Test Suite name cannot be blank");
        return false;
    }

    //Check if contains Special Chars
    else if (/^[a-zA-Z0-9_.]*$/.test(filename) == false) {
        alert('Test Suite name contains illegal characters.\n Only AlphaNumeric characters are allowed.');
        return false;
    }

    //submit the form for export
    else
        submitForm('pherretResults.php')
}
</script>

</body>
</html>