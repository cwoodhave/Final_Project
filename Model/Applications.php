<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/8/2017
 * Time: 10:45 AM
 */

namespace Model;

use Utility\DatabaseConnection as DatabaseConnection;
use Model\Responses as Responses;

require_once (dirname(__FILE__) .  '/../Utility/DatabaseConnection.php');
require_once (dirname(__FILE__) . '/Responses.php');

class Applications
{
    private $applicationID;
    private $userID;
    private $courseID;
    private $dateCreated;
    private $responses;
    private $dbh;

    function __construct($applicationID = null)
    {
        $this->dbh = DatabaseConnection::getInstance();
        $this->applicationID = null;

        if($applicationID !== null && is_int($applicationID))
        {
            $this->getApplicationByID($applicationID);
        }

    }

    private function getApplicationByID($applicationID)
    {
        try
        {
            $stmthndl = $this->dbh->prepare("SELECT * FROM applications WHERE applicationID = :applicationID");
            $stmthndl->bindParam("applicationID", $applicationID);

            $stmthndl->execute();
            $stmthndl->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $stmthndl->fetch();

            if($stmthndl->rowCount() == 1)
            {
                foreach ($row as $property => $value)
                {
                    if (method_exists($this, ($method = 'set' . ucfirst($property))))
                    {
                        $this->$method($value);
                    }
                }
            }

            $responses = Responses::getResponseByApplicationID($applicationID);

            foreach ($responses as $response)
            {
                $responseID = $response['responseID'];
                $this->responses[] = new Responses($responseID);
            }

        }
        catch (\PDOException $e)
        {

        }
    }


    function saveApplication()
    {
        if($this->applicationID === null) {
            try {
                $stmt = $this->dbh->prepare("INSERT INTO applications (userID, courseID)
                                              VALUES (:userID, :courseID)");
                $stmt->bindParam("userID", $this->userID);
                $stmt->bindParam("courseID", $this->courseID);

                $stmt->execute();

                $this->applicationID = $this->dbh->lastInsertId();

            } catch (\PDOException $e) {

            }
            //Save Responses
            foreach ($this->responses as $response) {

                $response->setApplicationID($this->applicationID);
                $response->saveResponse();
            }
        }
        else
        {
            //Update Responses
            foreach ($this->responses as $response) {
                $response->saveResponse();
            }
        }
    }

    public static function getApplicationsByCourse($courseID)
    {
        try
        {
            if(isset($courseID) && !empty($courseID) && is_int($courseID))
            {
                $db = DatabaseConnection::getInstance();
                $stmt = $db->prepare("SELECT  a.applicationID, u.userID, c.courseID, u.username, u.firstname, u.lastname, c.classNumber, c.courseYear, c.courseSemester, c.instructorID, c.openDate, c.closeDate, a.dateCreated
                                            FROM applications a LEFT JOIN users u ON (a.userID = u.userID)
                                            LEFT JOIN courses c ON (a.courseID = c.courseID) 
                                            WHERE a.courseID = :courseID
                                            ORDER BY a.dateCreated DESC");
                $stmt->bindParam("courseID", $courseID);
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);

                $applications = $stmt->fetchAll();

                for ($i=0; $i < sizeof($applications); ++$i)
                {
                    $responses = Responses::getResponseByApplicationID($applications[$i]['applicationID']);
                    $applications[$i]['responses'] = $responses;
                }

                return $applications;
            }
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function getApplicationsByUser($userID)
    {
        try
        {
            if(isset($userID) && !empty($userID) && is_int($userID))
            {
                $db = DatabaseConnection::getInstance();
                $stmt = $db->prepare("SELECT  a.applicationID, u.userID, c.courseID, u.username, u.firstname, u.lastname, c.classNumber, c.courseYear, c.courseSemester, c.instructorID, c.openDate, c.closeDate
                                            FROM applications a LEFT JOIN users u ON (a.userID = u.userID)
                                            LEFT JOIN courses c ON (a.courseID = c.courseID) 
                                            WHERE a.userID = :userID
                                            ORDER BY a.dateCreated DESC");
                $stmt->bindParam("userID", $userID);
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);

                $applications = $stmt->fetchAll();

                for ($i=0; $i < sizeof($applications); ++$i)
                {
                    $responses = Responses::getResponseByApplicationID($applications[$i]['applicationID']);
                    $applications[$i]['responses'] = $responses;
                }

                return $applications;
            }
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function GetFullApplicationByID($applicationID)
    {
        try
        {
            if(isset($applicationID) && !empty($applicationID) && is_int($applicationID))
            {
                $db = DatabaseConnection::getInstance();
                $stmt = $db->prepare("SELECT  a.applicationID, u.userID, c.courseID, u.username, u.firstname, u.lastname, c.classNumber, c.courseYear, c.courseSemester, c.instructorID, c.openDate, c.closeDate
                                            FROM applications a LEFT JOIN users u ON (a.userID = u.userID)
                                            LEFT JOIN courses c ON (a.courseID = c.courseID) 
                                            WHERE a.applicationID = :applicationID
                                            ORDER BY a.dateCreated DESC");
                $stmt->bindParam("applicationID", $applicationID);
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);

                $rows = $stmt->rowCount();

                if ($rows === 1)
                {
                    $applications = $stmt->fetch();

                    $responses = Responses::getResponseByApplicationID($applications['applicationID']);
                    $applications['responses'] = $responses;

                    return $applications;
                }
            }
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function ApplicationAlreadySubmitted($userID, $courseID) : bool
    {
        try
        {
            if(isset($userID) && !empty($userID) && is_int($userID)
            && isset($courseID) && !empty($courseID) && is_int($courseID))
            {
                $db = DatabaseConnection::getInstance();
                $stmt = $db->prepare("SELECT * FROM applications WHERE userID = :userID AND courseID = :courseID");
                $stmt->bindParam("userID", $userID);
                $stmt->bindParam("courseID", $courseID);
                $stmt->execute();

                $rows = $stmt->rowCount();

                return ($rows === 1);
            }
        }
        catch (\PDOException $e)
        {

        }


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
        if(isset($applicationID) && !empty($applicationID) && is_int($applicationID))
        {
            $this->applicationID = $applicationID;
        }
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
        if(isset($userID) && !empty($userID) && is_int($userID))
        {
            $this->userID = $userID;
        }
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
        if(isset($courseID) && !empty($courseID) && is_int($courseID))
        {
            $this->courseID = $courseID;
        }
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
        if(isset($dateCreated) && !empty($dateCreated))
        {
            $this->dateCreated = $dateCreated;
        }
    }

    /**
     * @return mixed
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param mixed $responses
     */
    public function setResponses($responses)
    {
        if(isset($responses) && !empty($responses))
        {
            $this->responses = $responses;
        }
    }

}