<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/6/2017
 * Time: 8:58 AM
 */

session_start();

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === ''){
    header("Location: index.php");
}

$username = $_SESSION['login_user'];

require_once '_header.php';

echo "Hello $username";

require_once '_footer.php';