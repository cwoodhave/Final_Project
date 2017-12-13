<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 12/9/2017
 * Time: 9:38 PM
 */

use Model\Courses as Courses;

require_once '../Model/Courses.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] === '' || !isset($_SESSION['login_user_isAdmin']) || !$_SESSION['login_user_isAdmin']){
    header("Location: ../index.php");
}

$activeCourses = Courses::getActiveCourses();
$futureCourses = Courses::getFutureCourse();
$oldCourses = Courses::getPreviousCourses();

require_once '_header.php';

echo "<h2>Courses Main Page</h2>
        <div class='row'><br><a href='create_course.php'><input class='btn btn-primary' type='button' value='Create New Course'></a></div><br>
        <div class='row'>
            <h4>Active Courses</h4>
            <table class='table table-bordered table-striped' id='active'>
                <thead>
                    <tr>
                        <th class='text-center' scope='col'>Class Number</th>
                        <th class='text-center' scope='col'>Year</th>
                        <th class='text-center' scope='col'>Semester</th>
                        <th class='text-center' scope='col'>Open Date</th>
                        <th class='text-center' scope='col'>Close Date</th>
                        <th class='text-center' scope='col'>Instructor Name</th>
                    </tr>
                </thead>
                <tbody>";

                foreach ($activeCourses as $course){
                    $open = date_create($course['openDate']);
                    $close = date_create($course['closeDate']);
                    echo "<tr>
                            <td>" . $course['classNumber'] . "</td>
                            <td>" . $course['courseYear'] . "</td>
                            <td>" . $course['courseSemester'] . "</td>
                            <td>" . date_format($open,'F j, Y, g:i a' ) . "</td>
                            <td>" . date_format($close, 'F j, Y, g:i a') . "</td>
                            <td>" . $course['instructorFirstname'] . " " . $course['instructorLastname'] . "</td>
                        </tr>";
                }

  echo          "</tbody>
            </table>
        </div><br>
        <div class='row'>
            <h4>Future Courses</h4>
            <table class='table table-striped table-bordered' id='future'>
                <thead>
                    <tr>
                        <th class='text-center' scope='col'>Class Number</th>
                        <th class='text-center' scope='col'>Year</th>
                        <th class='text-center' scope='col'>Semester</th>
                        <th class='text-center' scope='col'>Open Date</th>
                        <th class='text-center' scope='col'>Close Date</th>
                        <th class='text-center' scope='col'>Instructor Name</th>
                    </tr>
                </thead>
                <tbody>";

                foreach ($futureCourses as $course){
                    $open = date_create($course['openDate']);
                    $close = date_create($course['closeDate']);
                    echo "<tr>
                            <td>" . $course['classNumber'] . "</td>
                            <td>" . $course['courseYear'] . "</td>
                            <td>" . $course['courseSemester'] . "</td>
                            <td>" . date_format($open,'F j, Y, g:i a' ) . "</td>
                            <td>" . date_format($close, 'F j, Y, g:i a') . "</td>
                            <td>" . $course['instructorFirstname'] . " " . $course['instructorLastname'] . "</td>
                        </tr>";
                }

  echo          "</tbody>
            </table>
            
        </div><br>
         <div class='row'>
            <h4>Previous Courses</h4>
            <table class='table table-striped table-bordered' id='previous'>
                <thead>
                    <tr>
                        <th class='text-center' scope='col'>Class Number</th>
                        <th class='text-center' scope='col'>Year</th>
                        <th class='text-center' scope='col'>Semester</th>
                        <th class='text-center' scope='col'>Open Date</th>
                        <th class='text-center' scope='col'>Close Date</th>
                        <th class='text-center' scope='col'>Instructor Name</th>
                    </tr>
                </thead>
                <tbody>";

                foreach ($oldCourses as $course){
                    $open = date_create($course['openDate']);
                    $close = date_create($course['closeDate']);
                    echo "<tr>
                            <td>" . $course['classNumber'] . "</td>
                            <td>" . $course['courseYear'] . "</td>
                            <td>" . $course['courseSemester'] . "</td>
                            <td>" . date_format($open,'F j, Y, g:i a' ) . "</td>
                            <td>" . date_format($close, 'F j, Y, g:i a') . "</td>
                            <td>" . $course['instructorFirstname'] . " " . $course['instructorLastname'] . "</td>
                        </tr>";
                }

  echo          "</tbody>
            </table>
            
        </div><br>";

?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $("#active").DataTable();
        $('#future').DataTable();
        $('#previous').DataTable();
    })
</script>

<?php

require_once '_footer.php';