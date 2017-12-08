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

    $newUser = new Users();
    $username;
    $password;
    $confirm;
    $first;
    $last;
    $email;


    if (isset($_POST['submit'])) {
        $ok = true;
        //Verify fields are not empty and that they exist
        if (empty($_POST['username']) || !isset($_POST['username']) || Users::UserExists($_POST['username'])){
            $ok = false;
            $error[] = "Username is invalid or already exists";
        } else {
            $username = $_POST['username'];
        }
        if (empty($_POST['password']) || !isset($_POST['password']) || !preg_match('@[A-Z]@', $_POST['password'])
            || !preg_match('@[a-z]@', $_POST['password']) || !preg_match('@[0-9]@', $_POST['password'])
            || strlen($_POST['password']) < 8 || $_POST['password'] != $_POST['confirm']
        ){
            $ok = false;
            $error[] = "Password is invalid or does not match.";
        } else {
            $password = $_POST['password'];
        }
        if (empty($_POST['first']) || !isset($_POST['first'])){
            $ok = false;
            $error[] = "Please fill out your first name";
        } else {
            $first = $_POST['first'];
        }
        if (empty($_POST['last']) || !isset($_POST['last'])){
            $ok = false;
            $error[] = "Please fill out your last name";
        } else {
            $last = $_POST['last'];
        }
        if (empty($_POST['email']) || !isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $ok = false;
            $error[] = "Email is invalid";
        } else {
            $email = $_POST['email'];
        }

        if($ok)
        {
            $newUser->saveUser($username, $password, $first, $last, $email);
            require_once '../login.php';
        }
    }
}

require_once '_header.php';

echo "<h3>Create User Account</h3>
        <form method='post'>
        <label for='username'>Username: </label><input type='text' name='username' id='username' value='$username'/><br/>
        <span>Password must be at least 8 characters long and contain at least 1 number, 1 upper case character, 
        and 1 lower case character</span><br/>
        <label for='password'>Password: </label><input type='password' name='password' id='password' /><br/>
        <label for='confirm'>Confirm Password: </label><input type='password' name='confirm' id='confirm'><br/>
        <label for='first'>First Name: </label><input type='text' name='first' id='first' value='$first'><br/>
        <label for='last'>Last Name: </label><input type='text' name='last' id='last' value='$last'><br/>
        <label for='email'>Email: </label><input type='email' name='email' id='email' value='$email'><br/>
        <input type='submit' name='submit' value='Create Account'/> 
        </form><br>";

if(isset($error) && !empty($error) && is_array($error)){
    foreach ($error as $property => $value)
    {
        echo "<br/><span style='color: red'>$value</span>";
    }
}

require_once '_footer.php';