<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 9:38 PM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' || !isset($_SESSION['login_user_isAdmin']) || !$_SESSION['login_user_isAdmin']){
    header("Location: ../index.php");
}

require_once '_header.php';

echo "<h2>Courses Main Page</h2>
        <div class='col-sm-6 text-left'>
            <h4>Active Courses</h4>
            
        </div>
        <div class='col-sm-6 text-left'>
            <h4>Inactive Courses</h4>
            
        </div>
        <a href='create_course.php'><input type='button' value='Create New Course'></a>";

require_once '_footer.php';