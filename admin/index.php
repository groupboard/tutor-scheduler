<?php
require_once("../config.php");
require_once("../db.php");
if ($_SESSION['username'] == '' || $_SESSION['user_type'] != 'A')
{
    header('Location: ../index.php');
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Tutoring Scheduler Admin</title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<a href="users.php">Manage Users</a>
<p>
<a href="sessions.php">Manage Sessions</a>
<?php
if ($_SESSION['username'] != "")
{
    print '<p><a href="../logout.php">Logout</a></p>';
}
?>
</body>
</html>
