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
            if(isset($courseID) && !empty($courseID) && is_numeric($courseID))
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
        }
        catch (\PDOException $e)
        {

        }
    }

    function getCourseBySemester($year, $semester)
    {
        try
        {
            if(isset($year) && !empty($year) && isset($semester) && !empty($semester))
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
        }
        catch (\PDOException $e)
        {

        }
    }

    function saveCourse()
    {
        try
        {
            if($this->courseID === null)
            {
                $stmt = $this->dbh->prepare("INSERT INTO courses (classNumber, courseYear, courseSemester, instructorID, openDate, closeDate)
                                              VALUES (:classNumber, :courseYear, :courseSemester, :instructorID, :openDate, :closeDate)");
                $stmt->bindParam('classNumber', $this->classNumber);
                $stmt->bindParam('courseYear', $this->courseYear);
                $stmt->bindParam('courseSemester', $this->courseSemester);
                $stmt->bindParam('instructorID', $this->instructorID);
                $stmt->bindParam('openDate', $this->openDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
                $stmt->bindParam('closeDate', $this->closeDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);

                $stmt->execute();

                $this->courseID = $this->dbh->lastInsertId();
            }
            else
            {
                $stmt = $this->dbh->prepare("UPDATE courses
                                              SET classNumber = :classNumber, courseYear = :courseYear, courseSemester = :courseSemester, 
                                              instructorID = :instructorID, openDate = :openDate, closeDate = :closeDate
                                              WHERE courseID = :courseID");
                $stmt->bindParam('classNumber', $this->classNumber);
                $stmt->bindParam('courseYear', $this->courseYear);
                $stmt->bindParam('courseSemester', $this->courseSemester);
                $stmt->bindParam('instructorID', $this->instructorID);
                $stmt->bindParam('openDate', $this->openDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
                $stmt->bindParam('closeDate', $this->closeDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
                $stmt->bindParam('courseID', $this->courseID);

                $stmt->execute();
            }
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function getActiveCourses($classNumber = null)
    {
        try
        {
            $db = DatabaseConnection::getInstance();
            $now = date("Y-m-d H:i:s");

            if($classNumber === null)
            {
                $stmt = $db->prepare("SELECT courseID, classNumber, courseYear, courseSemester, instructorID, openDate, closeDate,  firstname AS instructorFirstname, lastname AS instructorLastname
                                            FROM courses LEFT JOIN users ON (courses.instructorID = users.userID) 
                                            WHERE openDate <= :now AND closeDate >= :now
                                            ORDER BY closeDate ASC; ");
                $stmt->bindParam('now', $now, \PDO::PARAM_STR);
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);

                return $stmt->fetchAll();
            }
            else
            {
                $stmt = $db->prepare("SELECT courseID, classNumber, courseYear, courseSemester, instructorID, openDate, closeDate,  firstname AS instructorFirstname, lastname AS instructorLastname
                                            FROM courses LEFT JOIN users ON (courses.instructorID = users.userID) 
                                            WHERE openDate <= :now AND closeDate >= :now
                                            AND classNumber = :classNumber
                                            ORDER BY closeDate ASC; ");
                $stmt->bindParam('now', $now, \PDO::PARAM_STR);
                $stmt->bindParam('classNumber', $classNumber);
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);

                return $stmt->fetchAll();
            }

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
                                            ORDER BY closeDate DESC; ");
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
                                            ORDER BY openDate ASC; ");
            $stmt->bindParam('now', $now, \PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch (\PDOException $e)
        {

        }
    }

    public static function getCoursesByInstructor($instructorID)
    {
        try
        {
            if(isset($instructorID) && !empty($instructorID) && is_numeric($instructorID))
            {
                $db = DatabaseConnection::getInstance();
                $stmt = $db->prepare("SELECT * FROM courses WHERE instructorID = :instructorID ORDER BY closeDate DESC;");
                $stmt->bindParam("instructorID", $instructorID);
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);

                return $stmt->fetchAll();
            }
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
        if(isset($courseID) && !empty($courseID) && is_numeric($courseID))
        {
            $this->courseID = $courseID;
        }
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
        if(isset($classNumber) && !empty($classNumber) && is_string($classNumber))
        {
            $this->classNumber = $classNumber;
        }
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
        if(isset($courseYear) && !empty($courseYear) && is_numeric($courseYear) && $courseYear >= 2000 && $courseYear <= 2100)
        {
            $this->courseYear = $courseYear;
        }
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
        if(isset($courseSemester) && !empty($courseSemester) && is_string($courseSemester) &&
            ($courseSemester === 'FALL' || $courseSemester === 'SPRING' || $courseSemester === 'SUMMER'))
        {
            $this->courseSemester = $courseSemester;
        }
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
        if(isset($instructorID) && !empty($instructorID) && is_numeric($instructorID))
        {
            $this->instructorID = $instructorID;
        }
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
        if(isset($openDate) && !empty($openDate) && $this->validateDate($openDate))
        {
            $this->openDate = $openDate;
        }
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
        if(isset($closeDate) && !empty($closeDate) && $this->validateDate($closeDate))
        {
            $this->closeDate = $closeDate;
        }
    }

    //Validate DateTime as per php.net
    private function validateDate($date, $format = 'Y-m-d\TH:i')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


}