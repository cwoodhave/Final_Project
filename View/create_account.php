<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/6/2017
 * Time: 7:58 AM
 */

use Model\Users as Users;

require_once '../Model/Users.php';

$username = "";
$password = "";
$confirm = "";
$first = "";
$last = "";
$email = "";

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

echo "<h3>Create User Account</h3>
        <form method='post'>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='username' >Username: </label>
            <input class='col-sm-5' type='text' name='username' id='username' value='$username'/><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='password'>Password*: </label>
            <input class='col-sm-5' type='password' name='password' id='password' /><br/>
        </div>
        <div class='row' style='margin-top: 5px;'>
            <label class='col-sm-3 col-sm-offset-2 text-right' for='confirm'>Confirm Password: </label>
            <input class='col-sm-5' type='password' name='confirm' id='confirm'><br/>
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
            <input class='btn btn-success' type='submit' name='submit' value='Create Account'/> 
        </div>
       </form><br>
        <span style='font-size: smaller; color: darkblue'>*Password must be: at least 8 characters long and contain at least 1 number, 1 upper case character, 
        and 1 lower case character.</span><br/>";

require_once  '_footer.php';