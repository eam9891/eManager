/***********************************************************
 *                                                         *
 *             Created by Ethan on 9/22/2016.              *
 *      This script will get/open/close all modals         *
 *                                                         *
 ***********************************************************/


// Get the modals
var addJobModal = document.getElementById('addJobModal');

// Get the buttons that open the modals
var addJobBtn = document.getElementById("addJobID");

// Get the <span> elements that close the modals
var exitAddJob = document.getElementsByClassName("close")[0];


addJobBtn.onclick = function() {
    addJobModal.style.display = "block";

};

// When the user clicks on <span> (x), close the modals
exitAddJob.onclick = function() {
    addJobModal.style.display = "none";

};

// When the user clicks anywhere outside of the modals, close it
window.onclick = function(event) {
    if (event.target == addJobModal) {
        addJobModal.style.display = "none";

    }
};


// Get the modals
var addWorkerModal = document.getElementById('addJobModal');

// Get the buttons that open the modals
var addWorkerBtn = document.getElementById("addJobID");

// Get the <span> elements that close the modals
var exitAddWorker = document.getElementsByClassName("close")[0];


addWorkerBtn.onclick = function() {
    addWorkerModal.style.display = "block";

};

// When the user clicks on <span> (x), close the modals
exitAddWorker.onclick = function() {
    addWorkerModal.style.display = "none";

};

// When the user clicks anywhere outside of the modals, close it
window.onclick = function(event) {
    if (event.target == addWorkerModal) {
        addWorkerModal.style.display = "none";

    }
};






// Add new job modal on admin.php *Testing*
//\\$(document).ready(function(){
//    $("#addJobBtn").click(function(){
//        $("#addJobModal").modal();
//    });
//});

// Add new user modal **Admin Function Only**
//$(document).ready(function(){
//    $("#addUserBtn").click(function(){
 //       $("#addUserModal").modal();
//    });
//});

