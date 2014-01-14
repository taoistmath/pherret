function formhash(form, password) {
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
 
    // Finally submit the form. 
    form.submit();
}

function regformhash(form, uid, email, password, conf) {
    var errorCt = 0;
    
    //Check Username
    re = /^\w+$/; 
    // Check the field has a value
    if (checkBlankField(uid) == false) {
        ++errorCt;
    } 
    // Check that the username is sufficiently long (min 3 chars)
    else if (uid.value.length < 3) {
        document.getElementById('usernameError').innerHTML='Usernames must be at least 3 characters long. Please try again';
        ++errorCt;
    } 
    // Check that the username is only digits, upper and lower case letters and underscores
    else if (!re.test(uid.value)) { 
        document.getElementById('usernameError').innerHTML='Username must contain only letters, numbers and underscores. Please try again'; 
        ++errorCt;
    }
    else { 
        document.getElementById('usernameError').innerHTML=''; 
    }

    //Check Email
    // Check the field has a value
    if (checkBlankField(email) == false) {
        ++errorCt;
    }

    //Check Password
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    // Check the field has a value
    if (checkBlankField(password) == false) {
        ++errorCt;
    }
    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    else if (password.value.length < 6) {
        document.getElementById('passwordError').innerHTML='Passwords must be at least 6 characters long. Please try again';
        ++errorCt;
    }
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
    else if (!re.test(password.value)) {
        document.getElementById('passwordError').innerHTML='Passwords must contain at least one number, one lowercase and one uppercase letter. Please try again';
        ++errorCt;
    }
    else {
        document.getElementById('passwordError').innerHTML='';
    }

    //Check Password Confirmation
    // Check the field has a value
    if (checkBlankField(conf) == false) {
        ++errorCt;
    }
    // Check password and confirmation are the same
    else if (password.value != conf.value) {
        document.getElementById('confirmpwdError').innerHTML='Your password and confirmation do not match. Please try again';
        ++errorCt;
    }
    else {
        document.getElementById('confirmpwdError').innerHTML='';
    }

    if (errorCt > 0) {
        form.username.focus();
        return false
    }

    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";
 
    // Finally submit the form. 
    form.submit();
    return true;
}

function checkBlankField(field) {
    var blankErrorMsg = '*This is a required field.';
    
    if (field.value === null || field.value === '') {
        var errMsg = field.name + "Error";
        document.getElementById(errMsg).innerHTML=blankErrorMsg;
        return false;
    }
}
