<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/12/2017
 * Time: 9:06 AM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === ''){
    header("Location: ../index.php");
}

require_once '_header.php';

echo "<h2>Notifications</h2>";

require_once '_footer.php';