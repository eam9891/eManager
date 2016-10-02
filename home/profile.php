<?php

    include_once('../app/config.php');

    $user = new User();

    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user'])) {

        // If they are not, we redirect them to the login page.
        header("Location: ../../index.php");
        die("Redirecting to ../../index.php");
    }

    $userID = $_SESSION['user']['userID'];

    // Logged in user details.
    $user = $user->getUser($db, (int) $userID);

    // Relation of the logged in user
    $relation = new Relation($db, $user);

    // Profile user details
    $friend_id = (int) $_GET['uid'];


    // Check if the profile is same as the logged in user
    if ($friend_id = $userID) {

        $profile = $user;
        $profile_relation = $relation;
        $profile_friends = $relation->getFriendsList();

    } else {
        // Profile use details
        $profile = (new User())->getUser($db, $friend_id);

        // Relation object for the current profile being showed
        $profile_relation = new Relation($db, $profile);

        // Got the Friends list
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
                    if ($profile->getUserId() != $userID) {
                        // Check if user is there in any relationship record
                        if ($relationship != false) {
                            switch ($relationship->getStatus()) {
                            case 0:
                                if ($relationship->getActionUserId() == $user->getUserId()) {
                                    echo '<a href="user_action.php?action=cancel&friend_id=' .
                                          $profile->getUserId() . '">Cancel Request</a>';
                                } else {
                                    echo '<a href="user_action.php?action=accept&friend_id=' .
                                          $profile->getUserId() . '">Accept Request</a>';
                                }
                                break;

                            case 1:
                                echo '<a href="user_action.php?action=unfriend&friend_id=' .
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