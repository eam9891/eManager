<?php
// Holds the list of friend requests for user
$user_friend_requests = $relation->getFriendRequests();

if (!empty($user_friend_requests)) {
  echo '<table>';
  
  foreach ($user_friend_requests as $rel) {
    echo '<tr>';
    $friend = $relation->getFriend($rel);
    echo '<td><a href="profile.php?uid=' . $friend->getUserId() . '">' . ucfirst($friend->getUsername()) . '</a></li>';
    echo '<td><a href="user_action.php?action=accept&friend_id='. $friend->getUserId() .'" title="Accept friend Request">Accept</a></td>';
    echo '<td><a href="user_action.php?action=decline&friend_id='. $friend->getUserId() .'">Decline</a></td>';
    echo '</tr>';
  }
  
  echo '</table>';
} else {
  echo '<h6>No friend requests!</h6>';
}