<!DOCTYPE html>
<html>
<head>
<style>
body
{
  background-color: #ccccff;
}

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

<?php
$starttime = microtime(true);
define("RECORDINGS_PHP_URL", "http://www.familiecoenen.nl/recordings.php");
define("RECORDINGS_DIRECTORY", "recordings");
define("RECORDINGS_EXTENSION", "*.jpeg");

function getBase($path) {
    $last_part = strrchr($path, "/");
    $base = str_replace($last_part, '', $path);
    return $base;
}

function getFile($path) {
    $last_part = strrchr($path, "/");
    $file = substr($last_part, 1);
    return $file;
}

echo("<center><table style='width:40%; border:0px'><tr>");
if (isset($_GET['picture'])) {
    $refesh_page = getBase($_GET["picture"]);
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "'\" class=\"button\">Back to all timeslots</button></th>");
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page'\" class=\"button\">Step back</button></th>");
} else if (isset($_GET['timeslot'])) {
    $refesh_page = $_GET["timeslot"];
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "'\" class=\"button\">Back to all timeslots</button></th>");
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page'\" class=\"button\">Refresh this timeslot</button></th>");
} else {
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "'\" class=\"button\">Refresh</button></th>");
}
echo("</tr></table></center><br><br>");

function printPath() {
        $d = dir(getcwd());
        echo("<center>Path: " . $d->path . "<br></center>");
        $d->close(); 
}

function presentTimeslots($dir) {
    if (is_dir($dir)) {
        chdir($dir);
        printPath();

        if ($timeslots = glob("*", GLOB_ONLYDIR)) {
            $coloms = 6;
            rsort($timeslots);
            $n_timeslots = count($timeslots);
            echo("<center><table style='width:90%'>");
            echo("<th colspan=\"$coloms\">$n_timeslots timeslots found</th>");
            for($index = 0; $index < $n_timeslots; $index++) {
                $timeslot = $timeslots[$index];
                if (($index % $coloms) == 0) echo("<tr>");
                echo("<td><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?timeslot=$dir/$timeslot'\" class=\"button\">$timeslot</button></td>");
                if ((($index + 1) % $coloms) == 0) echo("</tr>");
            }
            echo("</table></center>");
        } else {
            echo("<center><h1>No timeslots found</h1></center>");
        }
    } else {
        echo("<center><h1>[$dir] is not a directory</h1></center>");
    }
}

function presentPictures($timeslot) {
    if (is_dir($timeslot)) {
        chdir($timeslot);
        printPath();

        if ($pictures = glob(RECORDINGS_EXTENSION)) {
            $coloms = 4;
            rsort($pictures);
            $n_pictures = count($pictures);
            echo("<center><table style='width:90%'>");
            echo("<th colspan=\"$coloms\">$n_pictures pictures found</th>");
            for($index = 0; $index < $n_pictures; $index++) {
                $picture = $pictures[$index];
                if (($index % $coloms) == 0) echo("<tr>");
                echo("<td><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$timeslot/$picture'\" class=\"button\">$picture</button></td>");
                if ((($index + 1) % $coloms) == 0) echo("</tr>");
            }
            echo("</table></center>");
        } else {
            echo("<center><h1>No pictures found</h1></center>");
        }
    } else {
        echo("<center><h1>[$timeslot] is not a directory</h1></center>");
    }
}

function presentPicture($picture) {
    if (is_file($picture)) {
        $picture_base = getBase($picture);
        $picture_file = getFile($picture);
        if (is_dir($picture_base)) {
            chdir($picture_base);
            echo("<center>Base is [$picture_base], File is [$picture_file]</center><br>");
            echo("<center>Picture fullname is [$picture]</center><br>");

            if ($pictures = glob(RECORDINGS_EXTENSION)) {
                rsort($pictures);
                $n_pictures = count($pictures);
                $index = 0;
                while ((0 != strcmp($picture_file, $pictures[$index])) && ($index < $n_pictures)) $index++;
                $prev_index = $index - 1;
                $next_index = $index + 1;
                $position = $index + 1; // array[0] is position 1
                echo("<center>This picture is found in position [$position/$n_pictures]</center><br>");
                echo("<center><table style='width:100%; border:0px'><tr>");
                if ($prev_index >= 0) {
                    $prev_file = $pictures[$prev_index];
                    echo("<td style='width:50%; border:0px' align=\"left\"><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$picture_base/$prev_file'\" class=\"button\">PREVIOUS</button></td>");
                } else {
                    echo("<td style='width:50%; border:0px'></td>");
                }
                if ($next_index < $n_pictures) {
                    $next_file = $pictures[$next_index];
                    echo("<td style='width:50%; border:0px' align=\"right\"><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$picture_base/$next_file'\" class=\"button\">NEXT</button></td>");
                } else {
                    echo("<td style='width:50%; border:0px'></td>");
                }
                echo("</tr></table></center>");
            } else {
                echo("<center><h1>No more pictures found</h1></center>");
            }
            echo("<center><img src='$picture'></img></center><br>");
        } else {
            echo("<center><h1>[$picture_base] is not a directory</h1></center>");
        }
    } else {
        echo("<center><h1>[$picture] is not a regular file</h1></center>");
    }
}

if (isset($_GET['picture'])) {
    presentPicture($_GET["picture"]);
} else if (isset($_GET['timeslot'])) {
    presentPictures($_GET["timeslot"]);
} else {
    presentTimeslots(RECORDINGS_DIRECTORY);
}

$stoptime = microtime(true);

print "<br><br><center>| Execution time: " . number_format( ($stoptime - $starttime), 5) . " |</center><br><br>";

?>

</body>
</html>
