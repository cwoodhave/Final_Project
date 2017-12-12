<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 6:08 PM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' || !isset($_SESSION['login_user_isAdmin']) || !$_SESSION['login_user_isAdmin']){
    header("Location: ../index.php");
}

use Model\Applications as Applications;
use Model\Courses as Courses;
use Model\Users as Users;

require_once '../Model/Applications.php';
require_once '../Model/Courses.php';
require_once '../Model/Users.php';

$user = new Users($_SESSION['login_user']);
$userID= $user->getUserID();
$courses = Courses::getCoursesByInstructor($userID);

require_once '_header.php';

echo "<h2>Student Applications</h2><br>
        <div class='panel-group'>";

$counter = 1;

foreach ($courses as $course)
{
    $applications = Applications::getApplicationsByCourse($course['courseID']);
    $opens = date_create($course['openDate']);
    $closes = date_create($course['closeDate']);

    echo "<div class='panel panel-default'>
            <div class='panel-heading'  data-toggle='collapse' href='#collapse$counter'>
               <h3 class='panel-title'>"
                . $course['classNumber'] . ": " . $course['courseSemester'] . " " . $course['courseYear'] . "</h3>
                <h5>Opens: " . date_format($opens, 'F j, Y, g:i a') . " | Closes: " . date_format($closes, 'F j, Y, g:i a') . "</h5>
                
            </div>
            <div id='collapse$counter' class='panel-collapse collapse' 
            <div class='panel-body' >";

    if(empty($applications))
    {
        echo "<h4>None Found</h4>";
    }
    else
    {
        foreach ($applications as $application)
        {
            $submitted = date_create($application['dateCreated']);
            echo "<hr style='border-width: 1px; border-color: #666666;' >
                    <h4>Student: " . $application['firstname'] . " " . $application['lastname'] . "</h4>
                    <h5>Submitted: " . date_format($submitted, 'F j, Y, g:i a') . "</h5>";

            foreach ($application['responses'] as $response)
            {
                if($response['questionID'] !== 'name' && $response['questionID'] !== 'w_number'
                    && $response['questionID'] !== 'credits' && $response['questionID'] !== 'hours')
                {
                    echo "<div class='row' style='margin-top: 5px;'>
                        <label class='col-sm-2 text-right' for='" . $response['questionID'] . "'>" . ucfirst($response['questionID']) . ": </label>
                        <textarea class='col-sm-9' name='" . $response['questionID'] . "' id='" . $response['questionID'] . "' readonly>" . $response['responseText'] . "</textarea>
                    </div>";
                }
                else
                {
                    echo "<div class='row' style='margin-top: 5px;'>
                        <label class='col-sm-2 text-right' for='" . $response['questionID'] . "'>" . ucfirst($response['questionID']) . ": </label>
                        <p class='col-sm-9 text-left' name='" . $response['questionID'] . "' id='" . $response['questionID'] . "'>" . $response['responseText'] . "</p>
                    </div>";
                }
            }

            echo "<form method='POST' action='notifications_admin.php'>
                        <p>Send a new notification to the user for this application.</p>
                        <input type='hidden' name='applicationID' value='" . $application['applicationID'] . "'>
                        <input type='hidden' name='sentFrom' value='$userID'>
                        <div class='row'><textarea cols='80' rows='5' name='notificationText', id='notificationText'></textarea></div>
                        <div class='row'><input type='submit' class='btn btn-warning' name='submit' value='Send Notification'></div>
                    </form>
                    <br>";
        }
    }
    echo "</div></div>";
    $counter++;
}

require_once '_footer.php';