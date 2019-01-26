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
    background-color: #cc0099;
    border: none;
    border-radius: 15px;
    box-shadow: 0 9px #999;
}

.button:hover {
    background-color: #990073
}

.button:active {
    background-color: #990033;
    box-shadow: 0 5px #666;
    transform: translateY(4px);
}

table {
    border: 1px solid #ddd;
}

td {
    padding: 10px;
}

th {
    border: 1px dotted black;
    padding: 10px;
}
</style>
</head>
<body>

<center><button onclick="location.href='http://www.familiecoenen.nl/recordings.php'" class="button">Back to timeslots</button></center>
<br><br>

<?php

$starttime = microtime(true);
define("RECORDINGS_DIRECTORY", "recordings");

function printPath() {
        $d = dir(getcwd());
        echo "<center>Path: " . $d->path . "<br></center>";
        $d->close(); 
}

function presentTimeslots($dir) {
    if (is_dir($dir)) {
        chdir($dir);
        printPath();

        $coloms = 6;
        $timeslots = glob("*", GLOB_ONLYDIR);
        $n_timeslots = count($timeslots);
        echo("<center><table style='width:90%'>");
        echo("<th colspan=\"$coloms\">$n_timeslots timeslots found</th>");
        for($index = 0; $index < $n_timeslots; $index++) {
            $timeslot = $timeslots[$index];
            if (($index % $coloms) == 0) echo("<tr>");
            echo("<td><button onclick=\"location.href='../recordings.php?timeslot=$dir/$timeslot'\" class=\"button\">$timeslot</button></td>");
            if ((($index + 1) % $coloms) == 0) echo("</tr>");
        }
        echo("</table></center>");
    } else {
        echo "[$dir] is not a directory" . "<br>";
    }
}

function presentPictures($timeslot) {
    if (is_dir($timeslot)) {
        chdir($timeslot);
        printPath();

        $coloms = 6;
        $pictures = glob("*.jpeg");
        $n_pictures = count($pictures);
        echo("<center><table style='width:90%'>");
        echo("<th colspan=\"$coloms\">$n_pictures pictures found</th>");
        for($index = 0; $index < $n_pictures; $index++) {
            $picture = $pictures[$index];
            if (($index % $coloms) == 0) echo("<tr>");
            echo("<td><button onclick=\"location.href='../../recordings.php?picture=$timeslot/$picture'\" class=\"button\">$picture</button></td>");
            if ((($index + 1) % $coloms) == 0) echo("</tr>");
        }
        echo("</table></center>");
    } else {
        echo "[$timeslot] is not a directory" . "<br>";
    }
}

function presentPicture($picture) {
    echo "Picture is [$picture]" . "<br>";
    if (is_file($picture)) {
        printPath();
        echo("<img src='$picture'></img>");
    } else {
        echo "[$picture] is not a regular file" . "<br>";
    }
}

if (isset($_GET['picture'])) {
    presentPicture($_GET["picture"]);
} else if (isset($_GET['timeslot'])) {
    presentPictures($_GET["timeslot"]);
} else {
    presentTimeslots(RECORDINGS_DIRECTORY);
}

//$thisdir = ".";
//$a = scandir($thisdir, SCANDIR_SORT_ASCENDING);
//$b = scandir($thisdir, SCANDIR_SORT_DESCENDING);
//print_r($a);
//print_r($b);

$stoptime = microtime(true);

print "<br><br><center>| Execution time: " . number_format( ($stoptime - $starttime), 5) . " |</center><br><br>";

?>

</body>
</html>
