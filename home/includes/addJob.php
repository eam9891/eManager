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

// This if statement checks to determine whether the registration form has been submitted
// If it has, then the registration code is run, otherwise the form is displayed
if(!empty($_POST))
{
    // Ensure that the user has entered a non-empty job title
    if(empty($_POST['jobTitle']))
    {
        // Note that die() is generally a terrible way of handling user errors
        // like this.  It is much better to display the error with the form
        // and allow the user to correct their mistake.  However, that is an
        // exercise for you to implement yourself.
        die("Please enter a title.");
    }

    // Ensure that the user has entered a non-empty password
    if(empty($_POST['jobState']))
    {
        die("Please enter a state.");
    }

    // This query will insert all of the job details into a row in the jobs table.
    // We will also insert the id of the current user as the jobAdmin
    $query = <<<TAG

        INSERT INTO jobs (
            jobTitle,
            jobState,
            jobTown,
            jobAddress,
            jobAdmin
        ) VALUES (
            :jobTitle,
            :jobState,
            :jobTown,
            :jobAddress,
            :jobAdmin
        )
    
TAG;

    // Before we prepare the tokens, we must designate the jobAdmin as the current user
    $jobAdmin = $_SESSION['user']['userID'];

    // Here we prepare our tokens for insertion into the SQL query.
    $query_params = array(
        ':jobTitle' => $_POST['jobTitle'],
        ':jobState' => $_POST['jobState'],
        ':jobTown' => $_POST['jobTown'],
        ':jobAddress' => $_POST['jobAddress'],
        ':jobAdmin' => $jobAdmin
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

    // This redirects the user back to the myJobs page
    header("Location: ../myJobs.php");

    // Calling die or exit after performing a redirect using the header function is critical.
    //  The rest of your PHP script will continue to execute and will be sent to the user if you do not die or exit.
    die("Redirecting to ../myJobs.php");
}


