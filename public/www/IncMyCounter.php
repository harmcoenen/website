<?php
if (file_exists("AantalBezoekers"))
{
  $myFile = fopen("AantalBezoekers", "r+");
  $visits = fgets($myFile, 6);
  $visits++;
  rewind($myFile);
  fwrite($myFile, $visits, 6);
  fclose($myFile);
}
else
{
  $myFile = fopen("AantalBezoekers", "w");
  $visits = "1";
  fwrite($myFile, $visits, 6);
  fclose($myFile);
}

$visits = sprintf("%05d", $visits);
?>