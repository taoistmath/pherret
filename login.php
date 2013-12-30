<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>

<?php include('includes/header.php'); ?>

<div class="container">

    <h2>PHERRET</h2>

    <form id="signin" class="form-signin" name="signin" method="POST" action="setSession.php" onsubmit="return validateLoginForm();">
        <input id = "username" class="span2" type="text" class="input-block-level" placeholder="username" name="username">
<!--        <input type="password" class="input-block-level" placeholder="Password">-->
<!--        <label class="checkbox">-->
<!--            <input type="checkbox" value="remember-me"> Remember me-->
<!--        </label>-->
        <label></label>
        <button class="btn btn-primary" type="submit">Sign in</button>
    </form>

</div>

<?php include('includes/footer.php'); ?>

<script>
    $(function () {
        $('ul.tree').checkTree();
    });

    $('form').submit(function () {

        // Get the Login Name value and trim it
        var name = $.trim($('#username').val());

        // Check if empty of not
        if (name  === '') {
            alert('Username is a required field.');
            return false;
        }

        //Check if contains Special Chars
        if(/^[a-zA-Z0-9_.]*$/.test(name) == false) {
            alert('Your username contains illegal characters.\n Only AlphaNumeric characters are allowed.');
            return false;
        }
    });
</script>

</body>
</html>
