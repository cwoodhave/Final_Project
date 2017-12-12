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
use Model\Users as Users;

require_once '../Model/Applications.php';
require_once '../Model/Users.php';

$user = new Users($_SESSION['login_user']);
$applications = Applications::getApplicationsByUser($user->getUserID());

require_once '_header.php';

echo "<h2>Submitted Applications</h2><br>";

foreach ($applications as $application)
{
    echo "<hr style='border-width: 1px; border-color: #666666;' ><h4>" . $application['classNumber'] . ": ". $application['courseSemester'] . " " . $application['courseYear'] . "</h4>";

    foreach ($application['responses'] as $response)
    {
        if($response['questionID'] !== 'name' && $response['questionID'] !== 'w_number' && $response['questionID'] !== 'credits' && $response['questionID'] !== 'hours')
        {
            echo "<div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 text-right' for='" . $response['questionID'] . "'>" . ucfirst($response['questionID']) . ": </label>
                <textarea class='col-sm-10' name='" . $response['questionID'] . "' id='" . $response['questionID'] . "' readonly>" . $response['responseText'] . "</textarea>
            </div>";
        }
        else
        {
            echo "<div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 text-right' for='" . $response['questionID'] . "'>" . ucfirst($response['questionID']) . ": </label>
                <p class='col-sm-10 text-left' name='" . $response['questionID'] . "' id='" . $response['questionID'] . "'>" . $response['responseText'] . "</p>
            </div>";
        }
    }

    echo "<br>";
}





require_once '_footer.php';