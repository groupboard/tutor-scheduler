<?php
$cookie_lifetime = 2592000; // 30 days

# If using an enterprise license, change this to your server hostname or ip
$groupworld_server = 'www.groupworld.net';

# If using the hosted Groupworld, change this to your Groupworld id.
# If using the enterprise Groupworld, leave it blank
$groupworld_id = '';

# Number of minutes early that users can log into sessions
$early_login_mins = 5;

# Location of Groupworld javascript files. Change this to the location on
# your own web server, if using the enterprise version
$groupworld_js = 'https://www.groupworld.net/js';

session_name("tutoring_scheduler");
session_set_cookie_params($cookie_lifetime, "/");
session_start();
?>
