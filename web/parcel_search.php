<?php
require("dbconnect.php");
// Get parameters from URL
$query = $_POST['query'];
$ownerdecode = base64_decode($owner);

// Opens a connection to PostgreSQL server.
$connection=pg_connect ("dbname=$database user=$user password=$password host=$host port=$port");
if (!$connection) {
    die("Not connected : " . pg_error());
}

$sql = "SELECT gid, parcel_id, printkey, owner, address, box2d(st_transform(the_geom,900913)) as bbox FROM parcels2010 WHERE owner LIKE '$query%' OR printkey LIKE '$query%' OR address LIKE '$query%'";

$result = pg_exec($connection, $sql);

if (!$result) {printf ("ERROR"); exit;}

// Creates an array of strings to hold the lines of the json file.
$json = array();
$json[] = ' { ';
    $json[] = ' "type": "FeatureCollection", ';
    $json[] = ' "features": [ ';
    
    $features = array();
    // Iterates through the rows, printing a node for each row.
    while ($row = @pg_fetch_assoc($result)){
        $pgbbox = $row['bbox'];
        $search = array("BOX(", " ", ")");
        $replace = array("", ",", "");
        $bbox = str_replace($search,$replace,$pgbbox);
        $features[] = ' { "type": "Feature", "properties": { "gid": "' . $row['gid'] . '", "parcel_id": "' . $row['parcel_id'] . '", "printkey": "' . $row['printkey'] . '", "owner": "' . $row['owner'] . '", "address": "' . $row['address'] . '"}, "bbox": [' . $bbox . ']}';
        
    }
    $json[] = implode(",\n", $features);
    $json[] = ' ] ';
$json[] = '} ';
$jsonOutput = join("\n", $json);
header('Content-type: application/json');
echo $jsonOutput;
?>