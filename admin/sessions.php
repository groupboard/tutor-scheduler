<?php
require_once("../db.php");
require_once("../config.php");
if ($_SESSION['username'] == '' || $_SESSION['user_type'] != 'A')
{
    header('Location: ../index.php');
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Session Management</title>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
</head>
<body>
<?php
$action = $_REQUEST['action'];
if ($action == 'new')
{
    $session_id = $_REQUEST['session_id'];
    if ($session_id != '')
    {
        if (!preg_match('/^\d+$/',$session_id))
        {
            die("invalid session id '$session_id'");
        }
        $query = "select * from session where session_id=$session_id";
        $result = mysqli_query($mysql_link, $query);
        if ($result && mysqli_num_rows($result))
        {
            while ($row = mysqli_fetch_assoc($result)) 
            {
                $title = $row['title'];
                $start_time = $row['scheduled_start_time'];
                $length_minutes = $row['length_minutes'];
                $teacher = $row['teacher'];
                $student = $row['student'];
                $student_login_time = $row['student_login_time'];
                $student_logout_time = $row['student_logout_time'];
                $teacher_login_time = $row['teacher_login_time'];
                $teacher_logout_time = $row['teacher_logout_time'];
            }
        }
        else
        {
            die("error getting session details for session $session_id");
        }
    }
    print "<form><table>";
    print "<input type=hidden name=action value=add><br>";
    print "<input type=hidden name=session_id value=\"$session_id\"><br>";
    print "<tr><td>Title: </td><td><input required type=text name=title value=\"$title\"><br></td></tr>";
    print "<tr><td>Start time ($tz): </td><td><input id=start_time required type=text name=start_time value=\"$start_time\"><br></td></tr>";
    print '<script type="text/javascript">$("#start_time").datetimepicker({timpicker:true, timepickerOptions:{hours:true,minutes:true,seconds:false,ampm:false}});</script>';
    print "<tr><td>Session length (minutes): </td><td><input required type=text name=length_minutes value=\"$length_minutes\"><br></td></tr>";
    print "<tr><td>Student: </td><td><select name=\"student\">";
    include("students.php");
    print "</select><br></td></tr>";
    print "<tr><td>Teacher: </td><td><select name=\"teacher\">";
    include("teachers.php");
    print "</select><br></td></tr>";
    print "<tr><td>Student login time ($tz):</td><td>$student_login_time</td></tr>";
    print "<tr><td>Student logout time ($tz):</td><td>$student_logout_time</td></tr>";
    print "<tr><td>Teacher login time ($tz):</td><td>$teacher_login_time</td></tr>";
    print "<tr><td>Teacher logout time ($tz):</td><td>$teacher_logout_time</td></tr>";
    print "</table><br><input type=submit value=Submit></form>";
}
else if ($action == 'add')
{
    $session_id = $_REQUEST['session_id'];
    if ($session_id != '')
    {
        if (!preg_match('/^\d+$/',$session_id))
        {
            die("invalid session id");
        }
    }
    $title = $_REQUEST['title'];
    $start_time = $_REQUEST['start_time'];
    $length_minutes = $_REQUEST['length_minutes'];
    $student = $_REQUEST['student'];
    if (!preg_match('/^\d+$/',$student))
    {
        die("invalid student id");
    }
    $teacher = $_REQUEST['teacher'];
    if (!preg_match('/^\d+$/',$teacher))
    {
        die("invalid teacher id");
    }
    if ($session_id == '')
    {
        $query = sprintf("insert into session values(null, '%s', '%s', %d, $teacher, $student, null, null, null, null, null)",
            mysqli_real_escape_string($mysql_link, $title),
            mysqli_real_escape_string($mysql_link, $start_time),
            mysqli_real_escape_string($mysql_link, $length_minutes)
            );
    }
    else
    {
        $query = sprintf("update session set title='%s', scheduled_start_time='%s', student=$student, teacher=$teacher, length_minutes=%d where session_id=$session_id",
            mysqli_real_escape_string($mysql_link, $title),
            mysqli_real_escape_string($mysql_link, $start_time),
            mysqli_real_escape_string($mysql_link, $length_minutes)
            );

    }

    $result = mysqli_query($mysql_link, $query);
    if ($result)
    {
        print "<p>Session added successfully</p>";
        header('Location: sessions.php');
    }
    else
    {
        print "<p>Error adding session: ".mysqli_error($mysql_link)."</p>";
    }
}
else if ($action == 'del')
{
    $session_id = $_REQUEST['session_id'];
    if ($session_id == '')
    {
        die("no session id specified");
    }
    if (!preg_match('/^\d+$/',$session_id))
    {
        die("invalid session id");
    }
    $query = "delete from session where session_id=$session_id";
    $result = mysqli_query($mysql_link, $query);
    if ($result)
    {
        print "<p>Session deleted successfully</p>";
    }
    else
    {
        print "<p>Error deleting session: ".mysqli_error($mysql_link)."</p>";
    }
    header('Location: sessions.php');
}
else
{
    print "<table><tr>";
    print "<th>Sessionid</th><th>Title</th><th>Start time ($tz)</th><th>Session length (mins)</th><th>Actual start time ($tz)</th><th>Teacher</th><th>Student</th><th>Teacher login time ($tz)</th><th>Teacher logout time ($tz)</th><th>Student login time ($tz)</th><th>Student logout time ($tz)</th><th>DELETE</th><th>EDIT</th></tr>";
    print "</tr>";
    $query = "select * from session";
    $result = mysqli_query($mysql_link, $query);
    if ($result && mysqli_num_rows($result))
    {
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $session_id = $row['session_id'];
            $title = $row['title'];
            $start_time = $row['scheduled_start_time'];
            $actual_start_time = $row['actual_start_time'];
            $length_minutes = $row['length_minutes'];
            $teacher = $row['teacher'];
            $student = $row['student'];
            $student_login_time = $row['student_login_time'];
            $student_logout_time = $row['student_logout_time'];
            $teacher_login_time = $row['teacher_login_time'];
            $teacher_logout_time = $row['teacher_logout_time'];

            if (preg_match('/^\d+$/',$student))
            {
                $q2 = "select username from user where user_id=$student";
                $result2 = mysqli_query($mysql_link, $q2);
                if ($result2 && mysqli_num_rows($result2))
                {
                    if ($row2 = mysqli_fetch_row($result2))
                    {
                        $student_name = $row2[0];
                    }
                }
            }
            if (preg_match('/^\d+$/',$teacher))
            {
                $q2 = "select username from user where user_id=$teacher";
                $result2 = mysqli_query($mysql_link, $q2);
                if ($result2 && mysqli_num_rows($result2))
                {
                    if ($row2 = mysqli_fetch_row($result2))
                    {
                        $teacher_name = $row2[0];
                    }
                }
            }


            print "<tr><td>$session_id</td><td>$title</td><td>$start_time</td><td>$length_minutes</td><td>$actual_start_time</td><td>$teacher_name</td><td>$student_name</td><td>$teacher_login_time</td><td>$teacher_logout_time</td><td>$student_login_time</td><td>$student_logout_time</td><td><a href=\"?action=del&session_id=$session_id\">Delete Session</a></td><td><a href=\"?action=new&session_id=$session_id\">Edit Session</a></td></tr>";
        }
    }
    print "</table>";
    print '<p><a href="?action=new">Add Session</a><p>';
}
?>
<p>
<a href="index.php">Admin Home</a>
</body>
</html>
