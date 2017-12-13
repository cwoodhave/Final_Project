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

if(!isset($_SESSION['login_user']) || empty($_SESSION['login_user'])){
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
$applications = Applications::getApplicationsByUser($userID);

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (isset($_POST['submit'])) {

        $applicationID = 0;
        $sentFrom = 0;
        $sentTo = 0;
        $notificationText = "";
        $ok = true;
        //Verify fields are not empty and that they exist
        if (empty($_POST['applicationID']) || !isset($_POST['applicationID']) || !is_numeric($_POST['applicationID'])){
            $ok = false;
            $error[] = "Something went wrong. 1 Message was not sent.";
        } else {
            $applicationID = filter_var($_POST['applicationID'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (empty($_POST['sentFrom']) || !isset($_POST['sentFrom']) || !is_numeric($_POST['sentFrom']) || $_POST['sentFrom'] !== $user->getUserID()){
            $ok = false;
            $error[] = "Something went wrong. 2 Message was not sent.";
        } else {
            $sentFrom = filter_var($_POST['sentFrom'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (empty($_POST['sentTo']) || !isset($_POST['sentTo']) || !is_numeric($_POST['sentTo'])){
            $ok = false;
            $error[] = "Something went wrong. 3 Message was not sent.";
        } else {
            $sentTo = filter_var($_POST['sentTo'], FILTER_SANITIZE_NUMBER_INT);
        }
        if (empty($_POST['notificationText']) || !isset($_POST['notificationText'])){
            $ok = false;
            $error[] = "Something went wrong. Message was not sent. Must provide a message";
        } else {
            $notificationText = filter_var($_POST['notificationText'], FILTER_SANITIZE_STRING);
        }

        if($ok)
        {
            $application = Applications::GetFullApplicationByID($applicationID);
            if($application['instructorID'] !== $sentTo)
            {
                $error[] = "Something went wrong. 4 Message was not sent.";
            }
            else
            {
                $newNotification = new Notifications();
                $newNotification->setApplicationID($applicationID);
                $newNotification->setSentFrom($sentFrom);
                $newNotification->setNotificationText($notificationText);
                $newNotification->saveNotification();

                $instructor = new Users();
                $instructor->getUserByID($sentTo);

                $subject = "CS 4800 & 4890 Notification System";
                $message = wordwrap("A new notification has been sent to you from one of your students for your " . $application['classNumber'] . " "
                    . $application['courseSemester'] . " " . $application['courseYear'] . " class.");
                $email = $instructor->getEmail();
                $headers = "FROM: no_reply@wsusupplementalapplication.com";

                mail($email, $subject, $message, $headers);

                header("Refresh:0");
            }
        }
    }
}

require_once '_header.php';

echo "<h2>Notifications</h2><br>
        <div class='panel-group'>";

$counter = 1;


foreach ($applications as $application)
{
    $notifications = Notifications::GetNotificationsByApplicationID($application['applicationID']);

    echo "
            <div class='panel panel-default'>
            <div class='panel-heading' data-toggle='collapse' href='#innercollapse$counter'>
                <h4 class='panel-title'>" . $application['classNumber'] . ": " . $application['courseSemester'] . " " . $application['courseYear'] . " &nbsp;<span style='color: slategrey' class='glyphicon glyphicon-plus'></span></h4>
            </div>
            
            <div id='innercollapse$counter' class='panel-collapse collapse'>
            <div class='panel-body'>
            <form method='POST'>
                <p>Send a new notification to the instructor for this application.</p>
                <input type='hidden' name='applicationID' value='" . $application['applicationID'] . "'>
                <input type='hidden' name='sentFrom' value='$userID'>
                <input type='hidden' name='sentTo' value='" . $application['instructorID'] . "'>
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
    $counter++;
    echo "</div></div></div><br>";
}

echo "</div>";

require_once '_footer.php';