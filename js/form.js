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
    var hasNoErrors = true;

    if(username.value == '' || password.value == '' || email.value == '' || conf.value == ''){
        displayError('Please fill in all the details.');
        username.focus();
        return false;
    }

    //check username
    regex = /^\w+$/;
    if(!regex.test(username.value)){
        displayError("The username must contain only letters, numbers and underscores.");
        username.focus();
        hasNoErrors = false;
    }
    if (username.value.length < 4 || username.value.length > 16) {
        displayError('The username must be between 4 and 16 characters long.');
        username.focus();
        hasNoErrors = false;
    }

    //check email
    regex = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,8})$/;
    if(!regex.test(email.value)){
        displayError("Please enter a valid email address.");
        email.focus();
        hasNoErrors = false;
    }

    //check password
    if (password.value.length < 6 || password.value.length > 16) {
        displayError('The password must be between 6 and 16 characters long.');
        password.focus();
        hasNoErrors = false;
    }

    // Check password and confirmation are the same
    if (password.value != conf.value) {
        displayError('Your password and confirmation do not match.');
        password.focus();
        hasNoErrors = false;
    }

    return hasNoErrors;
}

function validatePost(form, title, author, url, references){
    clearErrors();
    var hasNoErrors = true;

    //presence check
    if(title.value == '' || url.value == '' || references.value == '' || author.value == ''){
        displayError('Please fill in all the fields.');
        title.focus();
        return false;
    }
    //check title
    if(title.value.length > 200){
        displayError('The article title may not exceed 200 characters.');
        title.focus();
        hasNoErrors = false;
    }

    //check url
    //by imme_emosol: https://mathiasbynens.be/demo/url-regex
    var regex = /(https?|ftp):\/\/(-\.)?([^\s/?\.#-]+\.?)+(\/[^\s]*)?/;

    if (!regex.test(url.value)) {
        displayError('Please enter a valid URL of the article.');
        url.focus();
        hasNoErrors = false;
    }

    return hasNoErrors;
}

function validateSuggestion(form, correction){
    clearErrors();
    //presence check
    if(correction.value == ''){
        displayError('Please do not leave the correction field blank.');
        correction.focus();
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
