<?php
// Holds the list of bloced users
$blocked_friends = $relation->getBlockedFriends();

if (!empty($blocked_friends)) {
  echo '<table>';
  
  foreach ($blocked_friends as $rel) {
    echo '<tr>';
    $friend = $relation->getFriend($rel);
    echo '<td><a href="profile.php?uid=' . $friend->getUserId() . '">' . ucfirst($friend->getUsername()) . '</a></li>';
    echo '<td><a href="user_action.php?action=unblock&friend_id='. $friend->getUserId() .'" title="Unblock">Unblock</a></td>';
    echo '</tr>';
  }
  
  echo '</table>';
} else {
  echo '<h6>No blocked friends!</h6>';
}