<?php

// First we execute our common code to connection to the database and start the session
require("../app/connect.php");

// At the top of the page we check to see whether the user is an administrator
if(empty($_SESSION['user']['role']))
{
    // If they are not, we redirect them to the login page.
    header("Location: ../index.php");

    // Remember that this die statement is absolutely critical.  Without it,
    // people can view your members-only content without logging in.
    die("Redirecting to ../index.php");
}
// Everything below this point in the file is secured by the login system



// We can retrieve a list of members from the database using a SELECT query.
// In this case we do not have a WHERE clause because we want to select all of the rows from the table.
$query = "
        SELECT
            *
        FROM users
    ";


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
$rows = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ESerV</title>

    <link href="../css/template.css" rel="stylesheet">
    <link href="../css/profileDropdown.css" rel="stylesheet">
    <link href="../css/forms.css" rel="stylesheet">
    <link href="../css/iconBar.css" rel="stylesheet">
    <link href="../css/e_panel.css" rel="stylesheet">
    <link href="../css/modals.css" rel="stylesheet">
    <link href="../css/tables.css" rel="stylesheet">
    <link href="../css/tooltips.css" rel="stylesheet">
    <link href="../css/buttons.css" rel="stylesheet">
    <link href="../css/dropdowns.css" rel="stylesheet">

    <link href="admin.css" rel="stylesheet">

    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">


</head>
<header>
    <!-- Site Title -->
    <div class="siteTitle">
        <a href="admin.php">
            <i class="fa fa-diamond"></i>
            <span>ESerV</span>
        </a>
    </div>

    <!-- User profile with dropdown menu -->
    <div class="profileDropdown">
        <button onclick="accountDropdown()" class="profileDropbtn user-profile">
            <img src="../images/user.png" alt="">
            <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>
        </button>
        <div id="profileDropdownID" class="profileDropdownContent">
            <a href="#">Profile<span class="badge bg-red pull-right">50%</span></a>
            <a href="../home/edit_account.php">Settings</a>
            <a href="../home/logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
        </div>
    </div>

    <!-- Inbox dropdown with links to messages -->
    <div class="inboxDropdown" style="float:right;">
        <button class="inboxDropBtn">
            <i class="fa fa-envelope-o"></i>
            <span class="badge bg-green"> 6 </span>
        </button>
        <div class="inboxDropdownContent">
            <ul class="">
                <li>
                    <a href="#">
                        <span class="image"><img src="../images/user.png" alt="Profile Image" /></span>
                        <span>John Smith</span>
                        <span class="time">3 mins ago</span><br>
                        <span class="message">
                            Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="image"><img src="../images/user.png" alt="Profile Image" /></span>
                        <span>John Smith</span>
                        <span class="time">3 mins ago</span><br>
                        <span class="message">
                            Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                    </a>
                </li>
            </ul>


        </div>
    </div>


</header>
<nav class="icon-bar">
    <a class="active" href="admin.php">
        <i class="fa fa-tachometer"></i>
        <div class="icon-bar-item">Dash</div>
    </a>
    <a href="myInbox.php">
        <i class="fa fa-envelope"></i>
        <div class="icon-bar-item">Inbox</div>
    </a>
    <a href="../home/myJobs.php">
        <i class="fa fa-area-chart"></i>
        <div class="icon-bar-item">Jobs</div>
    </a>
    <a href="tables.php">
        <i class="fa fa-table"></i>
        <div class="icon-bar-item">Workers</div>
    </a>
    <a href="#">
        <i class="fa fa-cloud"></i>
        <div class="icon-bar-item">Billing</div>
    </a>
    <a href="#">
        <i class="fa fa-database"></i>
        <div class="icon-bar-item">Database</div>
    </a>
    <a href="#">
        <i class="fa fa-server"></i>
        <div class="icon-bar-item">Server</div>
    </a>
</nav>
<body>
<main>
    <div id="wrapper">

        <!-- Users table -->
        <div class="e_content">
            <?php if(empty($rows)): ?>
                <h1>No members were found!</h1>
            <?php else: ?>
                <div class="e_panel">
                    <div class="e_title">
                        <button class="btn pull-right" id="addUserBtn">Add User</button>
                        <h2>Registered Users</h2>

                        <!-- Create new user modal -->
                        <div class="modal fade" id="addUserModal" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content -->
                                <div class="modal-content">
                                    <div class="modal-header" style="padding:35px 50px;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h5>
                                            <span class="glyphicon glyphicon-lock"></span>
                                            Add a new user!
                                        </h5>
                                    </div>
                                    <div class="modal-body" style="padding:40px 50px;">
                                        <form action="create.php" method="post">
                                            <div class="form-group">
                                                <label for="username">
                                                    <span class="glyphicon glyphicon-user"></span>Enter a Username
                                                </label>
                                                <input type="text" class="form-control" name="username"
                                                       placeholder="Enter a username">
                                            </div>
                                            <div class="form-group">
                                                <label for="email">
                                                    <span class="glyphicon glyphicon-eye-open"></span>Enter an Email Address
                                                </label>
                                                <input type="email" class="form-control" name="email"
                                                       placeholder="Enter a valid email address">
                                            </div>
                                            <div class="form-group">
                                                <label for="psw">
                                                    <span class="glyphicon glyphicon-eye-open"></span>Enter a Password
                                                </label>
                                                <input type="password" class="form-control" name="password"
                                                       placeholder="Enter a password">
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block">
                                                <span class="glyphicon glyphicon-off"></span>Register
                                            </button>
                                        </form>
                                    </div>
                                    <div class="modal-footer"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /Create new user modal -->

                        <!-- Search box
                        <div class="e_searchBox">
                            <input type="text" class="form-control" placeholder="Search for...">
                        </div>
                        -->




                    </div>

                    <div class="e_content">

                        <table id="table">
                            <thead>
                            <tr>
                                <th class="column-title">ID </th>
                                <th class="column-title">Username </th>
                                <th class="column-title">Email </th>
                                <th class="column-title">Actions </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rows as $key => $value): ?>
                                <tr>
                                    <td><?= htmlentities($value['userID'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlentities($value['username'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlentities($value['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a class="btn btnOrange toolTip Top"
                                           href="../home/edit_account.php?id=<?= htmlentities($value['id'], ENT_QUOTES, 'UTF-8') ?>">
                                            <span class="fa fa-pencil" aria-hidden='true' style="color: black"></span>
                                            <span class="toolTipText Top"> Edit User </span>
                                        </a>
                                        <a class="btn btnRed toolTip Top"
                                           href="delete.php?id=<?= htmlentities($value['id'], ENT_QUOTES, 'UTF-8') ?>">
                                            <span class='fa fa-trash' aria-hidden='true' style="color: black"></span>
                                            <span class="toolTipText"> Delete User </span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                </div>
            <?php endif; ?>
        </div>
        <!-- /Users table -->



    </div>
    <script src="../js/dropdowns.js"></script>
    <script src="../js/modals.js"></script>
</body>
</html>