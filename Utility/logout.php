<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 3:56 PM
 */

session_start();

unset($_SESSION['login_user']);
unset($_SESSION['login_user_isAdmin']);

session_destroy();

header("Location: ../index.php");