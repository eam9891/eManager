<?php
/**
 * Created by PhpStorm.
 * User: Ethan
 * Date: 9/29/2016
 * Time: 12:44 PM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ESerV</title>

    <link href="css/template.css" rel="stylesheet">
    <link href="css/modals.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <link href="css/forms.css" rel="stylesheet">

    <link href="vendors/font-awesome/css/font-awesome.css" rel="stylesheet">

    <style>
        .registerLink {
            text-decoration: none;
            color: #9d9d9d;
            font-family: "Helvetica Neue", sans-serif;
            font-size: 13px;
            font-weight: 400;
            padding-right: 15px;
            line-height: 59px;
            display: block;
            height: 55px;
        }
        .registerLink:hover, .registerLink:focus {
            color: white;
        }

    </style>
</head>
<body>
    <header>

        <!-- Site Title -->
        <div class="siteTitle">
            <a href="index.php">
                <i class="fa fa-diamond"></i>
                <span>ESerV</span>
            </a>
        </div>

        <!-- Login Utility -->
        <form action="check_login.php" method="post" class="loginForm inlineForm">
            <input type="text"
                   class="formGroup"
                   name="username"
                   value="<?php echo $submitted_username; ?>"
                   placeholder="Username"
            >
            <input type="password"
                   class="formGroup"
                   name="password"
                   value=""
                   placeholder="Password"
            >
            <button type="submit" class="loginBtn"> Login </button>
        </form>

        <!-- Register -->
        <a href="register.php" class="registerLink pull-right">Don't Have An Account, Sign Up Now!</a>

    </header>
    <main>

    </main>
    <footer>

    </footer>

</body>
