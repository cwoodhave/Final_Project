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

require_once '_header.php';

echo "<h2>Student Applications</h2>";

require_once '_footer.php';