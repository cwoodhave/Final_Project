<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/8/2017
 * Time: 10:46 AM
 */

namespace Model;

use Utility\DatabaseConnection as DatabaseConnection;

require_once (dirname(__FILE__) .  '/../Utility/DatabaseConnection.php');

class Notifications
{
    private $notificationID;
    private $applicationID;
    private $dateSent;
    private $sentFrom;
    private $notificationText;
    private $dbh;

    function __construct()
    {
        $this->dbh = DatabaseConnection::getInstance();
    }



    /**
     * @return mixed
     */
    public function getNotificationID()
    {
        return $this->notificationID;
    }

    /**
     * @param mixed $notificationID
     */
    public function setNotificationID($notificationID)
    {
        $this->notificationID = $notificationID;
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
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * @param mixed $dateSent
     */
    public function setDateSent($dateSent)
    {
        $this->dateSent = $dateSent;
    }

    /**
     * @return mixed
     */
    public function getSentFrom()
    {
        return $this->sentFrom;
    }

    /**
     * @param mixed $sentFrom
     */
    public function setSentFrom($sentFrom)
    {
        $this->sentFrom = $sentFrom;
    }

    /**
     * @return mixed
     */
    public function getNotificationText()
    {
        return $this->notificationText;
    }

    /**
     * @param mixed $notificationText
     */
    public function setNotificationText($notificationText)
    {
        $this->notificationText = $notificationText;
    }


}