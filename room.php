<?php
require_once("config.php");
$session_id = $_REQUEST['session_id'];
if ($session_id == '')
{
    die("no session id");
}

// set the room (instance) name to the session id
$instance = $session_id;

$username = $_SESSION['username'];
if ($username == '')
{
    die("you must be logged in to see this page");
}
$user_type = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html>
<head>
<meta id="vp" name="viewport" content="width=device-width, initial-scale=1">
<title>Groupworld HTML5 Conference Room</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"><script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> <script type="text/javascript"> window.jQuery || document.write('<script src="<?php print $groupworld_js; ?>/jquery.min.js"><\/script>'); </script> <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script><link rel="stylesheet" type="text/css" href="newui.css">

<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/bkpfcahabbgcjikdomdcamdlcipolkcf">
<link rel="stylesheet" href="style.css" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php print $groupworld_js; ?>/dialog.css">

<?php print'<script type="text/javascript" src="'.$groupworld_js.'/aes-enc.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/aes-dec.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/aes-test.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/sha1.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/newbase64.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/dialog.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/tunnel.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/gsm610.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/groupworld.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/deskshare.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/polls.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/jscolor/jscolor.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/new_conference.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/new_whiteboard.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/new_videostrip.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/chat.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/jspdf.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/jspdf.plugin.addimage.min.js"></script>
<script type="text/javascript" src="'.$groupworld_js.'/FileSaver.min.js"></script>'; ?>

<!-- Emojis -->
<?php print '<link rel="stylesheet" type="text/css" href="'.$groupworld_js.'/jquery-emoji-picker-master/css/jquery.emojipicker.css"> <link rel="stylesheet" type="text/css" href="'.$groupworld_js.'/jquery-emoji-picker-master/css/jquery.emojipicker.twcdn.css"> <script type="text/javascript" src="'.$groupworld_js.'/jquery-emoji-picker-master/js/jquery.emojipicker.min.js"></script><script async type="text/javascript" src="'.$groupworld_js.'/jquery-emoji-picker-master/js/jquery.emojis.min.js"></script>'; ?>
<script type="text/javascript">

<?php if ($user_type != 'A') print "setInterval(checkTime, 1000);" ?>

function checkTime()
{
    var r = new XMLHttpRequest();
    r.addEventListener("load", function()
    {
        if (this.responseText != 'ok')
        {
            window.window_alert(this.responseText);
            window.location = 'logout.html';
        }
    });
    r.open("GET", "check_session_time.php?session_id=<?php print $session_id; ?>&user_id=<?php print $_SESSION['user_id']; ?>");
    r.send();
}

function start()
{
    if (screen.width < 800)
    {
        var mvp = document.getElementById('vp');
        mvp.setAttribute('content','width=800');
    }

    // Optional username and password. If not specified here, a popup dialog
    // box will appear.
    var username = '<?php print $username; ?>';
    var password = '';

    // The following lines change the default websocket port from 9175 to
    // 80 or 443. In order to use port 80 or 443 you must have configured you
    // webserver to proxy the websocket connection if using the self-hosted
    // enterprise version. See the "USING PORT 80/443" section of the 
    // Groupworld HTML5 client README file. Just comment out these lines if 
    // you have not set that up.

    if (window.location.protocol == 'https:')
    {
        groupworld.tunnel_port = 443;
    }
    else
    {
        groupworld.tunnel_port = 80;
    }

    // The GroupWorld server to connect to. You can optionally specify the
    // port number and installation id (using the format "server:port:install_id").
    var base = '<?php print $groupworld_server; ?>:'+groupworld.tunnel_port+'<?php if ($groupworld_id != "") print ":$groupworld_id"; ?>';

    // The object to load and instance name. To create a different "session",
    // just copy the html page and change the instance name.
    var object = 'new_conference:<?php print $instance; ?>';

    // Flags: not currently used.
    var flags = 0;
    groupworld.startup(username, password, base, object, flags);
}

</script>
<style>
/* Bug in Chrome: if you draw on the whiteboard and move off the top,
   Chrome will select a bunch of stuff which will then be dragged the next
   time you click on the whiteboard. To fix, we just turn off selection for
   all elements. */
* { 
    -moz-user-select: none; 
    -khtml-user-select: none; 
    -webkit-user-select: none; 
    -o-user-select: none; 
} 

/* Setting webkit-user-select to none screws up inputs on Safari.
 */
input {
    -webkit-user-select: auto;
}

/* For IE we need to disable touch behaviours (pan/zoom) on the canvas elements,
   so that the events are sent to the application instead. */
canvas {
    touch-action: none;
}

body { padding: 0; margin: 0; }
p { padding-left: 8px; }
</style>
</head>
<body 
onLoad="REDIPS.dialog.init(); start();">
<script src="<?php print $groupworld_js; ?>/strings.js"></script>
<script type="text/javascript">

</script>
<script type="text/javascript">
// Generate the actual Groupworld object. Parameters: width and height of
// object
new_whiteboard.newui = true;
// Add any API parameters here (http://www.groupworld.net/api.shtml)

new_whiteboard.newui = true;
new_whiteboard.options['default_size'] = 3;
new_whiteboard.options['show_share_video'] = 'true';
new_videostrip.options['show_auto_record'] = 'false';
new_videostrip.options['use_webrtc'] = 'true';
new_whiteboard.options['multi_user_pan'] = 'false';
new_conference.options['use_instance_recording_dir'] = 'true';
new_conference.options['use_scrollbars'] = 'false';
groupworld.htmlgen('100%','100%');
</script>
</body>
</html>
