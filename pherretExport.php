<!DOCTYPE html>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gfogelberg
 * Date: 6/12/13
 * Time: 9:48 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();

?>

<html lang="en" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
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


    <table>
        <tbody>
        <tr>
            <td class="span2">
                <form id="returnToList" name="returnToList" method="GET" action="pherret.php">
                    <div class="controls controls-row">
                        <br>
                        <button class="btn btn-primary" type="submit">Return to List</button>
                    </div>
                </form>
            </td>
        </tr>
        </tbody>
    </table>
</div>



    <?php

    exportFile();

    function exportFile()
    {

        $saveFileName = $_GET["exportFilename"].'.csv';
        $features = checkmarkValues();

        $file = fopen($saveFileName,"w");

        foreach ($features as $feature)
        {
            fputcsv($file,explode(',',$feature));
        }

        fclose($file);

        echo "
            <h4>
                Your Test Suite '$saveFileName' has been saved
            </h4>
            ";

    }

function checkmarkValues()
{
    if (isset($_GET['feature'])) {
        $feature = $_GET['feature'];
        return $feature;
    }
}


?>
