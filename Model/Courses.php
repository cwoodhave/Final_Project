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

class Courses
{
    const CS4800 = 0;
    const CS4890 = 1;

    const FALL = 0;
    const SPRING = 1;
    const SUMMER = 2;

    private $courseID;
    private $classNumber;
    private $courseYear;
    private $courseSemester;
    private $instructorID;
    private $openDate;
    private $closeDate;
    private $dbh;

    function __construct($courseID = null)
    {
        $this->dbh = DatabaseConnection::getInstance();
        $this->courseID = null;

        if($courseID !== null)
        {
            $this->getCourseByID($courseID);
        }
    }

    function getCourseByID($courseID)
    {
        try
        {
            $stmthndl = $this->dbh->prepare("SELECT * FROM courses WHERE courseID = :courseID");
            $stmthndl->bindParam("courseID", $courseID);

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

    function getCourseBySemester($year, $semester)
    {
        try
        {
            $stmthndl = $this->dbh->prepare("SELECT * FROM courses WHERE courseYear = :courseYear AND courseSemester = :courseSemester");
            $stmthndl->bindParam("courseYear", $year);
            $stmthndl->bindParam("courseSemester", $semester);

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

    function saveCourse($classNumber, $courseYear, $courseSemester, $instructorID, $openDate, $closeDate)
    {
        try
        {
            if($this->courseID === null)
            {
                $stmt = $this->dbh->prepare("INSERT INTO courses (classNumber, courseYear, courseSemester, instructorID, openDate, closeDate)
                                              VALUES (:classNumber, :courseYear, :courseSemester, :instructorID, :openDate, :closeDate)");
                $stmt->bindParam('classNumber', $classNumber);
                $stmt->bindParam('courseYear', $courseYear);
                $stmt->bindParam('courseSemester', $courseSemester);
                $stmt->bindParam('instructorID', $instructorID);
                $stmt->bindParam('openDate', $openDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
                $stmt->bindParam('closeDate', $closeDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);

                $stmt->execute();

                $this->courseID = $this->dbh->lastInsertId();
            }
            else
            {
                $stmt = $this->dbh->prepare("UPDATE courses
                                              SET classNumber = :classNumber, courseYear = :courseYear, courseSemester = :courseSemester, 
                                              instructorID = :instructorID, openDate = :openDate, closeDate = :closeDate)
                                              WHERE courseID = :courseID");
                $stmt->bindParam('classNumber', $classNumber);
                $stmt->bindParam('courseYear', $courseYear);
                $stmt->bindParam('courseSemester', $courseSemester);
                $stmt->bindParam('instructorID', $instructorID);
                $stmt->bindParam('openDate', $openDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
                $stmt->bindParam('closeDate', $closeDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
                $stmt->bindParam('courseID', $this->courseID);

                $stmt->execute();
            }

            $this->classNumber = $classNumber;
            $this->courseYear = $courseYear;
            $this->courseSemester = $courseSemester;
            $this->instructorID = $instructorID;
            $this->openDate = $openDate;
            $this->closeDate = $closeDate;
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function getActiveCourses()
    {
        try
        {
            $db = DatabaseConnection::getInstance();
            $now = date("Y-m-d H:i:s");

            $stmt = $db->prepare("SELECT courseID, classNumber, courseYear, courseSemester, instructorID, openDate, closeDate,  firstname AS instructorFirstname, lastname AS instructorLastname
                                            FROM courses LEFT JOIN users ON (courses.instructorID = users.userID) 
                                            WHERE openDate <= :now AND closeDate >= :now
                                            ORDER BY closeDate ASC; ");
            $stmt->bindParam('now', $now, \PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function getPreviousCourses()
    {
        try
        {
            $db = DatabaseConnection::getInstance();
            $now = date("Y-m-d H:i:s");

            $stmt = $db->prepare("SELECT courseID, classNumber, courseYear, courseSemester, instructorID, openDate, closeDate,  firstname AS instructorFirstname, lastname AS instructorLastname
                                            FROM courses LEFT JOIN users ON (courses.instructorID = users.userID) 
                                            WHERE closeDate < :now 
                                            ORDER BY closeDate ASC; ");
            $stmt->bindParam('now', $now, \PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function getFutureCourse()
    {
        try
        {
            $db = DatabaseConnection::getInstance();
            $now = date("Y-m-d H:i:s");

            $stmt = $db->prepare("SELECT courseID, classNumber, courseYear, courseSemester, instructorID, openDate, closeDate,  firstname AS instructorFirstname, lastname AS instructorLastname
                                            FROM courses LEFT JOIN users ON (courses.instructorID = users.userID) 
                                            WHERE openDate > :now 
                                            ORDER BY closeDate ASC; ");
            $stmt->bindParam('now', $now, \PDO::PARAM_STR);
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
    public function getClassNumber()
    {
        return $this->classNumber;
    }

    /**
     * @param mixed $classNumber
     */
    public function setClassNumber($classNumber)
    {
        $this->classNumber = $classNumber;
    }

    /**
     * @return mixed
     */
    public function getCourseYear()
    {
        return $this->courseYear;
    }

    /**
     * @param mixed $courseYear
     */
    public function setCourseYear($courseYear)
    {
        $this->courseYear = $courseYear;
    }

    /**
     * @return mixed
     */
    public function getCourseSemester()
    {
        return $this->courseSemester;
    }

    /**
     * @param mixed $courseSemester
     */
    public function setCourseSemester($courseSemester)
    {
        $this->courseSemester = $courseSemester;
    }

    /**
     * @return mixed
     */
    public function getInstructorID()
    {
        return $this->instructorID;
    }

    /**
     * @param mixed $instructorID
     */
    public function setInstructorID($instructorID)
    {
        $this->instructorID = $instructorID;
    }

    /**
     * @return mixed
     */
    public function getOpenDate()
    {
        return $this->openDate;
    }

    /**
     * @param mixed $openDate
     */
    public function setOpenDate($openDate)
    {
        $this->openDate = $openDate;
    }

    /**
     * @return mixed
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * @param mixed $closeDate
     */
    public function setCloseDate($closeDate)
    {
        $this->closeDate = $closeDate;
    }



}