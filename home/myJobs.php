<?php

// First we execute our common code to connect to the database and start the session
require("../app/config.php");

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']))
{
    // If they are not, we redirect them to the login page.
    header("Location: ../../index.php");

    // Remember that this die statement is absolutely critical.  Without it,
    // people can view your members-only content without logging in.
    die("Redirecting to ../../index.php");
}

// Set the current users ID
$userID = $_SESSION['user']['userID'];

// This query returns all rows from the jobs table where the user id matches the jobAdmin.
$query = <<<TAG

        SELECT * 
        FROM jobs 
        WHERE jobAdmin = :userID
    
TAG;


// The parameter values
$query_params = array(
    ':userID' => $userID
);

try
{
    // Execute the query against the database
    $stmt = $db->prepare($query);
    $result = $stmt->execute($query_params);
}
catch(PDOException $ex)
{
    // Note: On a production website, you should not output $ex->getMessage().
    // It may provide an attacker with helpful information about your code.
    die("Failed to run query: " . $ex->getMessage());
}

// Finally, we can retrieve all of the found rows into an array using fetchAll
$jobRows = $stmt->fetchAll();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ESerV</title>


    <link href="../css/template.css" rel="stylesheet">
    <link href="../css/forms.css" rel="stylesheet">
    <link href="../css/iconBar.css" rel="stylesheet">
    <link href="../css/e_panel.css" rel="stylesheet">
    <link href="../css/modals.css" rel="stylesheet">
    <link href="../css/dropdowns.css" rel="stylesheet">
    <link href="../css/profileDropdown.css" rel="stylesheet">
    <link href="../css/buttons.css" rel="stylesheet">
    <link href="../css/tables.css" rel="stylesheet">
    <link href="../css/tooltips.css" rel="stylesheet">

    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">


</head>
<header>
    <!-- Site Title -->
    <div class="siteTitle">
        <a href="home.php">
            <i class="fa fa-diamond"></i>
            <span>ESerV</span>
        </a>
    </div>

    <!-- User profile with dropdown menu -->
    <div class="profileDropdown">
        <button onclick="accountDropdown()" class="profileDropbtn user-profile">
            <img src="../../images/user.png" alt="">
            <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>
        </button>
        <div id="profileDropdownID" class="profileDropdownContent">
            <a href="#">Profile<span class="badge bg-red pull-right">50%</span></a>
            <a href="account-settings.php">Settings</a>
            <a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
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


    <!--<div class="inboxDropdown">
        <button onclick="inboxDropdown()" class="inboxDropbtn info-number">
            <i class="fa fa-envelope-o"></i>
            <span class="badge bg-green"> 6 </span>
        </button>
        <div id=inboxDropdownID class="inboxDropdownContent msg_list list_unstyled" role="menu">

           -->

</header>
<nav class="icon-bar">
    <a class="active" href="home.php">
        <i class="fa fa-tachometer"></i>
        <div class="icon-bar-item">Dash</div>
    </a>
    <a href="myInbox.php">
        <i class="fa fa-envelope"></i>
        <div class="icon-bar-item">Inbox</div>
    </a>
    <a href="myJobs.php">
        <i class="fa fa-area-chart"></i>
        <div class="icon-bar-item">Jobs</div>
    </a>
    <a href="myWorkers.php">
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
        <div class="e_panel">
            <div class="e_title">

                <button class="btn btnGreen pull-right" id="addJobID"> Create A New Job </button>
                <h2>My Jobs</h2>
            </div>
            <div class="e_content">

                <!-- Create New Job Modal -->
                <div class="modal" id="addJobModal">
                    <div class="modal-content">
                        <div class="modal-header" style="padding:35px 50px;">
                            <button type="button" class="close" data-dismiss="modal">
                                <span class="fa fa-close"></span>
                            </button>
                            <span class="fa fa-lock"></span> Create A New Job
                        </div>

                        <form action="includes/addJob.php" method="POST" class="modal-body">
                            <div class="formGroup">
                                <label for="jobTitle">
                                    <span class="glyphicon glyphicon-user"></span> Enter a job title:
                                </label>
                                <input type="text" name="jobTitle" placeholder="Example: Jane Doe">
                            </div>
                            <div class="formGroup">
                                <label for="jobState">
                                    <span class="glyphicon glyphicon-eye-open"></span> Enter a state:
                                </label>
                                <input type="text" name="jobState" placeholder="Example: CT">
                            </div>
                            <div class="formGroup">
                                <label for="jobTown">
                                    <span class="glyphicon glyphicon-eye-open"></span> Enter a town:
                                </label>
                                <input type="text" name="jobTown" placeholder="Example: Farmington">
                            </div>
                            <div class="formGroup">
                                <label for="jobAddress">
                                    <span class="glyphicon glyphicon-eye-open"></span> Enter an address:
                                </label>
                                <input type="text" name="jobAddress" placeholder="Example: 32 Church St">
                            </div>

                            <button type="submit" class="btn formSubmit">
                                <span class="glyphicon glyphicon-off"></span> Create Job!
                            </button>
                        </form>

                        <div class="modal-footer"></div>
                    </div>
                </div>

                <div id="tableWrapper">
                    <!-- Users table -->
                    <?php if(empty($jobRows)): ?>
                        <h1>No jobs were found! Please create a new job.</h1>
                    <?php else: ?>
                        <table id="table">
                            <thead>
                            <tr>
                                <th class="column-title"> Job Title     </th>
                                <th class="column-title"> Job State     </th>
                                <th class="column-title"> Job Town      </th>
                                <th class="column-title"> Job Address   </th>
                                <th class="column-title"> Actions       </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($jobRows as $key => $value): ?>
                                <tr>
                                    <td><?= htmlentities($value['jobTitle'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlentities($value['jobState'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlentities($value['jobTown'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlentities($value['jobAddress'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a class="btn btnOrange toolTip Top"
                                           href="manageJob.php?jobID=<?= htmlentities($value['jobID'], ENT_QUOTES, 'UTF-8') ?>">
                                            <span class="fa fa-pencil" aria-hidden='true' style="color: black"></span>
                                            <span class="toolTipText Top"> Manage Job </span>
                                        </a>
                                        <a class="btn btnRed toolTip Top"
                                           href="../admin/delete.php?id=<?= htmlentities($value['jobID'], ENT_QUOTES, 'UTF-8') ?>">
                                            <span class='fa fa-trash' aria-hidden='true' style="color: black"></span>
                                            <span class="toolTipText"> Delete Job </span>
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>


            </div>
        </div>
    </div>
</main>
<script src="../js/modals.js"></script>
</body>
</html>
