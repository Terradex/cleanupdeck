<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @property CI_DB_active_record $db
* @property CI_Security $security
*/

class Site_layer_model extends CI_Model
{
    private $pgdb;

    function __construct()
    {
        parent::__construct();
        $this->load->library('security');
        $this->pgdb = $this->load->database('pgterradex', TRUE);
    }

    function get_datasets()
    {
        $sql = "SELECT DISTINCT tdxdatas_1, dataset_fu FROM ce_kml_data_joined_all ORDER BY dataset_fu ASC";
        //$sql = "SELECT DISTINCT tdxdatas_1, dataset_fu, xmin(extent(geom)) || ',' || ymin(extent(geom)) || ',' || xmax(extent(geom)) || ',' || ymax(extent(geom)) as bbox FROM ce_kml_data_joined_all GROUP BY tdxdatas_1, dataset_fu ORDER BY dataset_fu";
        $query = $this->pgdb->query($sql);
        foreach ($query->result_array() as $row)
        {
            $output[] = $row;
        }
        if (isset($output))
        {
            return $output;
        }
        else
        {
            return false;
        }
    }

}
