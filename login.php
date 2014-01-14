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

<?php
include('includes/header.php');

if (isset($_GET['error'])) {
    echo '<p class="error">Error Logging In!</p>';
}
?> 

<div class="container">

    <form id="signin" class="form-signin" name="signin" method="POST" action="includes/process_login.php" >
        <input id="email" class="span2" name="email" type="text" class="input-block-level" placeholder="Email">
        <br>
        <span style="color:red;" id="usernameError"></span>
        <input id="password" class="span2" type="password" class="input-block-level" placeholder="Password">
<!--         <label class="checkbox">
            <input type="checkbox" value="remember-me"> Remember me
        </label> -->
        <label></label>
        <button class="btn btn-primary" type="submit" onclick="formhash(this.form, this.form.password);">Sign in</button>
        <!-- <button class="btn btn-primary" type="submit" onclick="return validateField(this.form,'username','setSession.php')">Sign in</button> -->
    </form>
    <p>If you don't have an account, please <a href="register.php">register</a></p>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>

