<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 6:05 PM
 */

session_start();

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === ''){
    header("Location: ../index.php");
}

require_once (realpath(dirname(__FILE__)). '/../View/_header.php');

echo "<h2>CS 4800 - Individual Projects and Research</h2>
        <form method='POST'>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='username'>Name: </label>
                <input class='col-sm-6' type='text' name='name' id='name'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-2 col-sm-offset-2 text-right' for='w_number'>W#: </label>
                <input class='col-sm-6' type='text' name='w_number' id='w_number'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='description'>Project description:<br>What does the student intend to do?  Provide detail!</label>
                <textarea class='col-sm-8' name='description' id='description' rows='10' ></textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='justification'>Justification:<br>Why does the student want to do what is proposed and what rationale can be given for why it ought to be done? (Justify upper division CS credit.)</label>
                <textarea class='col-sm-8' name='justification' id='justification' rows='10'></textarea><br/>
            </div>
             <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='method'>Method:<br> How does the student propose to accomplish the task, and how long will it take?</label>
                <textarea class='col-sm-8' name='method' id='method' rows='10'></textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='resources'>Resources:<br> What help, equipment, etc., will the student require to accomplish the task?</label>
                <textarea class='col-sm-8' name='resources' id='resources' rows='10'></textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='credits'>Number of credits you need: </label>
                <input class='col-sm-8' type='text' name='credits' id='credits'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <input class='btn btn-primary' type='submit' name='submit' value='Submit Application'/><br/>
            </div>
        </form>";

require_once (realpath(dirname(__FILE__)). '/../View/_footer.php');