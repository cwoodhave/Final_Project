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

        if($applicationID !== null)
        {
            $this->getApplicationByID(@$applicationID);
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

        }
        catch (\PDOException $e)
        {

        }
    }

    public static function getApplicationsByUser($userID)
    {
        try
        {
            $db = DatabaseConnection::getInstance();
            $stmt = $db->prepare("SELECT  a.applicationID, u.userID, c.courseID, u.username, u.firstname, u.lastname, c.classNumber, c.courseYear, c.courseSemester, c.instructorID, c.openDate, c.closeDate
                                            FROM applications a LEFT JOIN users u ON (a.userID = u.userID)
                                            LEFT JOIN courses c ON (a.courseID = c.courseID) 
                                            WHERE a.userID = :userID");
            $stmt->bindParam("userID", $userID);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
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
        $this->responses = $responses;
    }



}