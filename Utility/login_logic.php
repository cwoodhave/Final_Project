<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/6/2017
 * Time: 8:30 AM
 */

use Model\Users as Users;

require_once(dirname(__FILE__) . '/../Model/Users.php');


if(session_status() == PHP_SESSION_NONE){
    session_start();
}
$username = '';
$password = '';

//Check if POST is a submit
if (isset($_POST['submit'])) {
    //Verify fields are not empty and that they exist
    if (empty($_POST['username']) || !isset($_POST['username'])
        || empty($_POST['password']) || !isset($_POST['password']))
    {
        $error[] = "Username or Password is invalid";
    }
    else
    {
        // Define $username and $password and database handle
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);


        $username = filter_var($username, FILTER_SANITIZE_STRING);

        if(Users::UserExists($username)){

            if(Users::VerifyPassword($username, $password))
            {
                $user = new Users($username);
                $_SESSION['login_user'] = $username; // Initializing Session
                $_SESSION['login_user_isAdmin'] = $user->getisAdmin();
                header("location: http://icarus.cs.weber.edu/~cw11649/CS3620/Final_Project/View/profile.php"); // Redirecting To Other Page
            }
            else
            {
                $error[] = "Username or Password is invalid";
            }

        } else {
            $error[] = "Username or Password is invalid";
        }

    }
}
