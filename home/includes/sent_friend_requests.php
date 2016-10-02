<?php
// Holds the list of Friend requests sent
$sent_friend_requests = $relation->getSentFriendRequests();

if (!empty($sent_friend_requests)) {  
  echo '<table>';
  
  foreach ($sent_friend_requests as $rel) {
    echo '<tr>';
    $friend = $relation->getFriend($rel);
    echo '<td><a href="profile.php?uid=' . $friend->getUserId() . '">' . ucfirst($friend->getUsername()) . '</a></li>';
    echo '<td><a href="user_action.php?action=cancel&friend_id='. $friend->getUserId() .'" title="Cancel Request">Cancel</a></td>';
    echo '</tr>';
  }
  
  echo '</table>';
} else {
  echo '<h6>No friend requests sent!</h6>';
}