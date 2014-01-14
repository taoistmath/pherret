<!DOCTYPE html>
<html lang="en">

<?php
include('includes/head.php'); 
include_once 'includes/register.inc.php';
 ?>

<body>

<?php
include('includes/header.php');
?> 

<div class="container">

<?php
if (!empty($error_msg)) {
    echo $error_msg;
}
?>
    <ul>
        <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
        <li>Emails must have a valid email format</li>
        <li>Passwords must be at least 6 characters long</li>
        <li>Passwords must contain
            <ul>
                <li>At least one upper case letter (A..Z)</li>
                <li>At least one lower case letter (a..z)</li>
                <li>At least one number (0..9)</li>
            </ul>
        </li>
        <li>Your password and confirmation must match exactly</li>
    </ul>

    <form id="registration" class="form-signin" name="registration" method="post" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" >
        <input id="username" class="span2" name="username" type="text" class="input-block-level" placeholder="Username" />
        <br>
        <input id="email" class="span2" name="email" type="text" class="input-block-level" placeholder="Email" />
        <br>
        <input id="password" class="span2" name="password" type="password" class="input-block-level" placeholder="Password" />
        <br>
        <input id="confirmpwd" class="span2" name="confirmpwd" type="password" class="input-block-level" placeholder="Confirm Password" />
        <br>
        <button class="btn btn-primary" type="submit" 
            onclick="return regformhash(this.form, 
                            this.form.username, 
                            this.form.email, 
                            this.form.password, 
                            this.form.confirmpwd);">Register</button>
    </form>
    
    <p>Return to the <a href="login.php">login page</a>.</p>

</div>

<?php include('includes/footer.php'); ?>

</body>
</html>

