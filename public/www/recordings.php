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

/* Define section */
define("RECORDINGS_PHP_URL", "http://www.familiecoenen.nl/recordings.php");
define("RECORDINGS_DIRECTORY", "recordings");
define("RECORDINGS_EXTENSION", "*.jpeg");
define("RECORDINGS_IPCAM", "*[^p][^y].jpeg");
define("RECORDINGS_PICAM", "*_py.jpeg");

/* Function section */
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

function humanReadable($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function printPath() {
        $path = getcwd();
        echo("<center>Path: " . $path . "<br></center>");
}

function getFilter() {
    $filter = "";
    if (isset($_GET['picam'])) $filter = "picam";
    if (isset($_GET['ipcam'])) $filter = "ipcam";
    if (isset($_GET['picam']) && isset($_GET['ipcam'])) $filter = "picam&ipcam";
    return $filter;
}

function getGlobRule() {
    $glob_rule = RECORDINGS_EXTENSION;
    if (isset($_GET['picam'])) $glob_rule = RECORDINGS_PICAM;
    if (isset($_GET['ipcam'])) $glob_rule = RECORDINGS_IPCAM;
    if (isset($_GET['picam']) && isset($_GET['ipcam'])) $glob_rule = RECORDINGS_EXTENSION;
    return $glob_rule;
}

/* Function to create thumbnails */
function makeThumbnail($src, $dest, $desired_width) {
    /* Read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* Find the “desired height” of this thumbnail, relative to the desired width */
    $desired_height = floor($height * ($desired_width / $width));

    /* create a new, “virtual” image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* Copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    /* Create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);

    /* Free up memory */
    imagedestroy($virtual_image);
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

        $filter = getFilter();
        if ($pictures = glob(getGlobRule())) {
            $coloms = 4;
            $totalsize = 0;
            rsort($pictures);
            $n_pictures = count($pictures);
            echo("<center><table style='width:90%'>");
            for($index = 0; $index < $n_pictures; $index++) {
                $totalsize += filesize($pictures[$index]);
            }
            $totalsize = humanReadable($totalsize);
            echo("<th colspan=\"$coloms\">$n_pictures pictures found (total size $totalsize)</th>");
            for($index = 0; $index < $n_pictures; $index++) {
                $picture = $pictures[$index];
                $picturesize = humanReadable(filesize($picture));
                // Get original size information from picture
                list($org_width, $org_height, $type, $attr) = getimagesize($picture);
                // Divide original size by 16 to get small thumbnail icons
                // 1280x720 (divide by 16 = 80x45) or 1600x1200 (divide by 16 = 100x75)
                $tn_width = floor($org_width / 16);
                $tn_height = floor($org_height / 16);
                
                if (($index % $coloms) == 0) echo("<tr>");
                //echo("<td><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$timeslot/$picture&$filter'\" class=\"button\">$picture<br>($picturesize)</button></td>");
                //echo("<td><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$timeslot/$picture&$filter'\" class=\"button\">$picture<br><img src=$timeslot/$picture width=$tn_width height=$tn_height alt=\"not found\"></img><br>($picturesize)<br>($tn_width x $tn_height), ($org_width x $org_height)</button></td>");
                echo("<td><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$timeslot/$picture&$filter'\" class=\"button\">$picture<br><br><img src=$timeslot/$picture width=$tn_width height=$tn_height alt=\"not found\"></img><br>($picturesize)</button></td>");
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

            $filter = getFilter();
            if ($pictures = glob(getGlobRule())) {
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
                    echo("<td style='width:50%; border:0px' align=\"left\"><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$picture_base/$prev_file&$filter'\" class=\"button\">LATER</button></td>");
                } else {
                    echo("<td style='width:50%; border:0px'></td>");
                }
                if ($next_index < $n_pictures) {
                    $next_file = $pictures[$next_index];
                    echo("<td style='width:50%; border:0px' align=\"right\"><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?picture=$picture_base/$next_file&$filter'\" class=\"button\">EARLIER</button></td>");
                } else {
                    echo("<td style='width:50%; border:0px'></td>");
                }
                echo("</tr></table></center>");
            } else {
                echo("<center><h1>No more pictures found</h1></center>");
            }
            echo("<center><img src='$picture'></img></center><br>");
            echo("<center>Base is [$picture_base], File is [$picture_file]</center><br>");
            echo("<center>Picture fullname is [$picture]</center><br>");
        } else {
            echo("<center><h1>[$picture_base] is not a directory</h1></center>");
        }
    } else {
        echo("<center><h1>[$picture] is not a regular file</h1></center>");
    }
}

/* Main program */
$starttime = microtime(true);

echo("<center><table style='width:40%; border:0px'><tr>");
if (isset($_GET['picture'])) {
    $refesh_page = getBase($_GET["picture"]);
    $filter = getFilter();
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "'\" class=\"button\">Overview</button></th>");
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page&$filter'\" class=\"button\">Step back</button></th>");
} else if (isset($_GET['timeslot'])) {
    $refesh_page = $_GET["timeslot"];
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "'\" class=\"button\">Overview</button></th>");
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page'\" class=\"button\">All</button></th>");
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page&picam'\" class=\"button\">PIcam</button></th>");
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page&ipcam'\" class=\"button\">IPcam</button></th>");
} else {
    echo("<th style='border:0px'><button onclick=\"location.href='" . RECORDINGS_PHP_URL . "'\" class=\"button\">Refresh</button></th>");
}
echo("</tr></table></center><br><br>");

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
