<?php

    // First we execute our common code to connection to the database and start the session
    require("../authentication/connect.php");

    // Check to make sure the login credentials are correct
    require("../authentication/checkLogin.php");
    
    // This if statement checks to determine whether the edit form has been submitted
    // If it has, then the account updating code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
        // Make sure the user entered a valid E-Mail address
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            die("Invalid E-Mail Address");
        }
        
        // If the user is changing their E-Mail address, we need to make sure that
        // the new value does not conflict with a value that is already in the system.
        // If the user is not changing their E-Mail address this check is not needed.
        if($_POST['email'] != $_SESSION['user']['email'])
        {
            // Define our SQL query
            $query = "
                SELECT
                    1
                FROM users
                WHERE
                    email = :email
            ";
            
            // Define our query parameter values
            $query_params = array(
                ':email' => $_POST['email']
            );
            
            try
            {
                // Execute the query
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }
            catch(PDOException $ex)
            {
                // Note: On a production website, you should not output $ex->getMessage().
                // It may provide an attacker with helpful information about your code. 
                die("Failed to run query: " . $ex->getMessage());
            }
            
            // Retrieve results (if any)
            $row = $stmt->fetch();
            if($row)
            {
                die("This E-Mail address is already in use");
            }
        }
        
        // If the user entered a new password, we need to hash it and generate a fresh salt
        // for good measure.
        if(!empty($_POST['password']))
        {
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            $password = hash('sha256', $_POST['password'] . $salt);
            for($round = 0; $round < 65536; $round++)
            {
                $password = hash('sha256', $password . $salt);
            }
        }
        else
        {
            // If the user did not enter a new password we will not update their old one.
            $password = null;
            $salt = null;
        }
        
        // Initial query parameter values
        $query_params = array(
            ':email' => $_POST['email'],
            ':user_id' => $_SESSION['user']['id'],
        );
        
        // If the user is changing their password, then we need parameter values
        // for the new password hash and salt too.
        if($password !== null)
        {
            $query_params[':password'] = $password;
            $query_params[':salt'] = $salt;
        }
        
        // Note how this is only first half of the necessary update query.  We will dynamically
        // construct the rest of it depending on whether or not the user is changing
        // their password.
        $query = "UPDATE users SET email = :email";
        
        // If the user is changing their password, then we extend the SQL query
        // to include the password and salt columns and parameter tokens too.
        if($password !== null)
        {
            $query .= "
                , password = :password
                , salt = :salt
            ";
        }
        
        // Finally we finish the update query by specifying that we only wish
        // to update the one record with for the current user.
        $query .= "
            WHERE
                id = :user_id
        ";
        
        try
        {
            // Execute the query
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code. 
            die("Failed to run query: " . $ex->getMessage());
        }
        
        // Now that the user's E-Mail address has changed, the data stored in the $_SESSION
        // array is stale; we need to update it so that it is accurate.
        $_SESSION['user']['email'] = $_POST['email'];


        // Once all of the processing is done we can redirect users based on their role
        // If the session role returns true (1) they are an admin
        if($_SESSION['user']['role']) {

            // Redirect appropriately
            header("Location: ../admin/admin.php");
            die("Redirecting to ../admin/admin.php");
        }

        // This redirects the user back to their page
        header("Location: home.php");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to home.php");
    }
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ESerV - Edit Account</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


        <link rel="stylesheet" type="text/css" href="../css/modals.css">
    </head>
    <body>
        <header>

            <!-- Todo: implement avatars here somewhere -->

            <!-- Display the users name and make it the dropdown button -->
            <div id="userName">
                <div class="dropdown">
                    <div class="dropbtn">
                        Hello <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>
                        <img src="../images/downarrow.png" class="buttonIcon">
                    </div>
                    <!-- Dropdown menu bar content -->
                    <div class="dropdown-content">
                        <a href="edit_account.php">Edit Account</a>
                        <a href="../admin/memberlist.php">Memberlist</a>

                    </div>
                </div>
            </div>
        </header>

        <main>
            <h1>Edit Account</h1>
            <form action="edit_account.php" method="post">
                Username:<br />
                <b><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></b>
                <br /><br />
                E-Mail Address:<br />
                <label>
                    <input type="text"
                          name="email"
                          value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>"/>

                </label>
                <br /><br />
                Password:<br />
                <label>
                   <input type="password" name="password" value=""/>
                </label><br />
                <i>(leave blank if you do not want to change your password)</i>
                <br /><br />
                <input type="submit" value="Update Account" />
           </form>
        </main>

	</body>
</html>