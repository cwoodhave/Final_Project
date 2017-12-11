<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 6:07 PM
 */

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === ''){
    header("Location: ../index.php");
}

use Model\Courses as Courses;
require_once '../Model/Courses.php';

$activeCourses = Courses::getActiveCourses('CS4890');

require_once '_header.php';

echo "<h2>CS 4890 - Cooperative Work Experience</h2>
        <h4>Employment Qualifications Guide</h4>
        <p style='text-align: left'>CS 4890 is designed to allow students to receive upper division CS credit for their
            employment during a semester. This course requires that the student perform work at a job
            which compliments the Computer Science curriculum. This means that to be considered for
            this course the student should be spending significant time doing programming or advanced
            database management. This is an upper division course and as such requires professional
            expertise equivalent to an upper-division computer science student. Some examples of
            work that will NOT be accepted are: system or network administration, technical support,
            help-desk, PC building/repair, working with spreadsheets, data entry, sales positions, or any
            technical tasks which are simple and repetitive. A student desiring this class must
            communicate with the assigned instructor for the semester during which the student will be
            working if the student has questions regarding this policy, or if a given position qualifies for
            the class. The work must occur during the semester for which the student is receiving
            credit. A student must register for the class prior to the registration deadline and must
            receive instructor approval prior to registering.</p>
            <p>Credit is awarded according to the following schedule:</p>
            <p>1 Credit hour = 5 hours per week</p>
            <p>2 Credit hour = 10 hours per week</p>
            <p>3 Credit hour = 15 hours per week</p>
            <p>4 Credit hour = 20 hours per week</p>
            
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
                <label class='col-sm-4 text-right' for='description'>Job description:<br>Tell me about what you do for a living, and how it relates to computer science. 
                It doesn’t need to be a formal HR description, but it should demonstrate the relationship. 
                This information will allow me to determine if your position justifies upper division CS credit.</label>
                <textarea class='col-sm-8' name='description' id='description' rows='10' ></textarea><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='credits'>How many hours per week do you work? (Determines the number of credits.) </label>
                <input class='col-sm-8' type='text' name='credits' id='credits'/><br/>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <label class='col-sm-4 text-right' for='course'>Available Courses: </label>
                <select class='col-sm-8' name='courseID' id='courseID'>
                    <option value=''></option>";

                foreach ($activeCourses as $course){
                    echo "<option value='" . $course['courseID'] . "'> " . $course['courseSemester'] . " " . $course['courseYear'] . ", Instructor: "
                        . $course['instructorFirstname'] . " " . $course['instructorLastname'] . "</option>";
                }

echo        "</select>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <br><p style='font-weight: bold'>Note: You need to speak with your boss and make sure they are willing to fill out an employee evaluation (I’ll provide the form) at the end of the semester.</p>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <p style='font-weight: bold'>You must also have taken CS 2420 prior to being able to enroll in this course</p>
            </div>
            <div class='row' style='margin-top: 5px;'>
                <input class='btn btn-primary' type='submit' name='submit' value='Submit Application'/><br/>
            </div>
        </form>";

require_once '_footer.php';