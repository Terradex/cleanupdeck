<?php
$query = $_POST['query'];
$safequery = urlencode($query);
if ($fp = fopen("http://geoload.terradex.com:8080/geoserver/wfs?service=WFS&version=1.0.0&request=GetFeature&typeName=pgterradex:ce_kml_data_joined_all&propertyName=facilityna,facilityci,stateshort&outputformat=json&CQL_FILTER=facilityna%20like%20%27%25$safequery%25%27", "r")) {
   $content = '';
   while ($line = fread($fp, 1024)) {
      $content .= $line;
   }
header("Content-type: text/json");
print $content;
}
?>