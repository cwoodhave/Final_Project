<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/8/2017
 * Time: 10:45 AM
 */

namespace Model;

use Utility\DatabaseConnection as DatabaseConnection;

//require_once 'DatabaseConnection.php';
require_once (dirname(__FILE__) .  '/../Utility/DatabaseConnection.php');

class Users
{
    private $userID;
    private $username;
    private $password;
    private $firstname;
    private $lastname;
    private $email;
    private $isAdmin;
    private $dbh;

    function __construct($username = null)
    {
        $this->dbh = DatabaseConnection::getInstance();
        $this->userID = null;

        if($username != null)
        {
            $this->getUser($username);
        }
    }


    private function getUser($username)
    {
        try
        {
            $stmthndl = $this->dbh->prepare("SELECT * FROM users WHERE username = :username");
            $stmthndl->bindParam("username", $username);

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

    function saveUser($username, $password, $first, $last, $email)
    {
        try {
            //Create user if none exists
            if ($this->userID === null)
            {
                $stmthndl = $this->dbh->prepare("INSERT INTO users (username, password, firstname, lastname, email)
                                                    VALUES (:username, :password, :firstname, :lastname, :email)");
                $stmthndl->bindParam("username", $username);
                $stmthndl->bindParam("password", $password);
                $stmthndl->bindParam("firstname", $first);
                $stmthndl->bindParam("lastname", $last);
                $stmthndl->bindParam("email", $email);

                $stmthndl->execute();

                $this->userID = $this->dbh->lastInsertId();
                $this->isAdmin = false;
            }
            //Update user if already exists
            else
            {
                $stmthndl = $this->dbh->prepare("UPDATE users
                                                 SET username = :username, firstname = :firstname,
                                                lastname = :lastname, email = :email
                                                WHERE userID = :userID");
                $stmthndl->bindParam("username", $username);
                $stmthndl->bindParam("firstname", $first);
                $stmthndl->bindParam("lastname", $last);
                $stmthndl->bindParam("email", $email);
                $stmthndl->bindParam("userID", $this->userID);

                $stmthndl->execute();
            }

            $this->username = $username;
            $this->password = $password;
            $this->firstname = $first;
            $this->lastname = $last;
            $this->email = $email;

        }
        catch (\PDOException $e)
        {

        }
    }

    function updatePassword($oldPassword, $newPassword)
    {
        if(self::VerifyPassword($this->username,$oldPassword))
        {
            try
            {
                $stmthndl = $this->dbh->prepare("UPDATE users
                                                 SET password = :password
                                                WHERE userID = :userID");
                $stmthndl->bindParam("password", $newPassword);
                $stmthndl->bindParam("userID", $this->userID);

                $stmthndl->execute();
            }
            catch (\PDOException $e)
            {

            }
        }
    }

    public static function UserExists($username, $userID = null) : bool
    {
        try
        {
            $db = DatabaseConnection::getInstance();

            if($userID === null)
            {
                $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->bindParam("username", $username);
                $stmt->execute();

                $rows = $stmt->rowCount();

                if ($rows == 1)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            //Else statement for when user is updating their profile
            //Want to let them change their user info without updating their username
            else
            {
                $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND userID != :userID");
                $stmt->bindParam("username", $username);
                $stmt->bindParam('userID', $userID);
                $stmt->execute();

                $rows = $stmt->rowCount();

                if ($rows == 1)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }

        }
        catch (\PDOException $e)
        {

        }
    }

    public static function VerifyPassword($username, $password) : bool
    {
        try
        {
            if(self::UserExists($username))
            {
                $db = DatabaseConnection::getInstance();
                $stmt = $db->prepare("SELECT password FROM users WHERE username = :username");
                $stmt->bindParam("username", $username);
                $stmt->execute();

                $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                $results = $stmt->fetch();
                $passwordHash = $results['password'];
                return (password_verify($password, $passwordHash));

            }
            else
            {
                return false;
            }
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function GetInstructors()
    {
        try
        {
            $db = DatabaseConnection::getInstance();
            $stmt = $db->prepare("SELECT * FROM users WHERE isAdmin = TRUE");

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
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        if(isset($userID) && !empty($userID) && is_numeric($userID))
        {
            $this->userID = $userID;
        }
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        if(isset($username) && !empty($username))
        {
            $this->username = $username;
        }
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        if(isset($password) && !empty($password))
        {
            $this->password = $password;
        }
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        if(isset($firstname) && !empty($firstname))
        {
            $this->firstname = $firstname;
        }
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        if(isset($lastname) && !empty($lastname))
        {
            $this->lastname = $lastname;
        }
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        if(isset($email) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) )
        {
            $this->email = $email;
        }
    }

    /**
     * @return mixed
     */
    public function getisAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param mixed $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        if(isset($isAdmin))
        {
            $this->isAdmin = $isAdmin;
        }
    }



}