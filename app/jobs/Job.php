<?php

/** This class stores the details of a particular job
 *  Created by PhpStorm.
 *  User: Ethan
 *  Date: 10/2/2016
 *  Time: 12:28 AM
 */
class Job
{
    private $jobID;
    private $jobTitle;
    private $jobState;
    private $jobTown;
    private $jobAddress;
    private $jobAdmin;

    // Getters
    public function getJobID() {
        return $this->jobID;
    }
    public function getJobTitle() {
        return $this->jobTitle;
    }
    public function getJobState() {
        return $this->jobState;
    }
    public function getJobTown() {
        return $this->jobTown;
    }
    public function getJobAddress(){
        return $this->jobAddress;
    }
    public function getJobAdmin() {
        return $this->jobAdmin;
    }

    // Setters
    public function setJobID($jobID) {
        $this->jobID = $jobID;
    }
    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }
    public function setJobState($jobState) {
        $this->jobState = $jobState;
    }
    public function setJobTown($jobTown) {
        $this->jobTown = $jobTown;
    }
    public function setJobAddress($jobAddress) {
        $this->jobAddress = $jobAddress;
    }
    public function setJobAdmin($jobAdmin) {
        $this->jobAdmin = $jobAdmin;
    }


    /** Returns the Job Object provided the id of the job.
     *  @param $db PDO
     *  @param $jobID
     *  @return Job
     */
    public function getJob($db, $jobID) {

        // This query retrieves the user's information from the database using the supplied userID
        $query = "
            SELECT
                *
            FROM jobs
            WHERE
                jobID = :x
        ";

        // The parameter values
        $query_params = array(
            ':x' => $jobID
        );

        // Prepare and execute the query against the database
        $stmt = $db->prepare($query);
        $stmt->execute($query_params);

        // Return row into array
        $jobDetails = $stmt->fetch();


        $job = new Job();
        $job->arrToJob($jobDetails);
        return $job;
    }

    /** Set's the job details returned from the query into the current object.
     *  @param $jobDetails
     */
    public function arrToJob($jobDetails) {
        if (!empty($jobDetails)) {

            isset($jobDetails['jobID']) ?
                $this->setJobID($jobDetails['jobID']) : '';

            isset($jobDetails['jobTitle']) ?
                $this->setJobTitle($jobDetails['jobTitle']) : '';

            isset($jobDetails['jobState']) ?
                $this->setJobState($jobDetails['jobState']) : '';

            isset($jobDetails['jobTown']) ?
                $this->setJobTown($jobDetails['jobTown']) : '';

            isset($jobDetails['jobAddress']) ?
                $this->setJobAddress($jobDetails['jobAddress']) : '';

            isset($jobDetails['jobAdmin']) ?
                $this->setJobAdmin($jobDetails['jobAdmin']) : '';

        }
    }
}

