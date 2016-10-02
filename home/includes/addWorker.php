<?php

// First we execute our common code to connection to the database and start the session
require("../../app/connect.php");

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']))
{
    // If they are not, we redirect them to the login page.
    header("Location: ../../index.php");

    // Remember that this die statement is absolutely critical.  Without it,
    // people can view your members-only content without logging in.
    die("Redirecting to ../../index.php");
}

// Set post details
$workerUsername = $_POST['workerUsername'];
$workerTitle = $_POST['workerTitle'];
$workerEmail = $_POST['workerEmail'];

// Set GET details


// This if statement checks to determine whether the registration form has been submitted
// If it has, then the registration code is run, otherwise the form is displayed
if(!empty($_POST))
{
    // Ensure that the user has entered a non-empty email
    if(empty($_POST['workerEmail']))
    {
        // Note that die() is generally a terrible way of handling user errors
        // like this.  It is much better to display the error with the form
        // and allow the user to correct their mistake.  However, that is an
        // exercise for you to implement yourself.
        die("Please enter the email address of the person you want to request.");
    }

    // Ensure that the user has entered a name
    if(empty($_POST['workerTitle']))
    {
        die("Please enter a title.");
    }


    // We must first add the newly created user to the database
    $query = "
        INSERT INTO users (
            username,
            title,
            email
        ) VALUES (
            :username,
            :title,
            :email
        )
    ";
    $query_params = array(
        ':username' => $workerUsername,
        ':title' => $workerTitle,
        ':email' => $workerEmail
    );
    try
    {
        // Execute the query to create the user
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        // Change this !!!!!
        die("Failed to run query: " . $ex->getMessage());
    }


    // Next we can retrieve the id from the newly created user so we can add that to the relation table
    $query1 = "
        SELECT
            *
        FROM 
            users 
        WHERE
            username = :username
            
    ";
    $query_params1 = array(
        ':username' => $workerUsername
    );
    try
    {
        // Execute the query to create the user
        $stmt = $db->prepare($query1);
        $result = $stmt->execute($query_params1);
    }
    catch(PDOException $ex)
    {
        // Change this !!!!!
        die("Failed to run query: " . $ex->getMessage());
    }
    $userTwo = $stmt->fetch();


    // Get the job ID
    $jobID = $_GET['jobID'];

    if ( !empty($jobID)) {
        $jobID = $_REQUEST['jobID'];
    }

// Update workerRequests to add newly created user to job
    $query2 = <<<TAG

        INSERT INTO workerRequests (
            userOne,
            userTwo,
            status,
            actionUserID,
            jobID
        ) VALUES (
            :userOne,
            :userTwo,
            :status,
            :actionUserID,
            :jobID
        )

TAG;




// Before we prepare the tokens, we will set our variables
    $userID = $_SESSION['user']['userID'];
    $userTwoID = $userTwo['userID'];


// Here we prepare our tokens for insertion into the SQL query.
    $query_params2 = array(
        ':userOne' => $userID,
        ':userTwo' => $userTwoID,
        ':status' => 1, // 1 = Accepted Request
        ':actionUserID' => $userID,
        ':jobID' => $jobID
    );

    try
    {
        // Execute the query to create the user
        $stmt = $db->prepare($query2);
        $result = $stmt->execute($query_params2);
    }
    catch(PDOException $ex)
    {
        // Change this !!!!!
        die("Failed to run query: " . $ex->getMessage());
    }




// This redirects the user back to the myJobs page
    header("Location: ../manageJob.php?jobID=$jobID");

// Calling die or exit after performing a redirect using the header function is critical.
//  The rest of your PHP script will continue to execute and will be sent to the user if you do not die or exit.
    die("Redirecting to ../manageJob.php?jobID=$jobID");



}










