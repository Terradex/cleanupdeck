<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 * @property CI_Security $security
 */
class Coord_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
        $this->db = $this->load->database('pgterradex', TRUE);
	}

	/**
	 * Get X and Y coordinates in Mercator FROM Postgis
	 * based on lat long
	 */
	function get_xy_mercator_latlong($lat, $long)
	{
		$sql_statement = ("

                SELECT
                       ST_X(ST_Transform(ST_SetSRID(ST_Point('$long', '$lat'),4326),900913)) AS x_value,
                       ST_Y(ST_Transform(ST_SetSRID(ST_Point('$long', '$lat'),4326),900913)) AS y_value

	    ");

		$query = pg_query($sql_statement);


		if (!pg_num_rows($query))
		{
			return array();
		}
		else
		{
			while ($row = pg_fetch_assoc($query))
			{
				$table_array[] = array(
					'X' => $row['x_value'],
					'Y' => $row['y_value']
				);
			}
		}

		return $table_array;
	}

// end function
}