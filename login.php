<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>

<?php include('includes/header.php'); ?>

<div class="container">

    <form id="signin" class="form-signin" name="signin" method="POST">
        <input id = "username" class="span2" name="username" type="text" class="input-block-level" placeholder="username">
        <br>
        <span style="color:red;" id="usernameError"></span>
<!--        <input type="password" class="input-block-level" placeholder="Password">-->
<!--        <label class="checkbox">-->
<!--            <input type="checkbox" value="remember-me"> Remember me-->
<!--        </label>-->
        <label></label>
        <button class="btn btn-primary" type="submit" onclick="return validateField(this.form,'username','setSession.php')">Sign in</button>
    </form>

</div>

<?php include('includes/footer.php'); ?>

</body>
</html>