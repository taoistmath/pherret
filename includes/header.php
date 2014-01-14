<?php
include('includes/head.php');
 
sec_session_start();
 
?>

    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" href="/pherret.php">PHp Engineering Repository REgression Tool (PHERRET)</a>

                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <!--                    <li class="active"><a href="#">Home</a></li>-->
                        <!--                    <li><a href="#about">About</a></li>-->
                        <!--                    <li><a href="#contact">Contact</a></li>-->
                        <?php
                        if (login_check($mysqli) == true) {
                            echo '<li><a href="/includes/logout.php">Sign Out</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<body>
    <h2><img border="0" src="images/ferret.jpg" alt="ferret clip art" width="57" height="50" align="middle">PHERRET</h2>
</body>
