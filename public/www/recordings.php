<?php

$starttime = microtime(true);

print "<br>__________________________________________________________________<br><br>";
$thisdir = ".";
$a = scandir($thisdir, SCANDIR_SORT_ASCENDING);
$b = scandir($thisdir, SCANDIR_SORT_DESCENDING);
print_r($a);
print "<br>__________________________________________________________________<br><br>";
print_r($b);
print "<br>__________________________________________________________________<br><br>";

$recdir = "recordings";
if ( is_dir($recdir) )
{
    echo "[$recdir] is a directory" . "<br>";
    chdir($recdir);
}
else
{
    echo "[$recdir] is not a directory" . "<br>";
}

$d = dir( getcwd() );
echo "Handle: " . $d->handle . "<br>";
echo "Path: " . $d->path . "<br>";
$d->close(); 

print "<br>__________________________________________________________________<br><br>";
$timeboxes = glob("*", GLOB_ONLYDIR);
print "Number of time boxes found is: " . count($timeboxes) . "<br>";
print_r($timeboxes);
print "<br>__________________________________________________________________<br><br>";

$stoptime = microtime(true);

print "<br><br><center>| Execution time: " . number_format( ($stoptime - $starttime), 5) . " |</center><br><br>";

?>