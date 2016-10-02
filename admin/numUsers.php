<?php


    // First we execute our common code to connection to the database and start the session
    require("../app/connect.php");

    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']['role']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: ../index.php");

        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to ../index.php");
    }


    $query = " SELECT count(*) FROM users ";
    try
    {
        // These two statements run the query against your database table.
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
    catch(PDOException $ex)
    {
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code.
        die("Failed to run query: " . $ex->getMessage());
    }

    // Finally, we can retrieve all of the found rows into an array using fetchAll
    $numberOfUsers = $stmt->fetchColumn();


