<!DOCTYPE html>
<html lang="en">
<head>
    <title>Recordings</title>
    <meta charset="UTF-8">
    <meta name="generator" content="Notepad++" />
    <meta name="copyright" content="(c) 2019 Harm Coenen" />
    <meta name="description" content="Familie Coenen Recordings - Recordings van het huis en omgeving van de familie Coenen te Beugen" />
    <meta name="keywords" content="Coenen,familiecoenen,familie coenen,Beugen" />
    <meta name="author" content="Harm Coenen" />
    <meta name="robots" content="index,follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../w3-custom.css">
    <link rel="stylesheet" href="../w3.css">
    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.jpg" />
</head>

<body class="w3-pale-purple">

<?php

/* Define section */
define("MAIN_URL", "http://www.familiecoenen.nl");
define("RECORDINGS_PHP_URL", "http://www.familiecoenen.nl/recordings/recordings.php");
define("PHP_INFO_PHP_URL", "http://www.familiecoenen.nl/php-info.php");
define("RTSP_LNK", "https://rtsp.me/embed/5eF2knd3/");
define("RECORDINGS_DIRECTORY", ".");
define("RECORDINGS_EXTENSION", "*.jpeg");
define("RECORDINGS_IPCAM", "*[^p][^y].jpeg");
define("RECORDINGS_PICAM", "*_py.jpeg");
define("CTRL_DIR_UI", "/user_interrupt");
define("CTRL_DIR_EO", "/error_occurred");
define("CTRL_DIR_FR", "/force_reboot");

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
        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
        print "Path: " . getcwd();
        print "</div>";
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
            $slots_per_period = 24;
            rsort($timeslots);
            $n_timeslots = count($timeslots);

            print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "$n_timeslots timeslots found";
            print "</div>";

            for($index = 0; $index < $n_timeslots; $index++) {
                $timeslot = $timeslots[$index];
                if ($index == 0) {
                    print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
                } else {
                    if (($index % $slots_per_period) == 0) {
                        print "</div>";
                        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
                    }
                }
                print "<a class='w3-center w3-btn w3-border w3-border-purple w3-margin-bottom w3-margin-right w3-round-xxlarge' href='" . RECORDINGS_PHP_URL . "?timeslot=$dir/$timeslot'>$timeslot</a>";
            }
            print "</div>";
        } else {
            print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-red w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "<h1>No timeslots found</h1>";
            print "</div>";
        }
    } else {
        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-red w3-margin-top w3-margin-bottom w3-padding-4'>";
        print "<h1>[$dir] is not a directory</h1>";
        print "</div>";
    }
}

function presentRTSP($rtspme) {
    print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
    print "<iframe width='640' height='480' src='" . RTSP_LNK . "' frameborder='0' allowfullscreen></iframe>";
    print "</div>";
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
            for($index = 0; $index < $n_pictures; $index++) {
                $totalsize += filesize($pictures[$index]);
            }
            $totalsize = humanReadable($totalsize);

            print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "$n_pictures pictures found (total size $totalsize)";
            print "</div>";

            for($index = 0; $index < $n_pictures; $index++) {
                $picture = $pictures[$index];
                $caption = rtrim($picture, ".jpeg");
                $caption = rtrim($caption, "_py");
                //$picturesize = humanReadable(filesize($picture));
                // Get original size information from picture
                //list($org_width, $org_height, $type, $attr) = getimagesize($picture);
                // Divide original size by 16 to get small thumbnail icons
                // 1280x720 (divide by 16 = 80x45) or 1600x1200 (divide by 16 = 100x75)
                //$tn_width = floor($org_width / 16);
                //$tn_height = floor($org_height / 16);
                if ($index == 0) {
                    print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
                } else {
                    if (($index % $coloms) == 0) {
                        print "</div>";
                        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
                    }
                }
                print "<a class='w3-center w3-btn w3-border w3-border-purple w3-margin-bottom w3-margin-right w3-round-xxlarge' href='" . RECORDINGS_PHP_URL . "?picture=$timeslot/$picture&$filter'>$caption<br>";
                //print "<img src=$timeslot/$picture width=$tn_width height=$tn_height alt='loading...'>";
                print "<img src=$timeslot/$picture height=100 alt='loading...'>";
                print "</a>";
            }
            print "</div>";
        } else {
            print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-red w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "<h1>No pictures found</h1>";
            print "</div>";
        }
    } else {
        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-red w3-margin-top w3-margin-bottom w3-padding-4'>";
        print "<h1>[$timeslot] is not a directory</h1>";
        print "</div>";
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
                print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
                print "This picture is found in position [$position/$n_pictures]";
                print "</div>";

                print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
                if ($prev_index >= 0) {
                    $prev_file = $pictures[$prev_index];
                    print "<a class='w3-margin-left w3-margin-right w3-left w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "?picture=$picture_base/$prev_file&$filter'>LATER</a>";
                }
                if ($next_index < $n_pictures) {
                    $next_file = $pictures[$next_index];
                    print "<a class='w3-margin-left w3-margin-right w3-right w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "?picture=$picture_base/$next_file&$filter'>EARLIER</a>";
                }
                print "</div>";
            } else {
                print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-red w3-margin-top w3-margin-bottom w3-padding-4'>";
                print "<h1>No more pictures found</h1>";
                print "</div>";
            }
            print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "<img src='$picture' alt='loading...'><br>";
            print "<br>Base is [$picture_base], File is [$picture_file]";
            print "<br>Picture fullname is [$picture]";
            print "</div>";
        } else {
            print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-red w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "<h1>[$picture_base] is not a directory</h1>";
            print "</div>";
        }
    } else {
        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-red w3-margin-top w3-margin-bottom w3-padding-4'>";
        print "<h1>[$picture] is not a regular file</h1>";
        print "</div>";
    }
}

function presentCtrlDirs($basepath) {
    print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
    $ctrl_dir_style_present = "w3-small w3-margin w3-center w3-btn w3-round-xlarge w3-red w3-text-yellow w3-border w3-border-yellow";
    $ctrl_dir_style_not_present = "w3-small w3-margin w3-center w3-btn w3-round-xlarge w3-pale-purple w3-text-purple w3-border w3-border-purple";
    if (is_dir($basepath . CTRL_DIR_UI)) {
        print "<a class='$ctrl_dir_style_present' href='" . RECORDINGS_PHP_URL . "?tcd=ui'>user interrupt</a>";
    } else {
        print "<a class='$ctrl_dir_style_not_present' href='" . RECORDINGS_PHP_URL . "?tcd=ui'>user interrupt</a>";
    }
    if (is_dir($basepath . CTRL_DIR_EO)) {
        print "<a class='$ctrl_dir_style_present' href='" . RECORDINGS_PHP_URL . "?tcd=eo'>error occurred</a>";
    } else {
        print "<a class='$ctrl_dir_style_not_present' href='" . RECORDINGS_PHP_URL . "?tcd=eo'>error occurred</a>";
    }
    if (is_dir($basepath . CTRL_DIR_FR)) {
        print "<a class='$ctrl_dir_style_present' href='" . RECORDINGS_PHP_URL . "?tcd=fr'>force reboot</a>";
    } else {
        print "<a class='$ctrl_dir_style_not_present' href='" . RECORDINGS_PHP_URL . "?tcd=fr'>force reboot</a>";
    }
    print "</div>";
}

function toggleCtrlDir($dir) {
    if (is_dir($dir)) {
        rmdir($dir);
    } else {
        mkdir($dir);
    }
}

function handleCtrlDirs($basepath) {
    if (isset($_GET['tcd'])) {
        switch ($_GET['tcd']) {
            case "ui": /* case for user interrupt */
                toggleCtrlDir($basepath . CTRL_DIR_UI);
                break;
            case "eo": /* case for error occurred */
                toggleCtrlDir($basepath . CTRL_DIR_EO);
                break;
            case "fr": /* case for force reboot */
                toggleCtrlDir($basepath . CTRL_DIR_FR);
                break;
            default;
                break;
        }
    }
}

/* Main program */
$starttime = microtime(true);
$basepath = getcwd();
handleCtrlDirs($basepath);

print "<header class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
if (isset($_GET['picture'])) {
    $refesh_page = getBase($_GET["picture"]);
    $filter = getFilter();
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "'>Time Slots</a>";
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page&$filter'>Step back</a>";
} else if (isset($_GET['timeslot'])) {
    $refesh_page = $_GET["timeslot"];
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "'>Time Slots</a>";
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page'>All</a>";
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page&picam'>PIcam</a>";
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "?timeslot=$refesh_page&ipcam'>IPcam</a>";
} else if (isset($_GET['rtspme'])) {
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . MAIN_URL . "'>www.familiecoenen.nl</a>";
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "'>Time Slots</a>";
} else {
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . MAIN_URL . "'>www.familiecoenen.nl</a>";
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "'>Refresh</a>";
    print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "?rtspme=me'>Live Camera</a>";
}
print "</header>";

if (isset($_GET['picture'])) {
    presentPicture($_GET["picture"]);
} else if (isset($_GET['timeslot'])) {
    presentPictures($_GET["timeslot"]);
} else if (isset($_GET['rtspme'])) {
    presentRTSP($_GET["rtspme"]);
} else {
    presentTimeslots(RECORDINGS_DIRECTORY);
    presentCtrlDirs($basepath);
}

$stoptime = microtime(true);

print "<footer class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>| Execution time: " . number_format( ($stoptime - $starttime), 5) . " | <a href='" . PHP_INFO_PHP_URL . "'>PHP version: " . phpversion() . " </a> |</footer>";

?>

</body>
</html>
