<?php

    include_once('../app/config.php');

    // If they try to visit this page directly
    if (empty($_GET)) {
      header('Location: home.php');
    }

    // Check for user login
    if(empty($_SESSION['user']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: ../../index.php");
        die("Redirecting to ../../index.php");
    }


    $allowed_actions = array (
        'accept',   // status to 1
        'decline',  // status to 2
        'cancel',   // delete relationship
        'block',    // status 3
        'unblock',  // delete relationship
        'add',      // insert friend request
        'unfriend', // delete a friend
    );

    $allowed_friends = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

    if (
        isset($_GET['action']) && in_array($_GET['action'], $allowed_actions) &&
        isset($_GET['friend_id']) && in_array($_GET['friend_id'], $allowed_friends)
    ) {
        $action = $_GET['action'];
        $friend_id = (int) $_GET['friend_id'];
        $friend = new User();
        $friend = $friend->getUser($db, $friend_id);

        // Process based on the action
        switch ($action) {
        case 'accept':
            $result = $relation->acceptFriendRequest($friend);
            break;
        case 'decline':
            $result = $relation->declineFriendRequest($friend);
            break;
        case 'cancel':
            $result = $relation->cancelFriendRequest($friend);
            break;
        case 'block':
            $result = $relation->block($friend);
            break;
        case 'unblock':
            $result = $relation->unblockFriend($friend);
            break;
        case 'add':
            $result = $relation->addFriendRequest($friend);
            break;
        case 'unfriend':
            $result = $relation->unfriend($friend);
            break;

        }

        // Set the message cookie so that it shows this message in the home page.
        if ($result) {
            setcookie('status', 'success');
        } else {
            setcookie('status', 'failed');
        }
        // Redirect to home
        header('Location: home.php');
    } else {
        header('Location: home.php');
    }