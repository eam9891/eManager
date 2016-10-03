<?php

class Relation {
    
    /**
    * The user who is currently logged in
    * @var User
    */
    private $loggedInUser;

    /**
    * Database connection
    * @var PDO
    */
    private $dbCon;

    /**
    * @param PDO $db
    * @param User $loggedInUser
    * @return boolean|Relation
    */
    public function __construct($db, User $loggedInUser) {
        if ($db == 'undefined') {
          return false; // or you could throw an exception
        }
        // Current loggedin user
        $this->loggedInUser = $loggedInUser;
        // Database Connection
        $this->dbCon = $db;
    }

    /**
    * Return the friend of the current logged in user in the relationship object
    *
    * @param Relationship $rel
    * @return User $friend
    */
    public function getFriend(Relationship $rel) {
        if ($rel->getUserOne()->getUserId() === $this->loggedInUser->getUserId()) {
            $friend = $rel->getUserTwo();
        } else {
            $friend = $rel->getUserOne();
        }
        return $friend;
    }

    public function getNumContacts() {
        $id = (int)$this->loggedInUser->getUserId();

        $query = <<<TAG

            SELECT * FROM contacts
            WHERE (
                (userOne = :u1 OR userTwo = :u2)
                AND status = :s
            )
        
TAG;
        $query_params = array(
            ':u1' => $id,
            ':u2' => $id,
            ':s' => 1
        );
        $stmt = $this->dbCon->prepare($query);
        $stmt->execute($query_params);
        $count = $stmt->rowCount();
        return $count;
    }
  
    /**
    * Get all the friends list for the currently loggedin user
    *
    * @return array Relationship Objects
    */
    public function getFriendsList() {
        $id = (int)$this->loggedInUser->getUserId();

        $query = <<<TAG

            SELECT * FROM contacts
            WHERE (
                (userOne = :u1 OR userTwo = :u2)
                AND status = :s
            )
        
TAG;
        $query_params = array(
            ':u1' => $id,
            ':u2' => $id,
            ':s' => 1
        );
        $stmt = $this->dbCon->prepare($query);
        $stmt->execute($query_params);
        $row = $stmt->fetchAll();

        $rels = array();

        foreach ($row as $key => $value) {
            $rel = new Relationship();
            $rel->arrToRelationship($value, $this->dbCon);
            $rels[] = $rel;
        }

        return $rels;
    }
  
    /**
    * Get the list of friend requests sent by the logged in user
    *
    * @return array Relationship Objects
    */
    public function getSentFriendRequests() {
        $id = (int) $this->loggedInUser->getUserId();

        $query = "
            SELECT * FROM contacts
            WHERE (
                (userOne = :u1 OR userTwo = :u2)
                AND
                status = :s
        )
        ";
        $query_params = array(
            ':u1' => $id,
            ':u2' => $id,
            ':s' => 0
        );
        $stmt = $this->dbCon->prepare($query);
        $stmt->execute($query_params);

        $rels = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rel = new Relationship();
            $rel->arrToRelationship($row, $this->dbCon);
            $rels[] = $rel;
        }

        return $rels;
    }
  
    /**
    * Get the list of friend requests for the logged in user
    *
    * @return array Relationship Objects
    */
    public function getFriendRequests() {
    $id = (int) $this->loggedInUser->getUserId();

    $sql = 'SELECT * FROM `contacts` ' .
            'WHERE (`userOne` = ' . $id . ' OR `userTwo` = '. $id .')' .
            ' AND `status` = 0 ' .
            'AND `actionUserID` != ' . $id;

    $resultObj = $this->dbCon->query($sql);

    $rels = array();

    while($row = $resultObj->fetch(PDO::FETCH_ASSOC)) {
      $rel = new Relationship();
      $rel->arrToRelationship($row, $this->dbCon);
      $rels[] = $rel;
    }

    return $rels;
    }

    /**
    * Get the list of friends blocked by the current user.
    *
    * @return \Relationship array
    */
    public function getBlockedFriends() {
    $id = (int) $this->loggedInUser->getUserId();

    $sql = 'SELECT * FROM `contacts` ' .
            'WHERE (`userOne` = ' . $id . ' OR `userTwo` = '. $id .')' .
            ' AND `status` = 3 ' .
            'AND `actionUserID` = ' . $id;

    $resultObj = $this->dbCon->query($sql);

    $rels = array();

    while($row = $resultObj->fetch(PDO::FETCH_ASSOC)) {
      $rel = new Relationship();
      $rel->arrToRelationship($row, $this->dbCon);
      $rels[] = $rel;
    }

    return $rels;
    }

    /**
    * Get the relatiohship for the friend and user
    *
    * @param User $user
    * @return boolean|int - either false or the relationship status
    */
    public function getRelationship(User $user) {
        $user_one = (int) $this->loggedInUser->getUserId();
        $user_two = (int) $user->getUserId();

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }


        $query = "
            SELECT * FROM contacts
            WHERE 
                userOne = :u1
            AND
                userTwo = :u2
            
        ";
        $query_params = array(
            ':u1' => $user_one,
            ':u2' => $user_two
        );
        $stmt = $this->dbCon->prepare($query);
        $stmt->execute($query_params);



        if ($stmt->rowCount() > 0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $relationship = new Relationship();
          $relationship->arrToRelationship($row, $this->dbCon);
          return $relationship;
        }

        return false;
    }

    /**
    * Insert a new friends request
    *
    * @param User $user - User to which the friend request must be added with.
    * @return Boolean
    */
    public function addFriendRequest(User $user) {
    $user_one = (int) $this->loggedInUser->getUserId();
    $action_user_id = $user_one;
    $user_two = (int) $user->getUserId();

    if ($user_one > $user_two) {
        $temp = $user_one;
        $user_one = $user_two;
        $user_two = $temp;
    }

    $sql = 'INSERT INTO `contacts` '
            . '(`userOne`, `userTwo`, `status`, `actionUserID`) '
            . 'VALUES '
            . '(' . $user_one . ', '. $user_two .', 0, '. $action_user_id .')';

    $this->dbCon->query($sql);

    if ($this->dbCon->affected_rows > 0) {
      return true;
    }

    return false;
    }

    /**
    * Accept a friend request
    *
    * @param User $user - User to whome the friend request must be accepted with.
    * @return Boolean
    */
    public function acceptFriendRequest(User $user) {
    $user_one = (int) $this->loggedInUser->getUserId();
    $action_user_id = $user_one;
    $user_two = $user->getUserId();

    if ($user_one > $user_two) {
      $temp = $user_one;
      $user_one = $user_two;
      $user_two = $temp;
    }

    $sql = 'UPDATE `contacts` '
            . 'SET `status` = 1, `actionUserID` = '. $action_user_id
            .' WHERE `userOne` = '. $user_one
            .' AND `userTwo` = ' . $user_two;

    $this->dbCon->query($sql);

    if ($this->dbCon->affected_rows > 0) {
      return true;
    }

    return false;
    }

    /**
    * Decline a friend request for the user
    *
    * @params User $user - The user whose request to be declined
    * @return Boolean
    */
    public function declineFriendRequest(User $user) {
    $user_one = (int) $this->loggedInUser->getUserId();
    $action_user_id = $user_one;
    $user_two = $user->getUserId();

    if ($user_one > $user_two) {
      $temp = $user_one;
      $user_one = $user_two;
      $user_two = $temp;
    }

    $sql = 'UPDATE `contacts` '
            . 'SET `status` = 2, `actionUserID` = '. $action_user_id
            .' WHERE `userOne` = '. $user_one
            .' AND `userTwo` = ' . $user_two;

    $this->dbCon->query($sql);

    if ($this->dbCon->affected_rows > 0) {
      return true;
    }

    return false;
    }

    /**
    * Cancel a friend request
    *
    * @param User $user - The friend details
    * @return Boolean
    */
    public function cancelFriendRequest(User $user) {
    $user_one = (int) $this->loggedInUser->getUserId();
    $user_two = (int) $user->getUserId();

    if ($user_one > $user_two) {
      $temp = $user_one;
      $user_one = $user_two;
      $user_two = $temp;
    }

    $sql = 'DELETE FROM `contacts` ' .
            'WHERE `userOne` = ' . $user_one .
            ' AND `userTwo` = ' . $user_two .
            ' AND `status` = 0';

    $this->dbCon->query($sql);

    if ($this->dbCon->affected_rows > 0) {
      return true;
    }

    return false;
    }

    /**
    * Remove a friend from the friends list
    *
    * @param User $user - The friend details
    * @return Boolean
    */
    public function unfriend(User $user) {
    $user_one = (int) $this->loggedInUser->getUserId();
    $user_two = (int) $user->getUserId();

    if ($user_one > $user_two) {
      $temp = $user_one;
      $user_one = $user_two;
      $user_two = $temp;
    }

    $sql = 'DELETE FROM `contacts` ' .
            'WHERE `userOne` = ' . $user_one .
            ' AND `userTwo` = ' . $user_two .
            ' AND `status` = 1';

    $this->dbCon->query($sql);

    if ($this->dbCon->affected_rows > 0) {
      return true;
    }

    return false;
    }

    /**
    * Block a particular user
    *
    * @param User $user - The user to be blocked
    * @return Boolean
    */
    public function block(User $user) {
    $user_one = (int) $this->loggedInUser->getUserId();
    $action_user_id = $user_one;
    $user_two = $user->getUserId();

    if ($user_one > $user_two) {
      $temp = $user_one;
      $user_one = $user_two;
      $user_two = $temp;
    }

    $sql = 'UPDATE `contacts` '
            . 'SET `status` = 3, `actionUserID` = '. $action_user_id
            .' WHERE `userOne` = '. $user_one
            .' AND `userTwo` = ' . $user_two;

    $this->dbCon->query($sql);

    if ($this->dbCon->affected_rows > 0) {
      return true;
    }

    return false;
    }

    /**
    * Unblock a friend who is blocked already.
    *
    * @param User $user
    * @return boolean
    */
    public function unblockFriend(User $user) {
    $user_one = (int) $this->loggedInUser->getUserId();
    $user_two = (int) $user->getUserId();

    if ($user_one > $user_two) {
      $temp = $user_one;
      $user_one = $user_two;
      $user_two = $temp;
    }

    $sql = 'DELETE FROM `contacts` ' .
            'WHERE `userOne` = ' . $user_one .
            ' AND `userTwo` = ' . $user_two .
            ' AND `status` = 3';

    $this->dbCon->query($sql);

    if ($this->dbCon->affected_rows > 0) {
      return true;
    }

    return false;
    }
}