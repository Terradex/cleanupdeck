<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Email $email
 * @property CI_DB_active_record $db
 */
class Target extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function map_icplume($zoom =15, $lat=37.43841, $long=-122.15909, $user='none', $password='none')
	{
		$this->load->helper('url');
		$this->load->model('coord_model');
		$this->load->model('User_model');

		$data = $this->coord_model->get_xy_mercator_latlong($lat, $long);

		$user = str_replace('%40', '@', $user);

		/// Log me on now!
		$login_result = $this->User_model->login($user, $password);

		// Redirect to home if login failed
		if (!$login_result)
		{
			redirect(base_url());
		}

		// Set session
		$this->User_model->set_session($login_result);

		// Redirect appropriately now that we are logged in
		if (!empty($data))
		{
			$url = "?southPanel_collapsed=true&map_x=" . $data[0]['X'] . "&map_y=" . $data[0]['Y'] . "&map_zoom=" . $zoom . "&map_visibility_OpenLayers_Layer_WMS_66=true&map_opacity_OpenLayers_Layer_WMS_66=1&map_visibility_OpenLayers_Layer_WMS_60=true&map_opacity_OpenLayers_Layer_WMS_60=1&map_visibility_OpenLayers_Layer_WMS_62=true&map_opacity_OpenLayers_Layer_WMS_62=1&map_visibility_OpenLayers_Layer_Markers_70=true&map_opacity_OpenLayers_Layer_Markers_70=1&map_visibility_OpenLayers_Layer_Markers_72=true&map_opacity_OpenLayers_Layer_Markers_72=1&map_visibility_OpenLayers_Layer_Vector_74=true&map_opacity_OpenLayers_Layer_Vector_74=1";
			redirect(base_url($url));
		}
		else
		{
			redirect(base_url());
		}
	}

}
