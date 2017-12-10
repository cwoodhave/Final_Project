<?php
/**
 * Created by PhpStorm.
 * User: ncast
 * Date: 12/5/2017
 * Time: 3:32 PM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === ''){
    header("Location: ../index.php");
}


require_once '_header.php';

echo "<h2>Choose an application Application</h2>
        <p>There are two applications for which you can apply.  Review each one's description and apply for the one which best fits your needs.</p>
        <div class='col-sm-6 text-left'>
            <h4>CS 4800 - Individual Projects and Research</h4>
            <p>Credits: (1-4)</p>
            <p>The purpose of this course is to permit Computer Science majors to develop an individual project, program, system, or research paper, with coordination and approval of a faculty mentor.<br><br> 
            The final grade and amount of credit awarded will be determined by the department, depending on the complexity of the upper division work performed.<br> <br>
            Prerequisite: <br>CS 2420. May be repeated 3 times up to 4 credit hours. 
            <br>Note: Only 4 credit hours of CS 4800 or CS 4850 or CS 4890 can apply to a CS degree as an elective course, and only a maximum of 6 hours of CS 4800, 
            CS 4850, and CS 4890 may be taken to satisfy missing credits or to achieve full time academic status.</p>
            <a href='application_4800.php'><input class='btn btn-primary' type='button' value='Apply for 4800'/></a>
        </div>
        <div class='col-sm-6 text-left'>
            <h4>CS 4890 - Cooperative Work Experience</h4>
            <p>Credits: (1-4)</p>
            <p>The purpose of this course is to permit Computer Science majors who are currently working in a computer related job or internship to receive academic credit for their work, 
            with coordination and approval of a faculty mentor and their supervisor. <br><br>
            The amount of upper division credit awarded will be determined by the department, depending on the nature and quantity of work performed. <br><br>
            Prerequisite: <br>CS 2420. May be repeated 3 times up to 4 credit hours. 
            <br>Note: Only 4 credit hours of CS 4800 or CS 4850 or CS 4890 can apply to a CS degree as an elective course, 
            and only a maximum of 6 hours of CS 4800, CS 4850, and CS 4890 may be taken to satisfy missing credits or to achieve full time academic status.</p>
            <a href='application_4890.php'><input class='btn btn-primary' type='button' value='Apply for 4890'/></a>
        </div>";

require_once '_footer.php';