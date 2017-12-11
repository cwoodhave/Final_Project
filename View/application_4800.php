<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 6:05 PM
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
$justification = "";
$method = "";
$resources = "";
$credits = 1;
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
            $error[] = "Please provide a description";
        } else {
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['justification']) || !isset($_POST['justification'])){
            $ok = false;
            $error[] = "Please provide justification";
        } else {
            $justification = filter_var($_POST['justification'], FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['method']) || !isset($_POST['method'])){
            $ok = false;
            $error[] = "Please describe your method";
        } else {
            $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['resources']) || !isset($_POST['resources'])){
            $ok = false;
            $error[] = "Please describe your resources";
        } else {
            $resources = filter_var($_POST['resources'], FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['credits']) || !isset($_POST['credits']) || !is_numeric($_POST['credits'])
            || $_POST['credits'] > 4 || $_POST['credits'] < 1){
            $ok = false;
            $error[] = "Please select requested credits (1 to 4)";
        } else {
            $credits = filter_var($_POST['credits'], FILTER_SANITIZE_NUMBER_INT);
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
            $r_just = new Responses();
            $r_just->setQuestionID('justification');
            $r_just->setResponseText($justification);
            $responses[] = $r_just;
            $r_meth = new Responses();
            $r_meth->setQuestionID('method');
            $r_meth->setResponseText($method);
            $responses[] = $r_meth;
            $r_reso = new Responses();
            $r_reso->setQuestionID('resources');
            $r_reso->setResponseText($resources);
            $responses[] = $r_reso;
            $r_cred = new Responses();
            $r_cred->setQuestionID('credits');
            $r_cred->setResponseText($credits);
            $responses[] = $r_cred;

            $newApplication = new Applications();
            $newApplication->setCourseID($courseID);
            $newApplication->setUserID($user->getUserID());
            $newApplication->setResponses($responses);



            $newApplication->saveApplication();
            //header("location: user_applications.php");
        }
    }
}

$activeCourses = Courses::getActiveCourses('CS4800');

require_once '_header.php';

echo "<h2>CS 4800 - Individual Projects and Research</h2>
        <form method='POST'>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='name'>Name: </label>
                <input class='col-sm-6' type='text' name='name' id='name' value='$name'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='w_number'>W#: </label>
                <input class='col-sm-6' type='text' maxlength='9' minlength='9' name='w_number' id='w_number' value='$w_number'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='description'>Project description:<br>What does the student intend to do?  Provide detail!</label>
                <textarea class='col-sm-8' name='description' id='description' rows='10' >$description</textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='justification'>Justification:<br>Why does the student want to do what is proposed and what rationale can be given for why it ought to be done? (Justify upper division CS credit.)</label>
                <textarea class='col-sm-8' name='justification' id='justification' rows='10' >$justification</textarea><br/>
            </div>
             <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='method'>Method:<br> How does the student propose to accomplish the task, and how long will it take?</label>
                <textarea class='col-sm-8' name='method' id='method' rows='10'>$method</textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='resources'>Resources:<br> What help, equipment, etc., will the student require to accomplish the task?</label>
                <textarea class='col-sm-8' name='resources' id='resources' rows='10'>$resources</textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='credits'>Number of credits you need: </label>
                <input class='col-sm-1' type='number' min='1' max='4' name='credits' id='credits' value='$credits'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='course'>Available Courses: </label>
                <select class='col-sm-5' name='courseID' id='courseID'>
                    <option value=''></option>";

                foreach ($activeCourses as $course){
                    echo "<option value='" . $course['courseID'] . "'> " . $course['courseSemester'] . " " . $course['courseYear'] . ",  Instructor: "
                        . $course['instructorFirstname'] . " " . $course['instructorLastname'] . "</option>";
                }

echo        "</select>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <input class='btn btn-primary' type='submit' name='submit' value='Submit Application'/><br/>
            </div>
        </form>";

require_once '_footer.php';