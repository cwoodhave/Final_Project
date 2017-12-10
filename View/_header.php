<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/4/2017
 * Time: 8:20 AM
 */

if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'] && isset($_SESSION['login_user_isAdmin']) )){

    $user = $_SESSION['login_user'];
    $isAdmin = $_SESSION['login_user_isAdmin'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Project</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #a47cb7">
<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header" style="max-height: 50px;">
            <img src="http://icarus.cs.weber.edu/~cw11649/CS3620/Final_Project/Images/wsu_horiz2.png" alt="WSU Logo" align="left" style="padding: 5px 5px 5px 0px; max-height: 50px; max-width: 100%;"/>
            <a class="navbar-brand" href="http://icarus.cs.weber.edu/~cw11649/CS3620/Final_Project/" style="padding-left: 2em;">CS 4800/4890 Applications</a>
        </div>
        <ul class="nav navbar-nav navbar-right">

            <?php
                if(isset($user) && !empty($user)){
                    if($isAdmin){
                        echo "<li><a href=''>Review Applications</a></li>
                                <li><a href=''>Admin Stuff</a></li>";
                    }
                    else {
                        echo "<li><a href='http://icarus.cs.weber.edu/~cw11649/CS3620/Final_Project/View/applications_main.php'>Apply</a></li>
                                <li><a href=''> My Applications</a></li>";
                    }

                    echo "<li><a href='http://icarus.cs.weber.edu/~cw11649/CS3620/Final_Project/View/profile.php'>$user</a></li>
                            <li><a href='http://icarus.cs.weber.edu/~cw11649/CS3620/Final_Project/Utility/logout.php'>Logout</a></li>";
                }
                else{
                    echo "<li><a href='http://icarus.cs.weber.edu/~cw11649/CS3620/Final_Project/View/login.php'>Login</a></li>";
                }
            ?>

        </ul>
    </div>
</div>

<div class="container img-rounded" style="background-color: whitesmoke; padding: 2em;">
    <div class="col-sm-8 col-sm-offset-2 text-center">
