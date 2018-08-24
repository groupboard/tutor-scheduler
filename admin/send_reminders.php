<?php

// Send reminders to all users who have an upcoming session

include("../db.php");
include("../config.php");

$from_email = "ignore\@groupboard.com"; // the From address for emails
$subject = "Tutoring session reminder";
$room_url = $groupworld_server."/tutor_scheduler/room.php?session_id=";

# Reminder time in minutes. Set to 0 to turn off that particular reminder.
$reminder1_time = 1440; // 1 day
$reminder2_time = 60;   // 1 hour

$query = "select session_id,title,date_format(scheduled_start_time, '$date_format'),length_minutes,student,teacher,timestampdiff(minute, current_timestamp, scheduled_start_time) as mins_to_session,reminder1_sent,reminder2_sent from session where student_login_time is null and teacher_login_time is null and (reminder1_sent != 'y' and timestampdiff(minute, current_timestamp, scheduled_start_time) < $reminder1_time) or (reminder2_sent != 'y' and timestampdiff(minute, current_timestamp, scheduled_start_time) < $reminder2_time)";

$result = mysqli_query($mysql_link, $query);
if ($result && mysqli_num_rows($result))
{
    while ($row = mysqli_fetch_row($result)) 
    {
        $session_id = $row[0];
        $title = $row[1];
        $start_time = $row[2];
        $length_minutes = $row[3];
        $student = $row[4];
        $teacher = $row[5];
        $mins_in_future = $row[6];
        $reminder1_sent = $row[7];
        $reminder2_sent = $row[8];

        $student_email = get_user_email($student);
        $teacher_email = get_user_email($teacher);

        $student_name = get_user_name($student);
        $teacher_name = get_user_name($teacher);

        $student_body = "You have a tutoring session at $start_time with $teacher_name\n\nClick here to log into the session (up to $early_login_mins minutes before the session starts):\n\n$room_url$session_id\n\n";
        $teacher_body = "You have a tutoring session at $start_time with $student_name\n\nClick here to log into the session (up to $early_login_mins minutes before the session starts):\n\n$room_url$session_id\n\n";

        if ($reminder1_sent != 'y' && $mins_in_future < $reminder1_time && $reminder1_time != 0)
        {

            mail($student_email, $subject, $student_body, "From: $from_email");
            mail($teacher_email, $subject, $teacher_body, "From: $from_email");

            $query = "update session set reminder1_sent='y' where session_id=$session_id";
            $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));
        }
        else if ($reminder2_sent != 'y' && $mins_in_future < $reminder2_time && $reminder2_time != 0)
        {
            mail($student_email, $subject, $student_body, "From: $from_email");
            mail($teacher_email, $subject, $teacher_body, "From: $from_email");

            $query = "update session set reminder2_sent='y' where session_id=$session_id";
            $result = mysqli_query($mysql_link, $query) or die(mysqli_error($mysql_link));
        }
    }
}

function get_user_name($userid)
{
    global $mysql_link;
    $query = "select firstname,surname from user where user_id=$userid";
    $result = mysqli_query($mysql_link, $query);
    if ($result && mysqli_num_rows($result))
    {
        if ($row = mysqli_fetch_row($result)) 
        {
            $firstname = $row[0];
            $surname = $row[1];
            return $firstname.' '.$surname;
        }
    }

    return "";
}

function get_user_email($userid)
{
    global $mysql_link;
    $query = "select email from user where user_id=$userid";
    $result = mysqli_query($mysql_link, $query);
    if ($result && mysqli_num_rows($result))
    {
        if ($row = mysqli_fetch_row($result)) 
        {
            $email = $row[0];
            return $email;
        }
    }

    return "";
}
?>
