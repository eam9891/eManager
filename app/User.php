<?php
/**
 * Stores the details of a particular user
 * 
 * Copyright (c)  2013-2015 Codedodle.com
 * 
 * @author Tamil Selvan K <info@codedodle.com>
 */
class User {
  
  /**
   * The Unique id of the user
   *
   * @var Int
   */
  private $userId;
  
  /**
   * Name of the user
   *
   * @var String
   */
  private $userName;
  
  /**
   * User email id
   *
   * @var String
   */
  private $email;
  
  /**
   * User password
   *
   * @var String
   */
  private $password;
  
  //##################### Accessor and Mutator Methods #########################
  
  public function getUserId() {
    return $this->userId;
  }
  
  public function setUserId($userId) {
    $this->userId = $userId;
  }
  
  public function getUsername() {
    return $this->userName;
  }
  
  public function setUsername($userName) {
    $this->userName = $userName;
  }
  
  public function getEmail() {
    return $this->email;
  }
  
  public function setEmail($email) {
    $this->email = $email;
  }
  
  public function getPassword() {
    return $this->password;
  }
  
  public function setPassword($password) {
    $this->password = $password;
  }
  
  //##################### End of Accessor and Mutator Methods ##################
  
  /**
   * Returns the User Object provided the id of the user.
   * 
   * @param PDO $db
   * @param int $id
   * @return \User
   */
  public function getUser($db, $id) {

      // This query retrieves the user's information from the database using the supplied userID
      $query = "
            SELECT
                *
            FROM users
            WHERE
                userID = :x
        ";

      // The parameter values
      $query_params = array(
          ':x' => $id
      );

      // Prepare and execute the query against the database
      $stmt = $db->prepare($query);
      $stmt->execute($query_params);

      // Return row into array
      $user_details = $stmt->fetch();


      $user = new User();
      $user->arrToUser($user_details);
      return $user;
  }
  
  /**
   * Set's the user details returned from the query into the current object.
   * 
   * @param array $userRow
   */
  public function arrToUser($userRow) {
    if (!empty($userRow)) {
      isset($userRow['userID']) ?
        $this->setUserId($userRow['userID']) : '';
      isset($userRow['username']) ? 
        $this->setUsername($userRow['username']) : '';
      isset($userRow['email']) ? 
        $this->setEmail($userRow['email']) : '';
      isset($userRow['password']) ? 
        $this->setPassword($userRow['password']) : '';
    }
  }
}