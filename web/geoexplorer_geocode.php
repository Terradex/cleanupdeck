<?php
// make sure the remote file is successfully opened before doing anything else
$query = $_POST['query'];
$safequery = urlencode($query);
if ($fp = fopen("http://maps.google.com/maps/api/geocode/json?sensor=false&address=$safequery", "r")) {
   $content = '';
   // keep reading until there's nothing left
   while ($line = fread($fp, 1024)) {
      $content .= $line;
   }
header("Content-type: text/json");
//header("Content-Disposition: attachment; filename=hotels.kml");
//header("Pragma: no-cache");
//header("Expires: 0");
print $content;
   // do something with the content here
   // ...
} else {
   // an error occured when trying to open the specified url
}
?>