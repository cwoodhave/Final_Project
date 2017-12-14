<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/13/2017
 * Time: 7:01 PM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' || !isset($_SESSION['login_user_isAdmin']) || !$_SESSION['login_user_isAdmin']){
    header("Location: ../index.php");
}

use Model\Applications as Applications;
use Model\Courses as Courses;

require_once '../Model/Applications.php';
require_once '../Model/Courses.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $courseID = "";

    if(isset($_POST['courseID']) && !empty($_POST['courseID']) && is_numeric($_POST['courseID']))
    {
        $courseID = filter_var($_POST['courseID'], FILTER_SANITIZE_NUMBER_INT);

        if(Applications::ApplicationsExistsForCourse($courseID))
        {
            echo 'Can\'t delete course.  Applications have already been submitted.';
        }
        else
        {
            $course = new Courses($courseID);
            $course->deleteCourse();
            $course = null;

            echo "Course has been deleted. CourseID = " . $courseID;
        }

    }
    else
    {
        echo "Could not find course";
    }
}
