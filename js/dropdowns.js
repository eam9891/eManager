/**
 * Created by Ethan on 9/21/2016.
 */

/* User Account Dropdown */
function accountDropdown() {
    document.getElementById("profileDropdownID").classList.toggle("show");

}
// Close the inboxDropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.profileDropbtn')) {

        var profileDropdown = document.getElementsByClassName("profileDropdownContent");
        var i;
        for (i = 0; i < profileDropdown.length; i++) {
            var openDropdown = profileDropdown[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
};

/* When the user clicks on the button,
 toggle between hiding and showing the inboxDropdown content */
function inboxDropdown() {
    document.getElementById("inboxDropdownID").classList.toggle("show");
}

// Close the inboxDropdown menu if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.inboxDropbtn')) {

        var dropdowns = document.getElementsByClassName("inboxDropdownContent");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}