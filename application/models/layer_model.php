<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 * @property CI_Security $security
 */
class Layer_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function get_layer($id)
	{
		try
		{
			$default_db = $this->load->database('default', TRUE);
			$layer_id = $this->security->xss_clean($id);

			$sql = "SELECT id,
				name,
				name_long,
				trim(js_layers) as js_layers,
				js_transparent,
				js_isbaselayer,
				js_visibility,
				js_displayinlayerswitcher,
				js_tree_folder,
				js_layer_order,
				js_html_length,
				tab_url
				FROM layers WHERE id = ?";
			$query = $default_db->query($sql, array($layer_id));

			if ($query->num_rows() === 0)
			{
				return FALSE;
			}

			return $query->row();
		}
		catch (Exception $exc)
		{
			echo $exc->getTraceAsString();
		}
	}

	public function get_layers($a_ids)
	{
		$default_db = $this->load->database('default', TRUE);

		$ids = implode(',', $a_ids);

		// NOTE: All the checking for null in the where clause
		// are just to prevent errors while testing without all
		// the data in the DB
		$sql = "SELECT
				id,
				name,
				name_long,
				trim(js_layers) as js_layers,
				js_transparent,
				js_isbaselayer,
				js_visibility,
				js_displayinlayerswitcher,
				js_tree_folder,
				js_layer_order,
				js_html_length,
				js_layer_type,
				js_url,
				js_sphericalmercator,
				js_numzoomlevels,
				js_opacity,
				filter_type_view,
				filter_time_view,
				tab_url
				FROM layers WHERE
				(id IN ({$ids}) OR always_show = 1)
				AND js_layers IS NOT NULL
				AND js_transparent IS NOT NULL
				AND js_isbaselayer IS NOT NULL
				AND js_visibility IS NOT NULL
				AND js_displayinlayerswitcher IS NOT NULL
				AND js_tree_folder IS NOT NULL
				AND js_layer_order IS NOT NULL
				AND js_html_length IS NOT NULL
				ORDER BY js_layer_order
			";
		$query = $default_db->query($sql);

		if ($query->num_rows() === 0)
		{
			return FALSE;
		}

		return $query->result_array();
	}

	public function get_ordered_layers()
	{
		$default_db = $this->load->database('default', TRUE);
		$sql = "SELECT id, js_layer_order FROM layers ORDER BY js_layer_order";
		$query = $default_db->query($sql);

		foreach ($query->result() as $row)
		{
			$output[$row->id] = $row->js_layer_order;
		}

		return $output;
	}

	public function get_searchable_layers()
	{
		try
		{
			$default_db = $this->load->database('default', TRUE);
			$sql = "SELECT name FROM layers WHERE searchfield IS NOT NULL";
			$query = $default_db->query($sql);

			foreach ($query->result() as $row)
			{
				$output[] = $row->name;
			}

			return $output;
		}
		catch (Exception $exc)
		{
			echo $exc->getTraceAsString();
		}
	}

	public function build_layer_definitions($a_data)
	{
		try
		{
			$this->load->helper('number');

			$output = '';
//			print_r($a_data);
//			die();
			foreach ($a_data as $data)
			{
				if ($data['js_layer_type'] == 'xyz')
				{
					$output.= $this->_xyz_layer_definition($data);
				}
				else
				{
					$output.= $this->_wms_layer_definition($data);
				}
			}
			return $output;
		}
		catch (Exception $exc)
		{
			echo $exc->getTraceAsString();
		}
	}

	public function _xyz_layer_definition($data)
	{
		$data['js_sphericalmercator'] = boolean_format($data['js_sphericalmercator']);
		$data['js_isbaselayer'] = boolean_format($data['js_isbaselayer']);
		$data['js_visibility'] = boolean_format($data['js_visibility']);

		$output = <<<hdoc
					{$data['name']} = new OpenLayers.Layer.XYZ("{$data['name_long']}", "{$data['js_url']}", {
						sphericalMercator: {$data['js_sphericalmercator']},
						numZoomLevels: {$data['js_numzoomlevels']},
						isBaseLayer: {$data['js_isbaselayer']},
						visibility: {$data['js_visibility']},
						opacity: {$data['js_opacity']}
					});\n
hdoc;

		return $output;
	}

	public function _wms_layer_definition($data)
	{
		$data['geoserver_url'] = GEOSERVER_URL;
		$data['js_transparent'] = boolean_format($data['js_transparent']);
		$data['js_isbaselayer'] = boolean_format($data['js_isbaselayer']);
		$data['js_visibility'] = boolean_format($data['js_visibility']);
		$data['js_displayinlayerswitcher'] = boolean_format($data['js_displayinlayerswitcher']);
		$data['js_url'] = (($data['js_url'] == null) ? "http://{$data['geoserver_url']}/geoserver/wms" : $data['js_url']);

		$a_temp = explode(":", $data['js_layers']);
		$output = <<<hdoc
					{$data['name']} = new OpenLayers.Layer.WMS("{$data['name_long']}", "{$data['js_url']}", {
						layers: '{$data['js_layers']}',
						transparent: {$data['js_transparent']},
						format: "image/png"
					}, {
						layerid: '{$a_temp[1]}',
						isBaseLayer: {$data['js_isbaselayer']},
						visibility: {$data['js_visibility']},
						displayInLayerSwitcher: {$data['js_displayinlayerswitcher']}
					});\n
hdoc;

		return $output;
	}

	public function build_layer_vars($a_data)
	{
		$output = '';
		foreach ($a_data as $data)
		{
			$output.= "var {$data['name']};\n";
		}

		return $output;
	}

	public function build_layers_list($a_data)
	{
		$output = '';
		foreach ($a_data as $data)
		{
			$output.= "{$data['name']},\n";
		}

		return $output;
	}

	public function build_feature_controls($a_data)
	{
		$output = '';

		foreach ($a_data as $data)
		{
			$data['name'] = trim($data['name']);
			$output.= <<<hdoc
			    function {$data['name']}HTML(response) {
					if (response.responseText.length > {$data['js_html_length']}) {
						document.getElementById('{$data['name']}_Info').innerHTML = response.responseText;
						showFeatureInfo();
					}
					if (response.responseText.length <= {$data['js_html_length']}) {
					   document.getElementById('{$data['name']}_Info').innerHTML = "";
					}
				};\n
hdoc;
		}

		return $output;
	}

	public function build_feature_displays($a_data)
	{
		$output = '';

		foreach ($a_data as $data)
		{
			$data['geoserver_url'] = GEOSERVER_URL;
			$data['name'] = trim($data['name']);

			$output.= "document.getElementById('{$data['name']}_Info').innerHTML = \"\";";
			$output.= <<<hdoc
				if ({$data['name']}.getVisibility() == true) {
					document.getElementById('{$data['name']}_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
				}
				var {$data['name']}_url = {$data['name']}.getFullRequestString({
					REQUEST: "GetFeatureInfo",
					EXCEPTIONS: "application/vnd.ogc.se_xml",
					BBOX: map.getExtent().toBBOX(),
					X: event.xy.x,
					Y: event.xy.y,
					INFO_FORMAT: 'text/html',
					FEATURE_COUNT: 4,
					WIDTH: map.size.w,
					HEIGHT: map.size.h,
					QUERY_LAYERS: '{$data['js_layers']}'
				}, "http://{$data['geoserver_url']}/geoserver/wms");
				if ({$data['name']}.getVisibility() == true) {
					OpenLayers.loadURL({$data['name']}_url, '', this, {$data['name']}HTML);
				}\n
hdoc;
		}

		return $output;
	}

	public function build_html_sets($a_data)
	{
		$output = '';

		foreach ($a_data as $data)
		{
			$data['name'] = trim($data['name']);
			$output.= "document.getElementById('{$data['name']}_Info').innerHTML = \"\";";
			$output.= <<<hdoc
			            if ({$data['name']}.getVisibility() == true) {
							document.getElementById('{$data['name']}_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
						}
hdoc;
		}

		return $output;
	}

	public function build_layer_window_htmls($a_data)
	{
		$output = '';
		foreach ($a_data as $data)
		{
			$data['name'] = trim($data['name']);
			$output.= "<div id='{$data['name']}_Info'></div>";
		}

		return $output;
	}

	public function build_portfolio_panel($a_data, $protocol = false)
	{
		$output = false;

		foreach ($a_data as $data)
		{
			if ($data['tab_url'] != null)
			{
				$output = $data['tab_url'];

				// Set http protocol if it is passed in
				if ($protocol != false)
				{
					$pattern = '/^https?/i';
					$output = preg_replace($pattern, $protocol, $output);
				}
				break;
			}
		}

		return $output;
	}

	public function build_layer_nodes($a_data)
	{
		$output = '';

		foreach ($a_data as $data)
		{
			$node_var = "node{$data['name']}";

			$a_node_attr = array();
			$a_node_attr[] = 'nodeType: "gx_layer"';
			$a_node_attr[] = 'layer: ' . $data['name'];

			if ($data['filter_type_view'] != null)
			{
				$filter_types = $this->build_layer_type_filter_array($data['filter_type_view']);
				if ($filter_types != false)
				{
					$a_node_attr[] = "type_filter: " . $filter_types;
				}
			}

			// TODO: cannot use build_layer_time_filter_array
			// function yet. It is broken.
			if ($data['filter_time_view'] != null)
			{
				$filter_times = $this->build_layer_time_filter_array($data['filter_time_view']);
				if ($filter_times != false)
				{
					$a_node_attr[] = "time_filter: " . $filter_times;
				}
			}

			$s_node_attr = implode(',', $a_node_attr);
			$output.= <<<hdoc
				var {$node_var} = {
					{$s_node_attr}
				};\n
hdoc;
			$data2 = array(
				'node_var' => $node_var,
				'js_tree_folder' => $data['js_tree_folder']
			);
			$output.= $this->build_node_push($data2);
		}
		return $output;
	}

	public function build_layer_type_filter_array($filter_table)
	{
		$pg_db = $this->load->database('pgterradex', TRUE);
		$query = $pg_db->get($filter_table);

		if ($query->num_rows() === 0)
		{
			return false;
		}

		foreach ($query->result() as $row)
		{
			$temp[] = '["' . trim($row->displayname) . '", "type_filter=\'' . trim($row->type_filter) . '\'"]';
		}

		$output = "[" . implode(',', $temp) . "]";

		return $output;
	}

	// NOTE: Not using database as the values for the filters
	// there are hardcoded. Therefores, it saves a step
	// by just hardcoding it here in the code.
	public function build_layer_time_filter_array($filter_table)
	{
		$aTime_filter = array(
			'["All", "time_filter>=\'\'"]',
			'["Today", "time_filter=\'' . date('Y-m-d') . '\'"]',
			'["Last 2 Days", "time_filter>=\'' . date('Y-m-d', time() - (2 * 24 * 60 * 60)) . '\'"]',
			'["Last 3 Days", "time_filter>=\'' . date('Y-m-d', time() - (3 * 24 * 60 * 60)) . '\'"]',
			'["Last 4 Days", "time_filter>=\'' . date('Y-m-d', time() - (4 * 24 * 60 * 60)) . '\'"]',
			'["Last 5 Days", "time_filter>=\'' . date('Y-m-d', time() - (5 * 24 * 60 * 60)) . '\'"]',
			'["Last 6 Days", "time_filter>=\'' . date('Y-m-d', time() - (6 * 24 * 60 * 60)) . '\'"]',
			'["Last Week", "time_filter>=\'' . date('Y-m-d', time() - (7 * 24 * 60 * 60)) . '\'"]',
			'["Last 2 Weeks", "time_filter>=\'' . date('Y-m-d', time() - (14 * 24 * 60 * 60)) . '\'"]',
			'["Last Month (30 days)", "time_filter>=\'' . date('Y-m-d', time() - (30 * 24 * 60 * 60)) . '\'"]',
			'["' . date('Y') . '", "time_filter>=\'' . date('Y') . '-01-01\'"]',
			'["' . date('Y', time() - (365 * 24 * 60 * 60)) . '", "time_filter>=\'' . date('Y', time() - (365 * 24 * 60 * 60)) . '-01-01\' AND time_filter<=\'' . date('Y', time() - (365 * 24 * 60 * 60)) . '-12-31\'"]'
		);

		$output = "[" . implode(',', $aTime_filter) . "]";

		return $output;
	}

	public function build_node_push($data)
	{
		switch (strtolower($data['js_tree_folder']))
		{
			case 'cleanup':
				$output = "nodeCleanup.push({$data['node_var']});";
				break;

			case 'custom':
				$output = "nodeCustom.push({$data['node_var']});";
				break;

			case 'activity':
				$output = "nodeActivity.push({$data['node_var']});";
				break;

			case 'landwatch':
				$output = "nodeLandwatch.push({$data['node_var']});";
				break;

			case 'digclean':
				$output = "nodeDigClean.push({$data['node_var']});";
				break;

			case 'property':
				$output = "nodeProperty.push({$data['node_var']});";
				break;

			case 'environmental':
				$output = "nodeEnvironmental.push({$data['node_var']});";
				break;

			default:
				$output = '';
				break;
		}

		$output.= "\n";

		return $output;
	}

}