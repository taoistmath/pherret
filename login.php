<!DOCTYPE html>
<html lang="en">

<?php
include('includes/head.php');
 
sec_session_start();
?>

<body>

<?php
include('includes/header.php');

if (isset($_GET['error'])) {
    echo '<p class="error" style="color:red;">Error Logging In! Please verify your Email and Password are correct</p>';
}
?> 

<div class="container">

    <form id="signin" class="form-signin" name="signin" method="POST" action="includes/process_login.php" >
        <input id="email" class="span2" name="email" type="text" class="input-block-level" placeholder="Email">
        <br>
        <input id="password" class="span2" name="password" type="password" class="input-block-level" placeholder="Password">
        <br>
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

