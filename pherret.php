<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'helpers/variables.php';

sec_session_start();

////Get the branch
//$branch = $_GET['gitBranch'];
//if ($branch == NULL)
//    $branch = 'INFRASYS-1913-Stable'; //Set to same branch as repository.

//Set up paths
//$repoLoc = '/var/www/helios/tools/regression/features/dandb/mixed_stack'; //Set to absolute path to behat.yml file in your repository
//shell_exec("cp -r " . $repoLoc . " ./tools/regression/features/dandb");

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

<!DOCTYPE html>

<body>

<?php include('includes/header.php'); ?>

<?php if (login_check($mysqli) == true) : ?>

<div class="container">

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
             <button class="btn btn-success" type="submit" onclick="return checkCheckBoxes(this.form,'pherretResults.php')">Start Features</button>
        <label><br></label>

        <ul class="tree" style="margin-left: 15px;">
            <?php listFolderFiles($localRepo, array('index.php', 'edit_page.php', 'pages', 'full', 'sanity')); //checkmarkValues()?>
        </ul>

        <label><br></label>
             <button class="btn btn-success" type="submit" onclick="return checkCheckBoxes(this.form,'pherretResults.php')">Start Features</button>
        <label><br></label>

 
        <div class="form-horizontal">
            <div class="row">
                <div class="span4">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control span2" id="importFilename" name="importFilename" placeholder="Test Suite Filename">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit" onclick="return validateField(this.form,'importFilename','pherretResults.php')">Run Test Suite</button>
                                <span style="color:red;" id="importFilenameError"></span>
                                <br><br>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-horizontal">
            <div class="row">
                <div class="span4">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control span2" id="exportFilename" name="exportFilename" placeholder="Test Suite Filename">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit" onclick="return validateField(this.form,'exportFilename','pherretExport.php')">Save Test Suite</button>
                                <span style="color:red;" id="exportFilenameError"></span>
                                <br>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>

<?php else : ?>
    <p>
        <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
    </p>
<?php endif; ?>


<?php include('includes/footer.php'); ?>

</body>
</html>

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

<script>

$(function () {
    $('ul.tree').checkTree();
});

</script>