<?php
include('includes/header.php'); 
 
if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>

<!DOCTYPE html>
<html lang="en">

<body>

<?php 
#include('includes/header.php'); 
?> 

        <h1>Registration successful!</h1>
        <p>Please return to the <a href="login.php">login page</a></p>

<?php include('includes/footer.php'); ?>

</body>
</html>
