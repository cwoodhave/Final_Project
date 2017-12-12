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
    private $viewed;
    private $dbh;

    function __construct()
    {
        $this->notificationID = null;
        $this->dbh = DatabaseConnection::getInstance();
    }

    function saveNotification()
    {
        if($this->notificationID === null)
        {
            try
            {
                $stmt= $this->dbh->prepare('INSERT INTO notifications (applicationID, sentFrom, notificationText)
                                                      VALUES (:applicationID, :sentFrom, :notificationText)');
                $stmt->bindParam('applicationID', $this->applicationID);
                $stmt->bindParam('sentFrom', $this->sentFrom);
                $stmt->bindParam('notificationText', $this->notificationText);
                $stmt->execute();

                $this->notificationID = $this->dbh->lastInsertId();

            }
            catch (\PDOException $e)
            {

            }
        }
    }

    public static function GetNotificationsByApplicationID($applicationID)
    {
        try
        {
            $db = DatabaseConnection::getInstance();
            $stmt = $db->prepare('SELECT n.notificationID, n.applicationID, n.dateSent, n.sentFrom, n.notificationText, n.viewed, 
                                                  CONCAT(u.firstname, " ", u.lastname) AS fromName
                                            FROM notifications n LEFT JOIN users u ON (n.sentFrom = u.userID)
                                            WHERE applicationID = :applicationID
                                            ORDER BY dateSent DESC;');
            $stmt->bindParam('applicationID', $applicationID);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $stmt->fetchAll();

        }
        catch (\PDOException $e)
        {

        }
    }

    public static function SetNotificationAsViewed($notificationID)
    {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare('UPDATE notifications
                                        SET viewed = TRUE
                                        WHERE notificationID = :notificationID');
        $stmt->bindParam('notificationID', $notificationID);
        $stmt->execute();
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

    /**
     * @return mixed
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * @param mixed $viewed
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
    }






}