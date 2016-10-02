/**
 * Created by Ethan on 9/23/2016.
 */

function validateLogin() {
    var validateLoginUsername = document.getElementById("username");
    var validateLoginPassword = document.getElementById("password");

    if ( validateLoginUsername.checkValidity() == false || validateLoginPassword.checkValidity() == false) {
        document.getElementById("demo").innerHTML = validateLoginUsername.validationMessage;
        document.getElementById("demo").innerHTML = validateLoginPassword.validationMessage;
    }
}
