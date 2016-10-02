<?php
    // Whether the current profile is blocked in anyway
    $is_blocked = false;

    // Get the list of blocked friends
    $logged_in_user_blocked_list = $relation->getBlockedFriends();

    // check if current profile is in the blocked list of the user.
    foreach ($logged_in_user_blocked_list as $blocked_rel) {
        $rel = $relation->getFriend($blocked_rel);
        // If the user is present in the blocked user list
        if ($rel->getUserId() == $friend_id) {
            $is_blocked = true;
        }
    }

    // Get the list of blocked friends list of the profile
    $profile_user_blocked_list = $profile_relation->getBlockedFriends();

    // check if current profile is in the blocked list of the profile user.
    foreach ($profile_user_blocked_list as $blocked_rel) {
        $rel = $profile_relation->getFriend($blocked_rel);
        // If the user is present in the blocked user list
        if ($rel->getUserId() == $user->getUserId()) {
            $is_blocked = true;
        }
    }