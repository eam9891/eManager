<?php
	// First we execute our common code to connect to the database and start the session
	require("../app/config.php");

	// At the top of the page we check to see whether the user is logged in or not
	if(empty($_SESSION['user']['role']))
	{
		// If they are not, we redirect them to the login page.
		header("Location: ../../index.php");

		// Remember that this die statement is absolutely critical.  Without it,
		// people can view your members-only content without logging in.
		die("Redirecting to ../../index.php");
	}

	include("sysInfo.class.php");

    $userID = $_SESSION['user']['userID'];
    $user = new User();
    $user = $user->getUser($db, $userID);
    $relation = new Relation($db, $user);


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
		<button class="profileDropbtn user-profile">
			<img src="../images/user.png" alt="">
			<?= $user->getUsername() ?>
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
            <!-- System Status Panel -->
            <div class="e_panel">
                <div class="e_title">
                    <h2>System Status</h2>
                    <!--<ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="profileDropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>-->
                </div>
                <!-- top tiles -->
                <div class="tile_count">

                    <!-- Server Uptime -->
                    <div class="tile_stats_count">
					<span class="uptime_percent green pull-right">
						<i class="fa fa-sort-asc"></i> 99.99%
					</span>
                        <span class="count_top">
						<i class="fa fa-clock-o"></i> Server Uptime
					</span>
                        <div class="count" >
                            <div id="refresh" class="green">
                                <?php
                                $ut = $ServerInfo->Uptime();
                                echo "$ut[days]:$ut[hours]:$ut[mins]:$ut[secs] ";
                                ?>
                            </div>
                        </div>
                        <span class="count_bottom">
						Days : Hours : Minutes : Seconds
					</span>
                    </div>

                    <!-- Total Users -->
                    <div class="tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
                        <div class="count green">
                            <?php
                            include("numUsers.php");
                            echo $numberOfUsers;
                            ?>
                        </div>
                        <span class="count_bottom"><i class="green">4% </i> From last Week</span>
                    </div>

                    <!-- System Load -->
                    <div class="tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> System Load</span>
                        <div class="count green">
                            <div id="refreshLoad">
                                <?= $ServerInfo->Cpu(); ?>
                            </div>
                        </div>
                        <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
                    </div>

                    <!-- Network Activity -->
                    <div class="tile_stats_count">
                        <form class="pull-right">
                            <label class="select">
                                <select name="Network" onchange="networkUtility(this.value)">

                                    <option value="1" class="styledOption">Upload</option>
                                    <option value="2">Download</option>
                                    <option value="3" selected>Total</option>
                                </select>
                            </label>
                        </form>
                        <span class="count_top">
                            <i class="fa fa-user"></i>
                            Network
					    </span>
                        <div class="count green">
                            <div id="txtHint"><b></b></div>
                        </div>
                        <span class="count_bottom">
						    <i class="green"><i class="fa fa-sort-asc"></i> 34% </i>
						    From last Week
					    </span>
                    </div>

                </div>
                <!-- /top tiles -->

            </div>

            <!-- Another Admin Function..... -->
            <div class="e_panel">
                <div class="e_title">
                    <h2>Database Manager</h2>

                </div>
                <div class="e_content">

                </div>
            </div>
        </div>


	</main>
	<script src="../js/dropdowns.js"></script>
	<script src="../js/modals.js"></script>
	<script>

		// Network Utility
		function networkUtility(str) {
			var xmlhttp;

			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function () {

				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("txtHint").innerHTML = this.responseText;
				}
			};

			xmlhttp.open("GET", "sysInfo.class.php?q=" + str, true);
			xmlhttp.send();

		}
		// Load the default network selection (Total download and upload bytes)
		window.onload = networkUtility(3);

	</script>
</body>
</html>