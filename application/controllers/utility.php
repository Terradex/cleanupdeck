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
class Utility extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function search()
    {
        try
        {
			$this->load->model('layer_model');
			$queryable_layers = $this->layer_model->get_searchable_layers();

			$query = $this->input->post('query');
            $safequery = urlencode($query);
            $output = array();
            foreach ($queryable_layers as $queryable_layer)
            {
                if (!$this->input->post($queryable_layer))
                {
                    continue;
                }

                $url = "http://" . GEOSERVER_URL . "/geoserver/wfs?service=WFS&version=1.0.0&request=GetFeature&typeName=pgterradex:$queryable_layer&propertyName=displaypart1,displaypart2,displaypart3&outputformat=json&CQL_FILTER=searchfield%20ilike%20%27%25$safequery%25%27";
                if ($fp = fopen($url, "r"))
                {
                    $content = '';
                    while ($line = fread($fp, 1024))
                    {
                        $content .= $line;
                    }
                }
                $arrTemp = json_decode($content, TRUE);
                if (is_array($arrTemp))
                {
                    $output = array_merge($output, $arrTemp['features']);
                }
            }

            if (count($output) != 0)
            {
                $final_output = array(
                    'type' => 'FeatureCollection',
                    'features' => $output
                );
                header("Content-type: text/json");
                echo (json_encode($final_output));
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

}
