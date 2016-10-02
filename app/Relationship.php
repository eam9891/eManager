<?php
/**
 * This class is store the details of a relationship between two user objects
 * 
 * Copyright (c)  2013-2015 Codedodle.com
 * 
 * @author Tamil Selvan K <info@codedodle.com>
 */
class Relationship {
  
    /**
    * User one in the relationship
    *
    * @var User
    */
    public $userOne;

    /**
    * User two in the relationship
    *
    * @var User
    */
    public $userTwo;

    /**
    * Determines the status of the relationship
    *
    * 0 - Pending
    * 1 - Accepted
    * 2 - Declined
    * 3 - Blocked
    *
    * By default the status is set to 0
    */
    public $status = 0;

    /**
    * This is the user who made the most recent status field update
    */
    public $actionUserId;

    //##################### Accessor and Mutator Methods #########################

    public function getUserOne() {
        return $this->userOne;
    }

    public function setUserOne(User $userOne) {
        $this->userOne = $userOne;
    }

    public function getUserTwo() {
        return $this->userTwo;
    }

    public function setUserTwo(User $userTwo) {
        $this->userTwo = $userTwo;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getActionUserId() {
        return $this->actionUserId;
    }

    public function setActionUserId($actionUserId) {
        $this->actionUserId = $actionUserId;
    }

    //##################### End of Accessor and Mutator Methods ##################

    /**
    * Set's the details of the relationship from the query result into the
    * current relationship object instance.
    *
    * @param array $row
    * @param PDO $db
    */
    public function arrToRelationship($row, $db) {
        if (!empty($row)) {
            if (isset($row['userOne']) && isset($row['userTwo'])) {

                // Fetch the user details and create the user object set.
                $query = <<<TAG

                    SELECT * FROM users
                    WHERE 
                      userID = :u1
                    OR 
                      userID = :u2
                
TAG;
                $query_params = array (
                    ':u1' => (int) $row['userOne'],
                    ':u2' => (int) $row['userTwo']
                );
                $stmt = $db->prepare($query);
                $stmt->execute($query_params);


                $usersArr = array();
                while($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $usersArr[] = $record;
                }

                $userOne = new User();
                $userTwo = new User();

                // Check which user id is lesser.
                if ($row['userOne'] < $row['userTwo']) {
                    $userOne->arrToUser($usersArr[0]);
                    $userTwo->arrToUser($usersArr[1]);
                } else {
                    $userOne->arrToUser($usersArr[1]);
                    $userTwo->arrToUser($usersArr[0]);
                }

                $this->setUserOne($userOne);
                $this->setUserTwo($userTwo);
            }

            isset($row['status']) ? $this->setStatus((int)$row['status']) : '';
            isset($row['actionUserID']) ?
            $this->setActionUserId((int)$row['actionUserID']) : '';
        }
    }
}