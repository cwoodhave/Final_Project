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

class Responses
{
    private $responseID;
    private $applicationID;
    private $questionID;
    private $responseText;
    private $dbh;

    function __construct($responseID = null)
    {
        $this->dbh = DatabaseConnection::getInstance();
        $this->responseID = null;

        if($responseID !== null)
        {
            $this->getResponseByID($responseID);
        }
    }

    private function getResponseByID($responseID)
    {
        try
        {
            $stmthndl = $this->dbh->prepare("SELECT * FROM responses WHERE responseID = :responseID");
            $stmthndl->bindParam("responseID", $responseID);

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
        }
        catch (\PDOException $e)
        {

        }
    }

    function saveResponse()
    {
        if($this->responseID === null) {
            try {
                $stmt = $this->dbh->prepare("INSERT INTO responses (applicationID, questionID, responseText)
                                              VALUES (:applicationID, :questionID, responseText)");
                $stmt->bindParam("applicationID", $this->applicationID);
                $stmt->bindParam("questionID", $this->questionID);
                $stmt->bindParam("responseText", $this->responseText);

                $stmt->execute();

                $this->responseID = $this->dbh->lastInsertId();

            } catch (\PDOException $e) {

            }
        }
        else
        {
            $stmt = $this->dbh->prepare("UPDATE responses
                                              SET responseText = :responseText
                                              WHERE responseID = :responseID");
            $stmt->bindParam('responseText', $this->responseText);
            $stmt->bindParam('responseID', $this->responseID);

            $stmt->execute();
        }
    }

    /**
     * @return mixed
     */
    public function getResponseID()
    {
        return $this->responseID;
    }

    /**
     * @param mixed $responseID
     */
    public function setResponseID($responseID)
    {
        $this->responseID = $responseID;
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
    public function getQuestionID()
    {
        return $this->questionID;
    }

    /**
     * @param mixed $questionID
     */
    public function setQuestionID($questionID)
    {
        $this->questionID = $questionID;
    }

    /**
     * @return mixed
     */
    public function getResponseText()
    {
        return $this->responseText;
    }

    /**
     * @param mixed $responseText
     */
    public function setResponseText($responseText)
    {
        $this->responseText = $responseText;
    }



}