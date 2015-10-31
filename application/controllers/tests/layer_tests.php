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
class Layer_tests extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->library('unit_test');
		$this->load->model('layer_model');

		$val = $this->layer_model->get_layer(103);
		$this->unit->run($val, "is_object", "get_layer: Received layer 103");

		$val = $this->layer_model->get_ordered_layers();
		$this->unit->run($val, 'is_array', 'get_ordered_layers: Got array of layers');

		$ids = array_keys($val);
		$a_layers = $this->layer_model->get_layers($ids);
		$this->unit->run($a_layers, 'is_array', 'get_layers: Got array of layers with an array of ids');

		$val = $this->layer_model->build_layer_definitions($a_layers);
		$this->unit->run($val, 'is_string', 'build_layer_definitions: Got js for building layers');

		$val = $this->layer_model->build_layer_vars($a_layers);
		$this->unit->run($val, 'is_string', 'build_layer_vars: Got var declarations for layers');

		$val = $this->layer_model->build_feature_controls($a_layers);
		$this->unit->run($val, 'is_string', 'build_feature_controls: Got features controls for layers');

		$val = $this->layer_model->build_feature_displays($a_layers);
		$this->unit->run($val, 'is_string', 'build_feature_display: Got features display for layers');

		$val = $this->layer_model->build_html_sets($a_layers);
		$this->unit->run($val, 'is_string', 'build_html_sets: Got setting feature display html for layers');

		$val = $this->layer_model->build_layer_window_htmls($a_layers);
		$this->unit->run($val, 'is_string', 'build_layer_window_htmls: Got feature window html for layers');

		$val = $this->layer_model->build_layer_nodes($a_layers);
		$this->unit->run($val, 'is_string', 'build_layer_nodes: Got js for handling layer nodes for layers');

		print_r($val);

		echo $this->unit->report();
	}

}
