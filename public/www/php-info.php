<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Info</title>
    <meta charset="UTF-8">
    <meta name="generator" content="Notepad++" />
    <meta name="copyright" content="(c) 2019 Harm Coenen" />
    <meta name="description" content="Familie Coenen PHP Info - PHP Info van het huis en omgeving van de familie Coenen te Beugen" />
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
define("DETECTIONS_PHP_URL", "http://www.familiecoenen.nl/detections/detections.php");
define("RECORDINGS_PHP_URL", "http://www.familiecoenen.nl/recordings/recordings.php");

/* Function section */
function presentPHPInfo() {
    print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
    phpinfo();
    print "</div>";
}

/* Main program */
$starttime = microtime(true);

print "<header class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . MAIN_URL . "'>www.familiecoenen.nl</a>";
print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . RECORDINGS_PHP_URL . "'>Recordings</a>";
print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-border w3-border-purple w3-round-large' href='" . DETECTIONS_PHP_URL . "'>Detections</a>";
print "</header>";

presentPHPInfo();

$stoptime = microtime(true);

print "<footer class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>| Execution time: " . number_format( ($stoptime - $starttime), 5) . " | PHP version: " . phpversion() . " |</footer>";

?>

</body>
</html>
