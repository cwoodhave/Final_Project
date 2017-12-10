<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 3:52 PM
 */

require_once "../Model/Users.php";
session_start();

//Check if server request is post  and if the fields have been competed
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    require_once '../Utility/login_logic.php';
}

require_once '_header.php';

echo "<h4>Application process for supplemental course credits</h4>
        <p>Please login in or create a new account to begin</p>
        <form method='POST'>
        
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='username'>Username: </label>
                <input class='col-sm-6' type='text' name='username' id='username'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='password'>Password:</label>
                <input class='col-sm-6' type='password' name='password' id='password'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <input class='btn btn-primary' type='submit' name='submit' value='Sign In' />
            </div> 
        </form><br/>
        <a href='createAccount.php'>Create Account</a>";

if(isset($error) && !empty($error) && is_array($error)){
    foreach ($error as $property => $value)
    {
        echo "<br/><span style='color: red'>$value</span>";
    }
}
//if(isset($error) && $error !== ''){
//    echo "<br/><span style='color: red'>$error</span>";
//}

require_once '_footer.php';