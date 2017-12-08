<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/6/2017
 * Time: 8:30 AM
 */

use Utility\DatabaseConnection as DatabaseConnection;
use Model\Users;

require_once "Utility/DatabaseConnection.php";
require_once "Model/Users.php";

session_start(); // Starting Session
$error=''; // Variable To Store Error Message
$username = '';
$password = '';

//Check if POST is a submit
if (isset($_POST['submit'])) {
    //Verify fields are not empty and that they exist
    if (empty($_POST['username']) || !isset($_POST['username'])
        || empty($_POST['password']) || !isset($_POST['password']))
    {
        $error = "Username or Password is invalid";
    }
    else
    {
        // Define $username and $password and database handle
        $username=$_POST['username'];
        $password=$_POST['password'];

        try{
            $dbh = DatabaseConnection::getInstance();

            //Sanitize/Hash user data and query database
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $password = password_hash($password, PASSWORD_DEFAULT);

            $stmtHndl = $dbh->prepare("SELECT * FROM users WHERE 'username' = :username AND 'password' = :password");
            $stmtHndl->bindParam('username', $username);
            $stmtHndl->bindParam('password', $password);

            $stmtHndl->execute();

            $rows = $stmtHndl->rowCount();

            if ($rows == 1) {
                $_SESSION['login_user']= new Users($username); // Initializing Session
                header("location: View/profile.php"); // Redirecting To Other Page
            } else {
                $error = "Username or Password is invalid";
            }
            $dbh = null;
        }
        catch (\PDOException $ex) {
            $error = "Server Error";
        }
    }
}
