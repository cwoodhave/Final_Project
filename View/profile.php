<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/6/2017
 * Time: 8:58 AM
 */

use Model\Users as Users;

require_once "../Model/Users.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' ){
    header("Location: ../index.php");
}

$username = $_SESSION['login_user'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $user = new Users($username);

    if (isset($_POST['update_profile'])) {
        $newUsername = "";
        $newFirst = "";
        $newLast = "";
        $newEmail = "";
        $verify = "";
        $ok = true;

        //Verify fields are not empty and that they exist
        if (empty($_POST['username']) || !isset($_POST['username']) || Users::UserExists($_POST['username'], $user->getUserID())){
            $ok = false;
            $error[] = "Username is invalid or already exists";
        } else {
            $newUsername = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['first']) || !isset($_POST['first'])){
            $ok = false;
            $error[] = "Please fill out your first name";
        } else {
            $newFirst = filter_var(trim($_POST['first']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['last']) || !isset($_POST['last'])){
            $ok = false;
            $error[] = "Please fill out your last name";
        } else {
            $newLast = filter_var(trim($_POST['last']), FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['email']) || !isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $ok = false;
            $error[] = "Email is invalid";
        } else {
            $newEmail = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        }
        if (empty($_POST['verify']) || !isset($_POST['verify']) || !Users::VerifyPassword($username, $_POST['verify'])){
            $ok = false;
            $error[] = "Please verify your password";
        }
        else{
            $new_password = password_hash(trim($_POST['verify']), PASSWORD_DEFAULT);
        }

        if($ok)
        {
            $user->saveUser($newUsername, $verify , $newFirst, $newLast, $newEmail);
            $_SESSION['login_user'] = $newUsername;
            header("location: profile.php");
        }

    }
    elseif(isset($_POST['update_password']))
    {
        $old_password = "";
        $new_password = "";
        $ok = true;
        if (empty($_POST['old_password']) || !isset($_POST['old_password']) || !Users::VerifyPassword($username, $_POST['old_password'])){
            $ok = false;
            $error[] = "Password is invalid or does not match.";
        }
        else{
            $old_password = $_POST['old_password'];
        }
        if (empty($_POST['new_password']) || !isset($_POST['new_password']) || !preg_match('@[A-Z]@', $_POST['new_password'])
            || !preg_match('@[a-z]@', $_POST['new_password']) || !preg_match('@[0-9]@', $_POST['new_password'])
            || strlen($_POST['new_password']) < 8 || $_POST['new_password'] != $_POST['confirm']
        ){
            $ok = false;
            $error[] = "Password is invalid or does not match.";
        } else {
            $new_password = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);
        }

        if($ok)
        {
            $user->updatePassword($old_password, $new_password);
            header("location: profile.php");
        }
    }
}


if(!isset($user) || empty($user)){
    $user = new Users($username);
}

$isAdmin = $_SESSION['login_user_isAdmin'];

$first = $user->getFirstname();
$last = $user->getLastname();
$email = $user->getEmail();


require_once '_header.php';

echo "<h2>Hello $username</h2>
        <h3>Update Your Profile</h3>
        <form method='post'>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='username' >Username: </label>
            <input class='col-sm-5' type='text' name='username' id='username' value='$username'/><br/>
        </div>        
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='first'>First Name: </label>
            <input class='col-sm-5' type='text' name='first' id='first' value='$first'><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='last'>Last Name: </label>
            <input class='col-sm-5' type='text' name='last' id='last' value='$last'><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='email'>Email: </label>
            <input class='col-sm-5' type='email' name='email' id='email' value='$email'><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='verify'>Verify Password: </label>
            <input class='col-sm-5' type='password' name='verify' id='verify' /><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <input class='btn btn-success' type='submit' name='update_profile' value='Update User Info'/> 
        </div>
       </form><br>
       
       <form method='POST'>
       <h3>Change Password</h3>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='old_password'>Old Password: </label>
            <input class='col-sm-5' type='password' name='old_password' id='old_password' /><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='new_password'>New Password*: </label>
            <input class='col-sm-5' type='password' name='new_password' id='new_password' /><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='confirm'>Confirm Password: </label>
            <input class='col-sm-5' type='password' name='confirm' id='confirm'><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <input class='btn btn-success' type='submit' name='update_password' value='Update Password'/> 
        </div>
        </form>
        <span style='font-size: smaller; color: darkblue'>*Password must be: at least 8 characters long and contain at least 1 number, 1 upper case character, 
        and 1 lower case character.</span><br/>";

require_once '_footer.php';