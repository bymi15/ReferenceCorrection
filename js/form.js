function clearErrors(){
    //clear existing messages
    var node = document.getElementById("error_message");
    while(node.firstChild){
        node.removeChild(node.firstChild);
    }
}
function displayError(err){
    var msg = document.createElement("p");
    msg.setAttribute("class", "error");
    msg.innerHTML = err;

    var node = document.getElementById("error_message");
    node.appendChild(msg);
}

function validateLogin(form, username, password){
    clearErrors();
    if(username.value == '' || password.value == ''){
        displayError('Please enter your username and password');
        return false;
    }
    return true;
}

function validateRegistration(form, username, email, password, conf){
    clearErrors();
    if(username.value == '' || password.value == '' || email.value == '' || conf.value == ''){
        displayError('Please fill in all the details.');
        return false;
    }

    //check username
    regex = /^\w+$/;
    if(!regex.test(form.username.value)){
        displayError("The username must contain only letters, numbers and underscores. Please try again.");
        form.username.focus();
        return false;
    }

    //check password
    if (password.value.length < 6) {
        displayError('The password must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }

    // Check password and confirmation are the same
    if (password.value != conf.value) {
        displayError('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    return true;
}

function genHash(form) {
    if(validateLogin(form, form.username, form.password)){
        // Create a new element input, this will be our hashed password field.
        var p = document.createElement("input");

        // Add the new element to our form.
        form.appendChild(p);
        p.name = "p";
        p.type = "hidden";
        p.value = hex_sha512(password.value);

        // Make sure the plaintext password doesn't get sent.
        form.password.value = "";

        // Finally submit the form.
        form.submit();
        return true;
    }else{
        return false;
    }
}

function genRegHash(form, username, email, password, conf) {
    if(validateRegistration(form, username, email, password, conf)){
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
    }else{
        return false;
    }
}
