<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 * @property CI_Security $security
 */
// TODO: Remember to capitalize the name. The template could not do it.
class User_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('security');
	}

	function login($username, $password)
	{
		$username = $this->security->xss_clean($username);
		$password = $this->security->xss_clean($password);
		$ow_db = $this->load->database('csms', TRUE);

		$sql = "SELECT UserID2, email, FName, LName FROM CE_User WHERE email = ? AND PasswordSetUp = ?";
		$query = $ow_db->query($sql, array($username, $password));
		if ($query->num_rows() === 1)
		{
			$row = $query->row_array();
			$this->set_session($row);
			
			return $row;
		}
		else
		{
			return FALSE;
		}
	}

	function set_session($login_result)
	{
		try
		{
			$this->load->library('session');

			$session_data = array(
				'username' => $login_result['email'],
				'user_id' => $login_result['UserID2'],
				'first_name' => $login_result['FName'],
				'last_name' => $login_result['LName']
			);

			// Get layers //
			$layers = $this->get_user_layers($login_result['UserID2']);
			foreach ($layers as $layer)
			{
				$session_layers[$layer->layer_id] = 1;
				if ($layer->download == 1)
				{
					$session_downloads[$layer->layer_id . 'd'] = 1;
				}
			}
			$session_data['layers'] = $session_layers;
			if (isset($session_downloads))
			{
				$session_data['downloads'] = $session_downloads;
			}

			$this->session->set_userdata($session_data);
		}
		catch (Exception $exc)
		{
			echo $exc->getTraceAsString();
		}
	}

	function get_user_layers($user_id)
	{
		$default_db = $this->load->database('default', TRUE);
		$user_id = $this->security->xss_clean($user_id);

		$sql = "SELECT layer_id, download FROM user_layers WHERE user_id = ?";
		$query = $default_db->query($sql, array($user_id));

		return $query->result();
	}

}
