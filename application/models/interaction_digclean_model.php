<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 */
class Interaction_digclean_model extends CI_Model
{
   // private $csms;
    private $pgterradex;
    //private $table;

    function __construct()
    {
        parent::__construct();
      //  $this->csms = $this->load->database('csms', TRUE);
        $this->pgterradex = $this->load->database('pgterradex', TRUE);
    }



    //======================================================
// 5/24/12 get the DigClean AppID to show the proper Dashboard for Advisories
    function get_digclean_ids($siteid)
    {

     $sql = "

SELECT
        tdx_dataset_apps_assignment.tdx_appsid,
        dc_site.pg_siteid
    FROM
    tdx_dataset_apps_assignment,
    dc_site
    WHERE tdx_dataset_apps_assignment.tdx_datasetid=dc_site.tdx_datasetid
    AND dc_site.site_id = $siteid

        ";

     $query_pg = $this->pgterradex->query($sql);

     $result_array=$query_pg->result_array();
    // echo"result value: ".$result_array['0']['tdx_appsid'];
 
    return $query_pg->result_array();


    }//end function



}//end class
?>
