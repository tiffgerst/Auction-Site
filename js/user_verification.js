import { showError } from "./utilities.js";

const newUser = document.getElementById('createNewUser');

newUser.addEventListener('submit', function(event) {
    event.preventDefault();

    var password = newUser.elements['password'];
    var passwordConfirmation = newUser.elements['passwordConfirmation'];
    var email = newUser.elements['email'];

    if (password.value != passwordConfirmation.value) {
        showError(password,"Passwords do not match.");
        showError(passwordConfirmation,"Passwords do not match.");
    }

    else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email.value)) {
        showError(email,"Invalid email address.");
    }

    else {
        newUser.submit();
    }
});