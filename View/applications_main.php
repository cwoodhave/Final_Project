<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/5/2017
 * Time: 3:32 PM
 */

session_start();

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === ''){
    header("Location: ../index.php");
}


require_once '_header.php';

echo "<h2>Main Applications Page</h2>";

require_once '_footer.php';