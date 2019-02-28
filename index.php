<?php
include("config.php");
include("db.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Tutoring Session Login</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<center>
<?php
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
if ($_SESSION['username'] != '')
{
    $username = $_SESSION['username'];
}
$room = "room.php";

if ($username == "")
{
    show_login("");
}
else
{
    if ($_SESSION['username'] == '')
    {
        if (preg_match('/[^\w\.@ ]/', $username) > 0)
        {

            show_login("Invalid username: must only contain letters, digits, spaces, dots, underscores and @");
        }
        else if (preg_match('/[\047\042\134]/', $password) > 0)
        {
            show_login("Invalid password");
        }
        else
        {
            $query = "select user_id,type from user where username='$username' and password='$password'";
            $result = mysqli_query($mysql_link, $query);
            if((!$result) || (! mysqli_num_rows($result)))
            {
               show_login("Incorrect username/password");
            }
            else
            {
                if ($row = mysqli_fetch_row($result)) 
                {
                    $userid = $row[0];
                    $type = $row[1];
                }
                else
                {
                    die("error getting info from db");
                }

                $_SESSION['user_id'] = $userid;
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = $type;
            }
        }
    }
    else
    {
        $type = $_SESSION['user_type'];
        $userid = $_SESSION['user_id'];
    }

    if ($userid != "")
    {
        if ($type == "A")
        {
            // redirect to admin page
            print "<meta http-equiv='Refresh' content='0; url=admin/'>";
            return;
        }


        // List sessions
        print "<p>Sessions due to start within the next 24 hours or the previous 2 hours are listed below. You will be given the option of logging into any sessions starting within the next $early_login_mins minutes, or sessions that have already started.</p>";
        print "<table border=1><tr><th>Title</th><th>Scheduled Time ($tz)</th><th>Session Length (minutes)</th><th>Student</th><th>Teacher</th><th>Login</th></tr>\n";
        if ($type == "T")
        {
            $qual = "teacher=$userid";
        }
        else
        {
            $qual = "student=$userid";
        }
        $query = "select session_id,title,date_format(scheduled_start_time, '$date_format'),length_minutes,student,teacher,timestampdiff(second, current_timestamp, scheduled_start_time) from session where $qual order by scheduled_start_time";

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
                $seconds_in_future = $row[6];
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
                // list all sessions due to start in next 24 hours, or in
                // past 2 hours
                if ($seconds_in_future >= -7200 && $seconds_in_future < 86400)
                {
                    print "<tr><td>$title</td><td>$start_time</td><td>$length_minutes</td><td>$student_name</td><td>$teacher_name</td>";

                    // allow users to log in up to early_login_mins mins before scheduled start time
                    if ($seconds_in_future < 60*$early_login_mins)
                    {
                        print "<td><form method=post action=\"$room\"><input type=\"hidden\" name=\"session_id\" value=\"$session_id\"><input type=submit value=\"Login\"></form></td>";
                    }
                    else
                    {
                        print "<td>&nbsp;</td>";
                    }
                    print "</tr>\n";
                }
            }
        }
        else
        {
            print "<tr><td align=center colspan=6>No scheduled sessions</td></tr>";
        }
        print "</table>";
    }
}

function show_login($msg)
{
    print "<form>";
    print "<table>";
    if ($msg != "")
    {
        print "<p class=error>$msg</p>";
    }
    print "<tr><td>Username:</td><td><input type=text maxlength=40 name=username value=\"$username\"></td></tr>";
    print "<tr><td>Password:</td><td><input type=password maxlength=40 name=password value=\"$password\"></td></tr>";
    print "</table>";
    print "<p><input type=submit value=\"Login\"></p></form>";
}
?>
</center>
<?php
if ($_SESSION['username'] != "")
{
    print '<p><a href="logout.php">Logout</a></p>';
}
?>
</body>
</html>

