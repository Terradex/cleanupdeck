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
 */
class User extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    function login()
    {
        $this->load->helper('url');
        $this->load->model('User_model');

        // This is only for development to bypass login when configuration to mssql could
        // not be done successfully.
        if (defined('TEST_USERNAME') AND ENVIRONMENT == 'development')
        {
            $login_result = array(
                'email' => TEST_USERNAME,
                'UserID2' => TEST_USERID,
                'FName' => TEST_FIRSTNAME,
                'LName' => TEST_LASTNAME
            );
        }
        else
        {
            $login_result = $this->User_model->login($this->input->post('loginUsername'), $this->input->post('loginPassword'));
        }

        if ($login_result != FALSE)
        {

            $output = array(
                'success' => true
            );

            // If .NET login payload is requested, generate and pass back. //
            if ($this->input->post('signon_landwatch') == 1)
            {
                $this->load->model('Crypto_model');
                $payload = $this->input->post('loginUsername') . "," . (time() + 7200);
                $encrypted = $this->Crypto_model->encrypt_dotnet($payload);
                $output['message'] = $encrypted;

                // Set cookie //
                $cookie = array(
                    'name' => 'user',
                    'value' => $encrypted,
                    'expire' => '7200',
                    'path' => '/',
                    'prefix' => 'ter_'
                );

                $this->input->set_cookie($cookie);
            }

            echo json_encode($output);
        }
        else
        {
            $this->logout();
            $output = array(
                'success' => false,
                'errors' => array(
                    'reason' => 'Login failed. Try again'
                )
            );
            echo json_encode($output);
        }
    }

    function logout()
    {
        $this->session->sess_destroy();

        // Destroy cookie //
        $cookie = array(
            'name' => 'user',
            'value' => '',
            'expire' => '',
            'path' => '/',
            'prefix' => 'ter_'
        );

        $this->input->set_cookie($cookie);
    }

    function test()
    {
        echo "success";
    }

}
