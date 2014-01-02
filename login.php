<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>

<?php include('includes/header.php'); ?>

<div class="container">

    <h2>PHERRET</h2>

    <form id="signin" class="form-signin" name="signin" method="POST">
        <input id = "username" class="span2" type="text" class="input-block-level" placeholder="username" name="username">
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
