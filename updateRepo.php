<?php
sec_session_start();

$repoLoc = '/var/www/helios/tools/regression/features'; //Set to absolute path to behat.yml file in your repository
//shell_exec("cp -rp /var/www/helios/tools/regression/features ./tools/regression && chmod -R 777 ./tools/regression/features");

header("Location: http://pherret.local/pherret.php");

