<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/8/2017
 * Time: 10:45 AM
 */

namespace Model;

use Utility\DatabaseConnection as DatabaseConnection;

require_once (dirname(__FILE__) .  '/../Utility/DatabaseConnection.php');

class Applications
{
    private $applicationID;
    private $userID;
    private $courseID;
    private $dateCreated;
    private $dbh;

    function __construct()
    {
        $this->dbh = DatabaseConnection::getInstance();
    }




    /**
     * @return mixed
     */
    public function getApplicationID()
    {
        return $this->applicationID;
    }

    /**
     * @param mixed $applicationID
     */
    public function setApplicationID($applicationID)
    {
        $this->applicationID = $applicationID;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getCourseID()
    {
        return $this->courseID;
    }

    /**
     * @param mixed $courseID
     */
    public function setCourseID($courseID)
    {
        $this->courseID = $courseID;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }


}