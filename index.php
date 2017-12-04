<?php

require_once  'View/_header.php';

echo "<h4>Welcome to the application process for supplemental course credits</h4>
        <p>Please login in or create a new account to begin</p>
        <form method='post'>
            <label for='username'>Username: </label><input type='text' name='username' id='username'/><br/>
            <label for='password'>Password:</label><input type='password' name='password' id='password'/><br/>
            <input class='btn btn-primary' type='submit' value='Sign In' />
        </form>";

require_once 'View/_footer.php';