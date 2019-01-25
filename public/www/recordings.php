<!DOCTYPE html>
<html>
<head>
<style>
.button {
  padding: 5px 30px;
  font-size: 16px;
  text-align: center;
  cursor: pointer;
  outline: none;
  color: #fff;
  background-color: #ff33cc;
  border: none;
  border-radius: 15px;
  box-shadow: 0 9px #999;
}

.button:hover {background-color: #cc0099}

.button:active {
  background-color: #cc0099;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}
</style>
</head>
<body>

<center><button onclick="location.href='http://www.familiecoenen.nl/recordings.php'" class="button">Back to timeslots</button></center>
<br><br>

<?php
// http://www.familiecoenen.nl/recordings.php
// http://www.familiecoenen.nl/recordings.php?timeslot=2019.01.25-17hrs

$starttime = microtime(true);
define("RECORDINGS_DIRECTORY", "recordings");

function presentTimeslots($dir) {
    if (is_dir($dir)) {
        echo "[$dir] is a directory" . "<br>";
        chdir($dir);

        $d = dir(getcwd());
        echo "Handle: " . $d->handle . ", Path: " . $d->path . "<br>";
        $d->close(); 

        $timeslots = glob("*", GLOB_ONLYDIR);
        $n_timeslots = count($timeslots);
        echo "$n_timeslots timeslots found" . "<br>";
        echo("<center><table style='width:80%'>");
        for($index = 0; $index < $n_timeslots; $index++) {
            $timeslot = $timeslots[$index];
            if (($index % 6) == 0) echo("<tr>");
            // echo("<td><a href='../recordings.php?timeslot=$timeslot'>[$timeslot]</a></td>");
            echo("<td><button onclick=\"location.href='../recordings.php?timeslot=$timeslot'\" class=\"button\">$timeslot</button></td>");
            if ((($index + 1) % 6) == 0) echo("</tr>");
        }
        echo("</table></center>");
    } else {
        echo "[$dir] is not a directory" . "<br>";
    }
}

function presentPictures($timeslot) {
    echo "timeslot is [$timeslot]" . "<br>";
    if (is_dir($timeslot)) {
        echo "[$timeslot] is a directory" . "<br>";
        chdir($timeslot);

        $d = dir(getcwd());
        echo "Handle: " . $d->handle . ", Path: " . $d->path . "<br>";
        $d->close(); 

        $pictures = glob("*.jpeg");
        echo "Number of pictures found is: " . count($pictures) . "<br>";
        foreach ($pictures as $picture) {
            echo "[$picture], ";
        }
    } else {
        echo "[$timeslot] is not a directory" . "<br>";
    }
}

if (isset($_GET['timeslot'])) {
    presentPictures(RECORDINGS_DIRECTORY . "/" . $_GET["timeslot"]);
} else {
    presentTimeslots(RECORDINGS_DIRECTORY);
}

//$thisdir = ".";
//$a = scandir($thisdir, SCANDIR_SORT_ASCENDING);
//$b = scandir($thisdir, SCANDIR_SORT_DESCENDING);
//print_r($a);
//print_r($b);
//echo '<img src="recordings/2019.01.25-14hrs/2019_01_25_14_04_26.jpeg" />';

$stoptime = microtime(true);

print "<br><br><center>| Execution time: " . number_format( ($stoptime - $starttime), 5) . " |</center><br><br>";

?>

</body>
</html>
