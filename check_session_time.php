<?php
include("db.php");
$session_id = $_REQUEST['session_id'];
$user_id = $_REQUEST['user_id'];
if ($user_id == '')
{
    die("no user_id");
}
if ($session_id == '')
{
    die("no session_id");
}
if(!preg_match('/^\d+$/',$user_id))
{
    die("invalid user_id");
}
if(!preg_match('/^\d+$/',$session_id))
{
    die("invalid session_id");
}

$query = "select student,teacher,student_login_time,teacher_login_time,actual_start_time,timestampdiff(second, actual_start_time, now()),length_minutes from session where session_id=$session_id";

$result = mysqli_query($mysql_link, $query);
if ($result && mysqli_num_rows($result))
{
    if ($row = mysqli_fetch_row($result)) 
    {
        $student = $row[0];
        $teacher = $row[1];
        $student_login_time = $row[2];
        $teacher_login_time = $row[3];
        $actual_start_time = $row[4];
        $seconds_after_start = $row[5];
        $length_minutes = $row[6];
        $seconds_before_end = $length_minutes*60 - $seconds_after_start;
        if ($user_id == $student)
        {
            // update logout time
            $query = "update session set student_logout_time=NOW() where session_id=$session_id";
            $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));
            if ($student_login_time == '')
            {
                // only set login time if not already set
                $query = "update session set student_login_time=NOW() where session_id=$session_id";
                $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));

                if ($actual_start_time == '' && $teacher_login_time != '')
                {
                    // mark session as actually started
                    $query = "update session set actual_start_time=NOW() where session_id=$session_id";
                    $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));
                }
            }
        }
        else if ($user_id == $teacher)
        {
            // update logout time
            $query = "update session set teacher_logout_time=NOW() where session_id=$session_id";
            $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));
            if ($teacher_login_time == '')
            {
                // only set login time if not already set
                $query = "update session set teacher_login_time=NOW() where session_id=$session_id";
                $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));
                if ($actual_start_time == '' && $student_login_time != '')
                {
                    // mark session as actually started
                    $query = "update session set actual_login_time=NOW() where session_id=$session_id";
                    $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));
                }
            }
        }
        else
        {
            print "You are not authorized to log into this session";
            exit;
        }

        if ($actual_start_time != '')
        {
            if ($seconds_after_start < 0 || $seconds_before_end < 0)
            {
                print "Session finished";
                exit;
            }
        }
        print "ok";
        exit;
    }
}
print "session finished";

?>
