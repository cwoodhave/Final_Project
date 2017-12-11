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

echo "<h2>Applications</h2>";

echo var_dump($applications);



require_once '_footer.php';