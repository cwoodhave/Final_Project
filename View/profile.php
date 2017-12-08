<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/6/2017
 * Time: 8:58 AM
 */

use Model\Users;

session_start();

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' ){
    header("Location: ../index.php");
}

$username = $_SESSION['login_user']->getUsername();
$isAdmin = $_SESSION['login_user']->getisAdmin();

require_once '_header.php';

echo "<p>Hello $username</p>";

if($isAdmin)
{
    echo "<p>You have admin rights</p>";
}

require_once '_footer.php';