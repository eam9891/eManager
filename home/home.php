<?php
    // First we execute our common code to connect to the database and start the session
    include("../app/config.php");

    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: ../../index.php");

        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to ../../index.php");
    }

    $userID = $_SESSION['user']['userID'];
    $user = new User();


    $user = $user->getUser($db, $userID);

    $relation = new Relation($db, $user);

    // Message cookie, used to display info from operations on other pages
    $msg = '';
    $status = array(
        'success' => 'Operation performed Successfully.',
        'failed' => 'Action Failed to process!'
    );

    if (isset($_COOKIE['status']) && array_key_exists($_COOKIE['status'], $status)) {
        $msg = $status[$_COOKIE['status']];
        // clear the cookie
        setcookie('status', '');
        unset($_COOKIE['status']);
    }
?>
<!-- Basic html tags -->
<?php
    if ($msg !== '') echo '<p>' . $msg . '</p>';
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
    <link href="../css/tabs.css" rel="stylesheet">

    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

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
            <img src="../images/user.png" alt="">
            <?= $user->getUsername() ?>
        </button>
        <div id="profileDropdownID" class="profileDropdownContent">
            <a href="profile.php?uid=<?= $userID ?>">Profile<span class="badge bg-red pull-right">50%</span></a>
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
    <a href="myContacts.php">
        <i class="fa fa-table"></i>
        <div class="icon-bar-item">Contacts</div>
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
        <div class="tabTitle">
            <span class="pull-right"> Dashboard </span>
            <i class="fa fa-tachometer pull-right"></i>
        </div>


            <nav class='tabs'>
                <a href="#tab1" class="tabHdr selected">
                    <span class="badge bg-red"> 6 </span>
                    My Profile

                </a>
                <a href="#tab2" class='tabHdr'>
                    <span class="badge bg-green">
                        <?= $relation->getNumContacts(); ?>
                    </span>
                    Contacts
                </a>

                <a href="#tab3" class='tabHdr'>
                    Jobs
                </a>
                <a href="#tab4" class='tabHdr'>
                    Inbox
                </a>
            </nav>


        <div class='e_content tabContent' id='tab1'>
            <div class="user-details">
                <p>Username: <b><?php echo $user->getUsername(); ?></b></p>
                <p>Email: <b><?php echo $user->getEmail(); ?></b></p>
                <p><a href="logout.php" title="logout">Logout</a></p>
                <hr/>
            </div>
        </div>
        <div class='e_content tabContent' id='tab2'>
            <div id="friends">
                <h4>Friends</h4>
                <?php
                include_once('includes/user_friends.php');
                ?>
            </div>
            <div id="friendRequests">
                <h3>Friend Requests</h3>
                <div>
                    <?php
                    include_once('includes/user_friend_requests.php');
                    ?>
                </div>
            </div>
            <div id="blockedFriends">
                <h3>Blocked Friends</h3>
                <div>
                    <?php
                    include_once('includes/blocked_friends.php');
                    ?>
                </div>
            </div>
        </div>
        <div class='e_content tabContent' id='tab3'>
            Contents - Tab 3
        </div>
        <div class='e_content tabContent' id='tab4'>
            Contents - Tab 4
        </div>
    </div>


</div>
</main>
<script src="../js/tabs.js"></script>
</body>
</html>











