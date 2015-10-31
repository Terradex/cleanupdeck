<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Email $email
 * @property CI_DB_active_record $db
 * @property CI_Session $session
 * @property Site_layer_model $site_layer_model
 */
class Map extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('site_layer_model');
		$this->load->model('layer_model');

		$data_map = array();
		$data_map['datasets'] = $this->site_layer_model->get_datasets();

		$data = array();

		if (isset($this->session->userdata['user_id']))
		{
			$data['user_id'] = $this->session->userdata['user_id'];
			$data['first_name'] = $this->session->userdata['first_name'];
			$data['last_name'] = $this->session->userdata['last_name'];
		}

		if (isset($this->session->userdata['layers']))
		{
			$user_layers = $this->session->userdata['layers'];
			foreach ($user_layers as $key => $val)
			{
				$data['_' . $key] = $val;
			}

			// Get list of layers to build
			$all_layers = $this->layer_model->get_ordered_layers();
			$layer_ids = array_intersect_key($all_layers, $user_layers);
			$a_ids = array_keys($layer_ids);
			$a_layers = $this->layer_model->get_layers($a_ids);

			// Build variables
			$data['vars'] = $this->layer_model->build_layer_vars($a_layers);

			// Build layers declaration
			$data['layers_definition'] = $this->layer_model->build_layer_definitions($a_layers);

			// Build layers list
			$data['layers_list'] = $this->layer_model->build_layers_list($a_layers);

			// Build layer nodes
			$data['layer_nodes'] = $this->layer_model->build_layer_nodes($a_layers);

			// Build feature controls
			$data['feature_controls'] = $this->layer_model->build_feature_controls($a_layers);

			// Build defaults for info windows
			$data['html_defaults'] = $this->layer_model->build_html_sets($a_layers);

			// Build display code for features
			$data['feature_displays'] = $this->layer_model->build_feature_displays($a_layers);

			// Build layers window html
			$data['layers_window_html'] = $this->layer_model->build_layer_window_htmls($a_layers);

			// Build portfolio panel
			$data['portfolio_url'] = $this->layer_model->build_portfolio_panel($a_layers, ((isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] =='on')? 'https': 'http'));
			if (!$data['portfolio_url'])
			{
				unset($data['portfolio_url']);
			}
		}

		if (isset($this->session->userdata['downloads']))
		{
			$data_map['show_download'] = 1;
			$user_downloads = $this->session->userdata['downloads'];
			foreach ($user_downloads as $key => $val)
			{
				$data_map['_' . $key] = $val;
			}
		}

		$this->load->view('default_map', $data_map);
		$this->load->view('js/mapjs', $data);
	}

	function ny()
	{
		$this->load->library('user_agent');
		if ($this->agent->is_referral())
		{
			log_message('debug', 'Referrer: ' . $this->agent->referrer());
			echo ("Your referrer url: " . $this->agent->referrer());
		}
		else
		{
			log_message('debug', 'Referrer: None');
			echo ("Your referrer url: Unknown");
		}
	}

	function ny_link()
	{
		echo "<a href='http://cleanupdeck.terradex.com/map/ny'>http://cleanupdeck.terradex.dev/map/ny</a>";
	}

        
      
	//https://cleanupdeck.terradex.com/map/open_new_map_ny/bcp/C704032
	function open_new_map_ny($progno_type, $prog_no)
	{
		// TODO: Check referrer domain before allowing authentication
		// Currently set to log their referrer domain to be sure we
		// know what to use so they don't have any problems getting in at first
		// implementation.
		$this->load->library('user_agent');
		log_message('debug', 'Referrer: ' . $this->agent->referrer());


		// Auto login user as NYS user
		$pass = '855nysdec2013';
		$user = 'internal_nysdec@terradex.com';

		$this->load->model('User_model');
		$login_result = $this->User_model->login($user, $pass);


		//NOTE" progno type is for future, to have unique progno, might require progno_type
		// but we have currently progno_type not in database (all our progno are unique!)

		$table = 'ny_remediation_view';
		$where_column = 'sourceid';
		$where_datatype = 'var';

		$location_id = $prog_no;
		$and_where = $progno_type; // Read NOTE above!
		$geo_column = 'geom';

		$pin = 'true';
		$basemap = 'imagery'; //or 'streets'; or 'imagery';
		$zoom = 16;

		Map::open_new_map($table, $where_column, $where_datatype, $location_id, $geo_column, $zoom, $pin, $basemap);
	}
        
        
          //http://cleanupdeck.terradex.dev/map/open_new_map_bc/1025
        //https://cleanupdeck.terradex.com/map/open_new_map_bc/1025
	function open_new_map_bc($id)
	{
		// TODO: Check referrer domain before allowing authentication
		// Currently set to log their referrer domain to be sure we
		// know what to use so they don't have any problems getting in at first
		// implementation.
		$this->load->library('user_agent');
		log_message('debug', 'Referrer: ' . $this->agent->referrer());


		// Auto login user as BCSA user
		$pass = 'ruby1409';
		$user = 'colin@terradex.com';

		$this->load->model('User_model');
		$login_result = $this->User_model->login($user, $pass);


		$table = 'projects_bcsa_view';
		$where_column = 'id';
		$where_datatype = 'none';

		$location_id = $id;
	
		$geo_column = 'geom';

		$pin = 'true';
		$basemap = 'street'; //or 'streets'; or 'imagery';
		$zoom = 14;

		Map::open_new_map($table, $where_column, $where_datatype, $location_id, $geo_column, $zoom, $pin, $basemap);
	}

    //============================================================================    
        // DO NOT CHANGE BELOW IS CALLED BY ABOVE CONTROLLERS 
	//https://cleanupdeck.terradex.com/map/open_new_map/ny_remediation_view/sourceid/var/C704032/geom/true/imagery
	function open_new_map($table, $where_column, $where_datatype, $location_id, $geo_column, $zoom, $pin, $basemap)
	{
		// $basemap='streets';//imagery';


		$this->load->model('map_model');

		$data = $this->map_model->get_map_data($table, $where_column, $where_datatype, $location_id, $geo_column);
		$lat = $data['lat'];
		$lon = $data['lon'];
		// $pin='true';

		$layer = "pgterradex:" . $table;

		if (!empty($data))
		{
			//$url = "?southPanel_collapsed=true&map_x=" . $data[0]['X'] . "&map_y=" . $data[0]['Y'] . "&map_zoom=" . $zoom . "&map_visibility_OpenLayers_Layer_WMS_66=true&map_opacity_OpenLayers_Layer_WMS_66=1&map_visibility_OpenLayers_Layer_WMS_60=true&map_opacity_OpenLayers_Layer_WMS_60=1&map_visibility_OpenLayers_Layer_WMS_62=true&map_opacity_OpenLayers_Layer_WMS_62=1&map_visibility_OpenLayers_Layer_Markers_70=true&map_opacity_OpenLayers_Layer_Markers_70=1&map_visibility_OpenLayers_Layer_Markers_72=true&map_opacity_OpenLayers_Layer_Markers_72=1&map_visibility_OpenLayers_Layer_Vector_74=true&map_opacity_OpenLayers_Layer_Vector_74=1";
			$url = "https://cleanupdeck.terradex.com/?lat=$lat&lon=$lon&zoom=$zoom&pin=$pin&basemap=$basemap&layer=$layer&target=new";
		}
		else
		{
			$url = 'https://cleanupdeck.terradex.com/';
		}

		// redirect(base_url($url));

		header("Location:" . $url);
	}
        
  //========= DO NOT CHANGE ABOVE =============================================================== 
  //
  //
	//https://cleanupdeck.terradex.com/map/open_new_map_plume/bcp/1015
	function open_new_map_plume($progno_type, $id)
	{
		// TODO: Check referrer domain before allowing authentication
		// Currently set to log their referrer domain to be sure we
		// know what to use so they don't have any problems getting in at first
		// implementation.
		$this->load->library('user_agent');
		log_message('debug', 'Referrer: ' . $this->agent->referrer());


		// Auto login user as Palo Alto user
		$pass = 'paloalto2';
		$user = 'paloalto@terradex.net';

		$this->load->model('User_model');
		$login_result = $this->User_model->login($user, $pass);


		//NOTE" progno type is for future, to have unique progno, might require progno_type
		// but we have currently progno_type not in database (all our progno are unique!)
                
               $table='groundwater_plumes';
               $where_column='plumeid';
               $where_datatype='int4';
	       $location_id = $id;//NOTE: is the var
		//$and_where =$progno_type; // Read NOTE above!
		$geo_column = 'the_geom';

		$pin = 'true';
		$basemap = 'streets'; //or 'streets';
		$zoom = 15;

		Map::open_new_map($table, $where_column, $where_datatype, $location_id, $geo_column, $zoom, $pin, $basemap);
	}
// function end
}
