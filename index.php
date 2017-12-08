<?php

use Utility\DatabaseConnection as DatabaseConnection;
use Model\Users;

//Check if server request is post  and if the fields have been competed
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    require_once 'login.php';
}

require_once  'View/_header.php';

echo "<h4>Welcome to the application process for supplemental course credits</h4>
        <p>Please login in or create a new account to begin</p>
        <form method='POST'>
            <label for='username'>Username: </label><input type='text' name='username' id='username'/><br/>
            <label for='password'>Password:</label><input type='password' name='password' id='password'/><br/>
            <input class='btn btn-primary' type='submit' name='submit' value='Sign In' />
        </form><br/>
        <a href='View/createAccount.php'>Create Account</a>";

if(isset($error) && $error !== ''){
    echo "<br/><span style='color: red'>$error</span>";
}

require_once 'View/_footer.php';