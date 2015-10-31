<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_model extends CI_Model
{
   // private $csms;
    private $pgterradex;
    //private $table;

    function __construct()
    {
        parent::__construct();

        $this->pgterradex = $this->load->database('pgterradex', TRUE);
    }



    //======================================================
//  8/1/2013  Ticket  #320 Pull up Maps
    function get_map_data($table,$where_column,$where_datatype,$location_id,$geo_column)

    {   /*        $table='ny_remediation_view';
               $where_column='sourceid';
               $where_datatype='var';
               $location_id='C704032';
               $geo_column='geom';




     
 
   SELECT

   CAST ((Y (ST_Centroid (geom))) as varchar(12)) as lon,
   CAST ((X (ST_Centroid (geom))) as varchar(12)) as lat

   FROM ny_remediation_view

   WHERE sourceid='C704032'
   LIMIT 1
   */
    IF ($where_datatype=='var')
    { $sql = "
   SELECT
   CAST ((Y (ST_Centroid ($geo_column))) as varchar(12)) as lat,
   CAST ((X (ST_Centroid ($geo_column))) as varchar(12)) as lon

   FROM $table
   WHERE $where_column='$location_id'
   LIMIT 1 ";
        }

     else

$sql = "
   SELECT
   CAST ((Y (ST_Centroid ($geo_column))) as varchar(12)) as lat,
   CAST ((X (ST_Centroid ($geo_column))) as varchar(12)) as lon

   FROM $table
   WHERE $where_column=$location_id
   LIMIT 1  ";

        

     $query_pg = $this->pgterradex->query($sql);

    return $query_pg->row_array();


    }//end function



}//end class
?>
