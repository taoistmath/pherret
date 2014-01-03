<script>

function checkCheckBoxes(form,action) {
    //Count number of features selected
    var flag = 0;
    for (var i = 0; i< form["feature[]"].length; i++) {
        if(form["feature[]"][i].checked){
            flag ++;
        }
    }
    //Check if count is greater than or equal to 1
    if (flag == 0) {
        alert("You haven\'t chosen any tests to run!");
        return false;
    }

    //Submit the form for running
    submitForm(form,action);
}

function validateField(form,field,action)
{
    var name = form[field].value;

    // Check if empty of not
    if (name === null || name === "") {
        alert("This is a required field.");
        return false;
    }

    //Check if contains Special Chars
    if (/^[a-zA-Z0-9_.]*$/.test(name) == false) {
        alert('Only AlphaNumeric characters are allowed.');
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