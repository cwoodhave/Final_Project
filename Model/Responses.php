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

    function __construct()
    {
        $this->dbh = DatabaseConnection::getInstance();
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