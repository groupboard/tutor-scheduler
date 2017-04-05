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
<title>User Management</title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?php
$action = $_REQUEST['action'];
if ($action == 'new')
{
    $user_id = $_REQUEST['user_id'];
    if ($user_id != '')
    {
        if (!preg_match('/^\d+$/',$user_id))
        {
            die("invalid user id '$user_id'");
        }
        $query = "select * from user where user_id=$user_id";
        $result = mysqli_query($mysql_link, $query);
        if ($result && mysqli_num_rows($result))
        {
            while ($row = mysqli_fetch_assoc($result)) 
            {
                $username = $row['username'];
                $type = $row['type'];
                $date_created = $row['date_created'];
                $email = $row['email'];
                $password = $row['password'];
                $firstname = $row['firstname'];
                $surname = $row['surname'];
                $phone = $row['phone'];
                $street_address = $row['street_address'];
                $other_address = $row['other_address'];
                $town = $row['town'];
                $state = $row['state'];
                $zipcode = $row['zipcode'];
                $country_id = $row['country_id'];
            }
        }
        else
        {
            die("error getting user details for user $user_id");
        }
    }
    print "<form><table>";
    print "<input type=hidden name=action value=add><br>";
    print "<input type=hidden name=user_id value=\"$user_id\"><br>";
    print "<tr><td>Username: </td><td><input required type=text name=username value=\"$username\"><br></td></tr>";
    print "<tr><td>Email: </td><td><input required type=text name=email value=\"$email\"><br></td></tr>";
    print "<tr><td>Password: </td><td><input required type=password name=password value=\"$password\"><br></td></tr>";
    $t_sel = ($type == 'T' ? 'selected' : '');
    $s_sel = ($type == 'S' ? 'selected' : '');
    $a_sel = ($type == 'A' ? 'selected' : '');
    print "<tr><td>User type: </td><td><select name=type><option $s_sel value=\"S\">Student</option><option $t_sel value=\"T\">Teacher</option><option $a_sel value=\"A\">Admin</option></select><br></td></tr>";
    print "<tr><td>First Name: </td><td><input required type=text name=firstname value=\"$firstname\" required><br></td></tr>";
    print "<tr><td>Surname: </td><td><input required type=text name=surname value=\"$surname\" required><br></td></tr>";
    print "<tr><td>Phone: </td><td><input type=text name=phone value=\"$phone\"><br></td></tr>";
    print "<tr><td>Street: </td><td><input type=text name=street value=\"$street\"><br></td></tr>";
    print "<tr><td>Other Address: </td><td><input type=text name=other value=\"$other\"><br></td></tr>";
    print "<tr><td>Town: </td><td><input type=text name=town value=\"$town\"><br></td></tr>";
    print "<tr><td>State: </td><td><input type=text name=state value=\"$state\" maxlength=2><br></td></tr>";
    print "<tr><td>Postcode/zipcode: </td><td><input type=text name=zipcode value=\"$zipcode\" maxlength=10><br></td></tr>";
    print "<tr><td>Country:</td><td> <select name=country_id>";
    include("countries.php");
    print "</select></td></tr>";
    print "</table><br><input type=submit value=Submit></form>";
}
else if ($action == 'add')
{
    $user_id = $_REQUEST['user_id'];
    if ($user_id != '')
    {
        if (!preg_match('/^\d+$/',$user_id))
        {
            die("invalid user id");
        }
    }
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $type = $_REQUEST['type'];
    if ($type != 'A' && $type != 'S' && $type != 'T')
    {
        die("invalid type");
    }
    $email = $_REQUEST['email'];
    $firstname = $_REQUEST['firstname'];
    $surname = $_REQUEST['surname'];
    $phone = $_REQUEST['phone'];
    $street = $_REQUEST['street'];
    $other = $_REQUEST['other'];
    $town = $_REQUEST['town'];
    $state = $_REQUEST['state'];
    $zipcode = $_REQUEST['zipcode'];
    $country_id = $_REQUEST['country_id'];
    if ($user_id == '')
    {
        $query = sprintf("insert into user values(null, '%s', '%s', NOW(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d)",
            mysqli_real_escape_string($mysql_link, $username),
            mysqli_real_escape_string($mysql_link, $type),
            mysqli_real_escape_string($mysql_link, $email),
            mysqli_real_escape_string($mysql_link, $password),
            mysqli_real_escape_string($mysql_link, $firstname),
            mysqli_real_escape_string($mysql_link, $surname),
            mysqli_real_escape_string($mysql_link, $phone),
            mysqli_real_escape_string($mysql_link, $street),
            mysqli_real_escape_string($mysql_link, $other),
            mysqli_real_escape_string($mysql_link, $town),
            mysqli_real_escape_string($mysql_link, $state),
            mysqli_real_escape_string($mysql_link, $zipcode),
            mysqli_real_escape_string($mysql_link, $country_id)
            );
    }
    else
    {
        $query = sprintf("update user set username='%s', type='%s', email='%s', password='%s', firstname='%s', surname='%s', phone='%s', street_address='%s', other_address='%s', town='%s', state='%s', zipcode='%s', country_id=%d where user_id=$user_id",
            mysqli_real_escape_string($mysql_link, $username),
            mysqli_real_escape_string($mysql_link, $type),
            mysqli_real_escape_string($mysql_link, $email),
            mysqli_real_escape_string($mysql_link, $password),
            mysqli_real_escape_string($mysql_link, $firstname),
            mysqli_real_escape_string($mysql_link, $surname),
            mysqli_real_escape_string($mysql_link, $phone),
            mysqli_real_escape_string($mysql_link, $street),
            mysqli_real_escape_string($mysql_link, $other),
            mysqli_real_escape_string($mysql_link, $town),
            mysqli_real_escape_string($mysql_link, $state),
            mysqli_real_escape_string($mysql_link, $zipcode),
            mysqli_real_escape_string($mysql_link, $country_id)
            );

    }

    $result = mysqli_query($mysql_link, $query);
    if ($result)
    {
        print "<p>User added successfully</p>";
        header('Location: users.php');
    }
    else
    {
        print "<p>Error adding user: ".mysqli_error($mysql_link)."</p>";
    }
}
else if ($action == 'del')
{
    $user_id = $_REQUEST['user_id'];
    if ($user_id == '')
    {
        die("no user id specified");
    }
    if (!preg_match('/^\d+$/',$user_id))
    {
        die("invalid user id");
    }
    $query = "delete from user where user_id=$user_id";
    $result = mysqli_query($mysql_link, $query);
    if ($result)
    {
        print "<p>User deleted successfully</p>";
    }
    else
    {
        print "<p>Error deleting user: ".mysqli_error($mysql_link)."</p>";
    }
    header('Location: users.php');
}
else
{
    print "<table><tr>";
    print "<th>Userid</th><th>Username</th><th>Type</th><th>Date Created</th><th>Email</th><th>Password</th><th>First name</th><th>Surname</th><th>Phone</th><th>Street</th><th>Other</th><th>Town</th><th>State</th><th>Zipcode</th><th>Country</th><th>DELETE</th><th>EDIT</th></tr>";
    print "</tr>";
    $query = "select * from user";
    $result = mysqli_query($mysql_link, $query);
    if ($result && mysqli_num_rows($result))
    {
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $user_id = $row['user_id'];
            $username = $row['username'];
            $type = $row['type'];
            if ($type == 'A')
            {
                $display_type = 'Admin';
            }
            else if ($type == 'T')
            {
                $display_type = 'Teacher';
            }
            else if ($type == 'S')
            {
                $display_type = 'Student';
            }
            $date_created = $row['date_created'];
            $email = $row['email'];
            $password = $row['password'];
            $firstname = $row['firstname'];
            $surname = $row['surname'];
            $phone = $row['phone'];
            $street_address = $row['street_address'];
            $other_address = $row['other_address'];
            $town = $row['town'];
            $state = $row['state'];
            $zipcode = $row['zipcode'];
            $country_id = $row['country_id'];
            $country = '';

            if (preg_match('/^\d+$/',$country_id))
            {
                $q2 = "select country.name from country where country.country_id=$country_id";
                $result2 = mysqli_query($mysql_link, $q2);
                if ($result2 && mysqli_num_rows($result2))
                {
                    if ($row2 = mysqli_fetch_row($result2))
                    {
                        $country = $row2[0];
                    }
                }
            }

            print "<tr><td>$user_id</td><td>$username</td><td>$display_type</td><td>$date_created</td><td>$email</td><td>$password</td><td>$firstname</td><td>$surname</td><td>$phone</td><td>$street_address</td><td>$other_address</td><td>$town</td><td>$state</td><td>$zipcode</td><td>$country</td><td><a href=\"?action=del&user_id=$user_id\">Delete User</a></td><td><a href=\"?action=new&user_id=$user_id\">Edit User</a></td></tr>";
        }
    }
    print "</table>";
    print '<p><a href="?action=new">Add User</a><p>';
}
?>
<p>
<a href="index.php">Admin Home</a>
</body>
</html>
