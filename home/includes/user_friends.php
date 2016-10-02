<?php
// List of user friends
$user_friends = $relation->getFriendsList();

if (!empty($user_friends)) {
  echo '<ul>';
  foreach ($user_friends as $rel) {
    $friend = $relation->getFriend($rel);
    echo '<li><a href="http://192.168.0.21/home/profile.php?uid=' . $friend->getUserId() . '">' . ucfirst($friend->getUsername()) . '</a></li>';
  }
  echo '</ul>';
} else {
  echo '<h6>You don\'t have any friends yet!</h6>';
}