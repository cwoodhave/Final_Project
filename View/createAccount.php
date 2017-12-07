<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/6/2017
 * Time: 7:58 AM
 */

require_once '_header.php';

echo "<h3>Create User Account</h3>
        <form method='post'>
        <label for='username'>Username: </label><input type='text' name='username' id='username'/><br/>
        <label for='password'>Password: </label><input type='password' name='password' id='password'/><br/>
        <label for='confirm'>Confirm Password: </label><input type='password' name='confirm' id='confirm'><br/>
        <label for='first'>First Name: </label><input type='text' name='first' id='first'><br/>
        <label for='last'>Last Name: </label><input type='text' name='last' id='last'><br/>
        <label for='email'>Email: </label><input type='email' name='email' id='email'><br/>
        <input type='submit' value='Create Account'/> 
        </form>";

require_once '_footer.php';