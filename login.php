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

<script>
$(function () {
    $('ul.tree').checkTree();
});

function validateField(form,field,action)
{
    var name = form[field].value;

    // Check if empty of not
    if (name === null || name === "") {
        alert("Username is a required field.");
        return false;
    }

    //Check if contains Special Chars
    if (/^[a-zA-Z0-9_.]*$/.test(name) == false) {
        alert('Your username contains illegal characters.\nOnly AlphaNumeric characters are allowed.');
        return false;
    }

    //Submit the form for running
    submitForm(form,action)
}

function submitForm(form,action)
{
    form.action = action;
    form.submit();
}

</script>

</body>
</html>
