<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/12/2017
 * Time: 12:26 PM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' || !isset($_SESSION['login_user_isAdmin']) || !$_SESSION['login_user_isAdmin']){
    header("Location: ../index.php");
}

use Model\Applications as Applications;
use Model\Notifications as Notifications;
use Model\Courses as Courses;
use Model\Users as Users;

require_once '../Model/Applications.php';
require_once '../Model/Notifications.php';
require_once '../Model/Courses.php';
require_once '../Model/Users.php';

$user = new Users($_SESSION['login_user']);
$userID = $user->getUserID();
$courses = Courses::getCoursesByInstructor($user->getUserID());

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(!empty($_POST['action']) && $_POST['action'] == 'post_notification'){
        echo "<p>Message for application id " . $_POST['appID'] . "</p>";
        die();
    }

    if (isset($_POST['submit'])) {
        $applicationID = 0;
        $sentFrom = 0;
        $notificationText = "";
        $ok = true;
        //Verify fields are not empty and that they exist
        if (empty($_POST['applicationID']) || !isset($_POST['applicationID']) || !is_numeric($_POST['applicationID'])){
            $ok = false;
            $error[] = "Something went wrong.  Message was not sent.";
        } else {
            $applicationID = filter_var($_POST['applicationID'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (empty($_POST['sentFrom']) || !isset($_POST['sentFrom']) || !is_numeric($_POST['sentFrom']) || $_POST['sentFrom'] !== $user->getUserID()){
            $ok = false;
            $error[] = "Something went wrong.1  Message was not sent.";
        } else {
            $sentFrom = filter_var($_POST['sentFrom'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (empty($_POST['notificationText']) || !isset($_POST['notificationText'])){
            $ok = false;
            $error[] = "Something went wrong.  Message was not sent.  Must provide a message";
        } else {
            $notificationText = filter_var($_POST['notificationText'], FILTER_SANITIZE_STRING);
        }

        if($ok)
        {
            $newNotification = new Notifications();
            $newNotification->setApplicationID($applicationID);
            $newNotification->setSentFrom($sentFrom);
            $newNotification->setNotificationText($notificationText);
            $newNotification->saveNotification();
            header("Refresh:0");
        }
    }
}

require_once '_header.php';

echo "<h2>Notifications</h2><br>
        <div class='panel-group'>";

$counter = 1;
$innerCounter = 1;

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
        echo "<div class='panel panel-group'>"; //inner collapse structure
        foreach ($applications as $application)
        {
            $notifications = Notifications::GetNotificationsByApplicationID($application['applicationID']);

            echo "
                    <div class='panel panel-default'>
                    <div class='panel-heading' data-toggle='collapse' href='#innercollapse$innerCounter'>
                        <h4 class='panel-title'>Student: " . $application['firstname'] . " " . $application['lastname'] . "</h4>
                    </div>
                    
                    <div id='innercollapse$innerCounter' class='panel-collapse collapse'>
                    <div class='panel-body'>
                    <form method='POST'>
                        <p>Send a new notification to the user for this application.</p>
                        <input type='hidden' name='applicationID' value='" . $application['applicationID'] . "'>
                        <input type='hidden' name='sentFrom' value='$userID'>
                        <div class='row'><textarea cols='80' rows='5' name='notificationText', id='notificationText'></textarea></div>
                        <div class='row'><input type='submit' class='btn btn-warning' name='submit' value='Send Notification'></div>
                    </form>";

            if(!empty($notifications)) {
                foreach ($notifications as $notification) {
                    $sent = date_create($notification['dateSent']);

                    echo "<hr style='border-width: 1px; border-color: #666666;' >
                            <div class='row' style='margin-top: 5px;'>
                            <div class='row'><p>Sent From : " . $notification['fromName'] . " </p></div>
                            <div class='row'><p>Sent On: " . date_format($sent, 'F j, Y, g:i a') . "</p></div>
                            <div class='row'>
                            <label class='col-sm-2 text-right' for='message'>Message: </label>
                            <textarea class='col-sm-9' name='message' readonly>" . $notification['notificationText'] . "</textarea>
                            </div>
                        </div>";

                }
            }
            $innerCounter++;
            //end inner collapse body
            echo "</div></div></div><br>";
        }
        echo "</div>"; //end inner collapse structure
    }
    echo "</div></div>";
    $counter++;
}

echo "</div>";

require_once '_footer.php';