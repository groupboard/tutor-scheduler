<?php
require_once("../db.php");

$query = "select country_id,name from country order by name";
$result = mysqli_query($mysql_link, $query);
if((!$result) || (! mysqli_num_rows($result)))
{
    die("error listing countries");
}
while ($row = mysqli_fetch_row($result))
{
    $id = $row[0];
    $name = $row[1];
    $sel = '';
    if ($country_id == $id)
    {
        $sel = 'selected';
    }
    print "<option $sel value=\"$id\">$name</option>";
}

?>

