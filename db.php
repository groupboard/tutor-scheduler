<?php
$tz = "EST/EDT";     # timezone (for display purposes)
$time_diff = 0;      # difference between server timezone and display timezone
                     # (-3 will convert server EDT to display PDT)
$date_format = "%m/%d/%y %l:%i%p"; # see mysql DATE_FORMAT
$db = "tutor_scheduler";
$db_username = "groupworld";
$db_pass = "";
$mysql_link = mysqli_connect("localhost", $db_username, $db_pass);
mysqli_select_db($mysql_link, $db);
mysqli_query($mysql_link, "set time_zone=concat(convert(timestampdiff(hour, utc_timestamp,now())+($time_diff),char), ':0');");

?>
