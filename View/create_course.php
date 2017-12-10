<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 6:08 PM
 */

use Model\Users as Users;

require_once '../Model/Users.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' || !isset($_SESSION['login_user_isAdmin']) || !$_SESSION['login_user_isAdmin']){
    header("Location: ../index.php");
}

$instructors = Users::GetInstructors();

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (isset($_POST['submit'])) {
        $ok = true;
        //Verify fields are not empty and that they exist
        if (empty($_POST['username']) || !isset($_POST['username']) || Users::UserExists($_POST['username'])){
            $ok = false;
            $error[] = "Username is invalid or already exists";
        } else {
            $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['password']) || !isset($_POST['password']) || !preg_match('@[A-Z]@', $_POST['password'])
            || !preg_match('@[a-z]@', $_POST['password']) || !preg_match('@[0-9]@', $_POST['password'])
            || strlen($_POST['password']) < 8 || $_POST['password'] != $_POST['confirm']
        ){
            $ok = false;
            $error[] = "Password is invalid or does not match.";
        } else {
            $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        }
        if (empty($_POST['first']) || !isset($_POST['first'])){
            $ok = false;
            $error[] = "Please fill out your first name";
        } else {
            $first = filter_var(trim($_POST['first']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['last']) || !isset($_POST['last'])){
            $ok = false;
            $error[] = "Please fill out your last name";
        } else {
            $last = filter_var(trim($_POST['last']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['email']) || !isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $ok = false;
            $error[] = "Email is invalid";
        } else {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        }

        if($ok)
        {
            $newUser = new Users();
            $newUser->saveUser($username, $password, $first, $last, $email);
            require_once '../Utility/login_logic.php';
        }
    }
}

require_once '_header.php';

echo "<h3>Create New Course</h3>
        <form method='post'>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='classNumber' >Class Number: </label>
            <select class='col-sm-5' name='classNumber' id='classNumber'>
                <option value=''></option>
                <option value='CS4800'>CS 4800</option>
                <option value='CS4890'>CS 4890</option>
            </select>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='courseYear'>Course Year: </label>
            <input class='col-sm-5' type='number' maxlength='4' minlength='4'  name='courseYear' id='courseYear' /><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='courseSemester'>Course Semester: </label>
            <select class='col-sm-5' name='courseSemester' id='courseSemester'>
                <option value=''></option>
                <option value='FALL'>Fall</option>
                <option value='SPRING'>Spring</option>
                <option value='SUMMER'>Summer</option>
            </select>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='instructorID'>Instructor: </label>
            <select class='col-sm-5' name='instructorID' id='instructorID'>
                <option value=''></option>";

                foreach ($instructors as $instructor){
                    echo "<option value='" . $instructor['userID'] . "'>" . $instructor['firstname'] . " " . $instructor['lastname'] . "</option>";
                }

echo        "</select>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='openDate'>Opening Date: </label>
            <input class='col-sm-5' type='datetime-local' name='openDate' id='openDate' value='' readonly >         
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='closeDate'>Closing Date: </label>
            <input class='col-sm-5' type='datetime-local' name='closeDate' id='closeDate' value='' readonly><br/>           
        </div> 
        <div class='row' style='margin-top: 5px;'>
            <input class='btn btn-success' type='submit' name='submit' value='Create Course'/> 
        </div>
       </form><br>";

//var_dump($instructors);

require_once  '_footer.php';