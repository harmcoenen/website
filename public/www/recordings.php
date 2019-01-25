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
        echo "Number of timeslots found is: " . count($timeslots) . "<br>";
        foreach ($timeslots as $timeslot) {
            //echo $timeslot . "<br>";
            echo "[$timeslot], ";
        }
        //print_r($timeslots);
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
        //print_r($timeslots);
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

print "<br>__________________________________________________________________<br>";

$stoptime = microtime(true);

print "<br><br><center>| Execution time: " . number_format( ($stoptime - $starttime), 5) . " |</center><br><br>";

?>