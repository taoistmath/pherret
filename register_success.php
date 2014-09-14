<!DOCTYPE html>
<html lang="en">

<?php
include('includes/head.php'); 
 
sec_session_start();
 
if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>

<body>

<?php include('includes/header.php'); ?> 

        <h1>Registration successful!</h1>
        <p>Please return to the <a href="index.php">login page</a></p>

<?php include('includes/footer.php'); ?>

</body>
</html>
