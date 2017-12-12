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

echo "<h2>Submitted Applications</h2><br>
        <div class='panel-group'>";

$counter = 1;
foreach ($applications as $application)
{
    echo "<div class='panel panel-default'>
            <div class='panel-heading'>
                <h4 class='panel-title' data-toggle='collapse'   href='#collapse$counter'>"
                . $application['classNumber'] . ": ". $application['courseSemester'] . " " . $application['courseYear'] . "</h4>
            </div>
            
            <div id='collapse$counter' class='panel-collapse collapse' 
            <div class='panel-body' >";

    foreach ($application['responses'] as $response)
    {
        if($response['questionID'] !== 'name' && $response['questionID'] !== 'w_number' && $response['questionID'] !== 'credits' && $response['questionID'] !== 'hours')
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
                <p class='col-sm-10 text-left' name='" . $response['questionID'] . "' id='" . $response['questionID'] . "'>" . $response['responseText'] . "</p>
            </div>";
        }
    }
    $counter++;
    echo "</div></div></div><br>";
}
echo "</div>";





require_once '_footer.php';