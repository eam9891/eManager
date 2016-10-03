<?php

    // Include our config file - This holds the paths to all of our classes and our connection
    include_once $_SERVER['DOCUMENT_ROOT'].'/app/config.php';

    // Initialize a new user object
    $user = new User();

    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user'])) {

        // If they are not, we redirect them to the login page.
        header("Location: ../../index.php");
        die("Redirecting to ../../index.php");
    }

    // If there is not uid redirect to home page.
    if (empty($_GET) || !isset($_GET['uid'])) {
        header('Location: ../home.php');
        die('Redirecting to ../home.php');
    }

    // Logged in user details.
    $user = $user->getUser($db, (int) $_SESSION['user']['userID']);

    // Relation of the logged in user
    $relation = new Relation($db, $user);

    // Profile user details
    $friend_id = (int) $_GET['uid'];


    // Check if the profile is same as the logged in user
    // If it is we are requesting the current users profile
    if ($friend_id === $user->getUserId()) {

        // The user object will be the same as before
        $profile = $user;

        // Relations will also be the same
        $profile_relation = $relation;

        // Get the friends list
        $profile_friends = $relation->getFriendsList();

    // If it is not, we are requesting another users profile
    } else {

        // Set the user object
        $profile = (new User())->getUser($db, $friend_id);

        // Set the relation object
        $profile_relation = new Relation($db, $profile);

        // Get the Friends list
        $profile_friends = $profile_relation->getFriendsList();

        // Get the relationship between the current user and the profile user.
        $relationship = $relation->getRelationship($profile);
    }

    // Checks if the profile is blocked
    include_once('includes/blocked_profile.php');

?>
<div class="container">
    <?php if ($is_blocked === false) { ?>
        <div>
            <h3>Profile</h3>
            <div class="profile-body">
                <?php
                    echo '<p><a href="home.php" style="text-decoration:none;">Home</a></p>';
                    echo '<p>Username: <b>' . $profile->getUsername() . '</b></p>';
                    echo '<p>Email: <b>' . $profile->getEmail() . '</b></p>';

                    // Check if the current user is not the profile user.
                    if ($profile->getUserId() !== $user->getUserId()) {
                        // Check if user is there in any relationship record
                        if ($relationship != false) {
                            switch ($relationship->getStatus()) {
                            case 0:
                                if ($relationship->getActionUserId() == $user->getUserId()) {
                                    echo '<a href="http://192.168.0.21/home/includes/user_action.php?action=cancel&friend_id=' .
                                          $profile->getUserId() . '">Cancel Request</a>';
                                } else {
                                    echo '<a href="http://192.168.0.21/home/includes/user_action.php?action=accept&friend_id=' .
                                          $profile->getUserId() . '">Accept Request</a>';
                                }
                                break;

                            case 1:
                                echo '<a href="http://192.168.0.21/home/includes/user_action.php?action=unfriend&friend_id=' .
                                  $profile->getUserId() . '">Unfriend</a>';
                                break;

                            case 2:
                                echo '<small>Your request has been declined!</small>';
                                break;
                            }
                        } else if ($relationship === false) {
                            echo '<a href="user_action.php?action=add&friend_id=' .
                                $profile->getUserId() . '">Add Friend</a>';
                        }
                    }
                    echo '<hr/>';

                    // Display profile friends
                    if (!empty($profile_friends)) {
                        echo '<ul>';
                            foreach ($profile_friends as $rel) {
                                $friend = $profile_relation->getFriend($rel);
                                echo '<li><a href="http://192.168.0.21/home/profile.php?uid=' . $friend->getUserId() . '">' .
                                    ucfirst($friend->getUsername()) . '</a></li>';
                            }
                        echo '</ul>';
                    } else {
                        echo '<h6>No Friends</h6>';
                    }
                ?>
            </div>
        </div>
    <?php } else { ?>
        <p>You can't view this profile. It is either blocked or inactive</p>
    <?php } ?>
</div>