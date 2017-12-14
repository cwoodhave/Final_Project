<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 6:08 PM
 */

use Model\Users as Users;
use Model\Courses as Courses;

require_once '../Model/Users.php';
require_once '../Model/Courses.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' || !isset($_SESSION['login_user_isAdmin']) || !$_SESSION['login_user_isAdmin']){
    header("Location: ../index.php");
}

$instructors = Users::GetInstructors();

//Validate DateTime as per php.net
function validateDate($date, $format = 'Y-m-d\TH:i')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (isset($_POST['submit'])) {
        $classNumber = "";
        $courseYear = 0;
        $courseSemester = "";
        $instructorID = "";
        $openDate = new DateTime();
        $closeDate = new DateTime();
        $ok = true;
        //Verify fields are not empty and that they exist
        if (empty($_POST['classNumber']) || !isset($_POST['classNumber']) || ($_POST['classNumber'] !== "CS4800" && $_POST['classNumber'] !== "CS4890")){
            $ok = false;
            $error[] = "Invalid class number";
        } else {
            $classNumber = filter_var(trim($_POST['classNumber']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['courseYear']) || !isset($_POST['courseYear']) || !is_numeric($_POST['courseYear']) || $_POST['courseYear'] < 2015 || $_POST['courseYear'] > 2100){
            $ok = false;
            $error[] = "Invalid course year";
        } else {
            $courseYear = $_POST['courseYear'];
        }
        if (empty($_POST['courseSemester']) || !isset($_POST['courseSemester'])
            || ($_POST['courseSemester'] !== "FALL" && $_POST['courseSemester'] !== "SPRING" && $_POST['courseSemester'] !== "SUMMER")){
            $ok = false;
            $error[] = "Invalid course semester";
        } else {
            $courseSemester = filter_var(trim($_POST['courseSemester']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['instructorID']) || !isset($_POST['instructorID']) || !is_numeric($_POST['instructorID'])){
            $ok = false;
            $error[] = "Invalid instructor";
        } else {
            $instructorID = filter_var($_POST['instructorID'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (empty($_POST['openDate']) || !isset($_POST['openDate']) || !is_string($_POST['openDate']) || !validateDate($_POST['openDate'])){
            $ok = false;
            $error[] = "Invalid Open Date";
        } else {
            $openDate =new DateTime($_POST['openDate']);
        }
        if (empty($_POST['closeDate']) || !isset($_POST['closeDate']) || !is_string($_POST['closeDate'])|| !validateDate($_POST['closeDate'])){
            $ok = false;
            $error[] = "Invalid Close Date";
        } else {
            $closeDate = new DateTime($_POST['closeDate']);
        }

        if($ok)
        {
            $newCourse = new Courses();
            $newCourse->setClassNumber($classNumber);
            $newCourse->setCourseYear($courseYear);
            $newCourse->setCourseSemester($courseSemester);
            $newCourse->setInstructorID($instructorID);
            $newCourse->setOpenDate($openDate);
            $newCourse->setCloseDate($closeDate);
            $newCourse->saveCourse();
            header("location: create_course.php");
        }
    }
}

require_once '_header.php';

echo "<h3>Create New Course</h3>
        <form method='post'>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='classNumber' >Class Number: </label>
            <select class='col-sm-5' name='classNumber' id='classNumber'>
                <option value=''></option>
                <option value='CS4800'>CS 4800</option>
                <option value='CS4890'>CS 4890</option>
            </select>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='courseYear'>Course Year: </label>
            <input class='col-sm-5' type='year' min='2015' max='2100' value='" . date("Y") . "' name='courseYear' id='courseYear' /><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='courseSemester'>Course Semester: </label>
            <select class='col-sm-5' name='courseSemester' id='courseSemester'>
                <option value=''></option>
                <option value='FALL'>Fall</option>
                <option value='SPRING'>Spring</option>
                <option value='SUMMER'>Summer</option>
            </select>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='instructorID'>Instructor: </label>
            <select class='col-sm-5' name='instructorID' id='instructorID'>
                <option value=''></option>";

                foreach ($instructors as $instructor){
                    echo "<option value='" . $instructor['userID'] . "'>" . $instructor['firstname'] . " " . $instructor['lastname'] . "</option>";
                }

echo        "</select>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='openDate'>Open Date: </label>
            <input class='col-sm-5' type='datetime-local' name='openDate' id='openDate' >         
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='closeDate'>Close Date: </label>
            <input class='col-sm-5' type='datetime-local' name='closeDate' id='closeDate' ><br/>           
        </div> 
        <div class='row' style='margin-top: 5px;'>
            <input class='btn btn-success' type='submit' name='submit' value='Create Course'/> 
        </div>
       </form><br>";

require_once  '_footer.php';