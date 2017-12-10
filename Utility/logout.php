<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 3:56 PM
 */

session_start();

$_SESSION['login_user'] = null;
$_SESSION['login_user_isAdmin'] = null;

header("Location: ../index.php");