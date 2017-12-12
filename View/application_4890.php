<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 6:07 PM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === ''){
    header("Location: ../index.php");
}

use Model\Applications as Applications;
use Model\Responses as Responses;
use Model\Courses as Courses;
use Model\Users as Users;

require_once '../Model/Applications.php';
require_once '../Model/Responses.php';
require_once '../Model/Courses.php';
require_once '../Model/Users.php';

$name = "";
$w_number = "";
$description = "";
$hours = 1;
$courseID = 0;

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (isset($_POST['submit'])) {
        $ok = true;
        //Verify fields are not empty and that they exist
        if (empty($_POST['name']) || !isset($_POST['name'])){
            $ok = false;
            $error[] = "Please give your full name";
        } else {
            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['w_number']) || !isset($_POST['w_number']) || strlen($_POST['w_number']) != 9 || !preg_match('/^W[0-9]{8}$/i', $_POST['w_number'])){
            $ok = false;
            $error[] = "Please give your W# including the W";
        } else {
            $w_number = filter_var(ucfirst(trim($_POST['w_number'])), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['description']) || !isset($_POST['description'])){
            $ok = false;
            $error[] = "Please provide a job description";
        } else {
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['hours']) || !isset($_POST['hours']) || !is_numeric($_POST['hours'])
            || $_POST['hours'] > 168 || $_POST['hours'] < 0){
            $ok = false;
            $error[] = "Please select hours you work per week (1 to 168)";
        } else {
            $hours = filter_var($_POST['hours'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (empty($_POST['courseID']) || !isset($_POST['courseID']) || !is_numeric($_POST['courseID']) ){
            $ok = false;
            $error[] = "Please select an available course.  If no course is listed, contact CS department.";
        } else {
            $courseID = filter_var($_POST['courseID'], FILTER_SANITIZE_NUMBER_INT);
        }

        if($ok)
        {
            $user = new Users($_SESSION['login_user']);

            if(Applications::ApplicationAlreadySubmitted($user->getUserID(), $courseID))
            {
                $error[] = "You have already submitted an application for this course.";
            }
            else
            {

                $r_name = new Responses();
                $r_name->setQuestionID('name');
                $r_name->setResponseText($name);
                $responses[] = $r_name;
                $r_wnum = new Responses();
                $r_wnum->setQuestionID('w_number');
                $r_wnum->setResponseText($w_number);
                $responses[] = $r_wnum;
                $r_desc = new Responses();
                $r_desc->setQuestionID('description');
                $r_desc->setResponseText($description);
                $responses[] = $r_desc;
                $r_hours = new Responses();
                $r_hours->setQuestionID('hours');
                $r_hours->setResponseText($hours);
                $responses[] = $r_hours;

                $newApplication = new Applications();
                $newApplication->setCourseID($courseID);
                $newApplication->setUserID($user->getUserID());
                $newApplication->setResponses($responses);

                $newApplication->saveApplication();

                header("location: user_applications.php");
            }
        }
    }
}
$activeCourses = Courses::getActiveCourses('CS4890');

require_once '_header.php';

echo "<h2>CS 4890 - Cooperative Work Experience</h2>
        <h4>Employment Qualifications Guide</h4>
        <p style='text-align: left'>CS 4890 is designed to allow students to receive upper division CS credit for their
            employment during a semester. This course requires that the student perform work at a job
            which compliments the Computer Science curriculum. This means that to be considered for
            this course the student should be spending significant time doing programming or advanced
            database management. This is an upper division course and as such requires professional
            expertise equivalent to an upper-division computer science student. Some examples of
            work that will NOT be accepted are: system or network administration, technical support,
            help-desk, PC building/repair, working with spreadsheets, data entry, sales positions, or any
            technical tasks which are simple and repetitive. A student desiring this class must
            communicate with the assigned instructor for the semester during which the student will be
            working if the student has questions regarding this policy, or if a given position qualifies for
            the class. The work must occur during the semester for which the student is receiving
            credit. A student must register for the class prior to the registration deadline and must
            receive instructor approval prior to registering.</p>
            <p>Credit is awarded according to the following schedule:</p>
            <p>1 Credit hour = 5 hours per week</p>
            <p>2 Credit hour = 10 hours per week</p>
            <p>3 Credit hour = 15 hours per week</p>
            <p>4 Credit hour = 20 hours per week</p>
            
        <form method='POST'>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='name'>Name: </label>
                <input class='col-sm-6' type='text' name='name' id='name' value='$name'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='w_number'>W#: </label>
                <input class='col-sm-6' type='text' name='w_number' id='w_number' value='$w_number'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='description'>Job description:<br>Tell me about what you do for a living, and how it relates to computer science. 
                It doesn’t need to be a formal HR description, but it should demonstrate the relationship. 
                This information will allow me to determine if your position justifies upper division CS credit.</label>
                <textarea class='col-sm-8' name='description' id='description' rows='10' >$description</textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='hours'>How many hours per week do you work? (Determines the number of credits.) </label>
                <input class='col-sm-1' type='number' min='0' max='168' name='hours' id='hours' value='$hours'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='course'>Available Courses: </label>
                <select class='col-sm-6' name='courseID' id='courseID'>
                    <option value=''></option>";

                foreach ($activeCourses as $course){
                    echo "<option value='" . $course['courseID'] . "'> " . $course['courseSemester'] . " " . $course['courseYear'] . ", Instructor: "
                        . $course['instructorFirstname'] . " " . $course['instructorLastname'] . "</option>";
                }

echo        "</select>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <br><p style='font-weight: bold'>Note: You need to speak with your boss and make sure they are willing to fill out an employee evaluation (I’ll provide the form) at the end of the semester.</p>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <p style='font-weight: bold'>You must also have taken CS 2420 prior to being able to enroll in this course</p>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <input class='btn btn-primary' type='submit' name='submit' value='Submit Application'/><br/>
            </div>
        </form>";

require_once '_footer.php';