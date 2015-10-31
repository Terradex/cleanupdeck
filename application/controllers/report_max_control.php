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
class Report_max_control extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }
// http://cleanupdeck.terradex.dev/report_max_control/co_report_viewcontrol/3237/monitoring_report
//   http://cleanupdeck.terradex.dev/report_max_control/co_report_viewcontrol/3237
    function co_report_viewcontrol($siteid,$viewoption='default')
    {
        $this->load->library('session');
        $this->load->model('co_report_model');

        $this->load->model('interaction_digclean_model');

        $site_result = $this->co_report_model->get_site_info($siteid);

        if (empty($site_result)):
            {
                echo"EXIT ERROR SiteID..." . $siteid . "  does not exist!! Go Back";
                exit;
            } endif;

        $site_docs = $this->co_report_model->get_docs_site($siteid);


        // $images = $this->co_report_model->get_images($siteid);

        $parcel_result = $this->co_report_model->get_parcel_info($siteid);
        //$zone_result = $this->co_report_model->get_zone_info($siteid);
        $zones_result = $this->co_report_model->get_zone_info2($siteid);
        $zone_docs = $this->co_report_model->get_docs_zone_many($siteid);

        //9-1   $elemclass='objective'; // needed only  2D version to limit SELECT
        // 9-1 $elements_result_2D = $this->co_report_model->get_elementbyzone_info($siteid, $elemclass);

        $elements_result_3D = $this->co_report_model->get_elementbyzonebyclass_info($siteid);

        // $parcelmap_result = $this->co_report_model->get_parcelmap_img($siteid);
        $assessment_result = $this->co_report_model->get_assessment($siteid);



        // $assessmentdetails_result = $this->co_report_model->get_assessment_details($assessid);
      // 9-7-12 not needed with iFrams   $alerts_result = $this->co_report_model->get_alerts($siteid);

        $listed_docs = $this->co_report_model->get_documents_forlisting($siteid);
        $site_users = $this->co_report_model->get_site_users($siteid);


 // 9-7-12 not needed with iFrams      $live_alert_result= $this->co_report_model->get_live_alert_count($siteid);
    //  $companycharts_result= $this->co_report_model->get_company_reportcharts($siteid);

       $digcleanids_result= $this->interaction_digclean_model->get_digclean_ids($siteid);



        // NO OTHER DATA CAN BE ABOVE< need to define the array first here!
        $data = $site_result[0];

         // Pass in user ID of logged on user  //
         $data['user_id'] = $this->session->userdata('user_id');


        $data['parcels'] = $parcel_result;
        $data['zones'] = $zones_result;
        // $data['parcelmap'] = $parcelmap_result;
        $data['assessment'] = $assessment_result;
        //$data['assessmentdetails'] = $assessmentdetails_result;
        // 9-7-12 not needed with iFrams $data['alerts'] = $alerts_result;
        // $data['images'] = $images;
        // $data['elements2D'] = $elements_result_2D;
        $data['elements3D'] = $elements_result_3D;
        $data['sitedocs'] = $site_docs;
        $data['zonedocs'] = $zone_docs;
        $data['listed_docs'] = $listed_docs;
        $data['site_users'] = $site_users;


       // 9-7-12 not needed with iFrams $data['live_alert'] = $live_alert_result;
       // $data['charts'] = $companycharts_result;
        $data['digcleanids'] = $digcleanids_result;

 



        //NOTE: the order of the data does matter, on page can not use data unles was provided in earlier query!
        $assessid = Report_max_control::get_mostrecent_assessmentid($siteid);

        if (isset($assessid))
        {
            $assessmentdetails_result = $this->co_report_model->get_assessment_details($assessid);
            $data['assessmentdetails'] = $assessmentdetails_result;
        }
        else
        {
            echo"";
        }

        // Pass in user ID was line 110 //
        $data['user_id'] = $this->session->userdata('user_id');


    /*  5/25  echo"line controller 109 <br/><br/>";
                print_r($data['live_alert']);
          echo"<br/><br/>";
                 
                  print_r($data['companycharts']);
          echo"<br/><br/>";
                  print_r($data['digcleanids']);
          echo"<br/><br/>";
      */

        IF ($viewoption=='monitoring_report')
        {
            $this->load->view('co_report_monitoring_report_v2', $data);
        }

 else {
    $this->load->view('co_report_v6', $data);
}
        
    }

  


//http://cleanupdeck.terradex.dev/report_max_control/co_max_report_v1_manualdataentry/3237
    function co_max_report_v1_manualdataentry($siteid=0)
    {

             // user input data
      $siteid = $_POST['field_01'];

       // userid need to validate correctly see under user section
       if($siteid=='Enter SiteD' ):

            {
            echo"EXIT ERROR <br/>
                SiteID ..\"".$siteid."\"  was not entered correctly! <br/> Go Back";
            exit;
            } endif;

      Report_max_control::co_report_viewcontrol($siteid);
    }
//======================================================================
    //sub function
    function get_mostrecent_assessmentid($siteid)
    {

        // database connection- model type stuff
        $msterradex = $this->load->database('msterradex', TRUE);
        $this_assessmentid = (!isset($siteid) ? '3237' : $siteid);



        $query_select_assessmentid = "
                    SELECT    MAX(AssessID) AS AssessID
                    FROM         Assessment
                    WHERE     (SiteID = $siteid) AND (AssessStatus ='active')

          ";

        $query1 = $msterradex->query($query_select_assessmentid);

        foreach ($query1->result() as $row)
        {
            $this_assessmentid = $row->AssessID;
        }
        //option 1 works
        //    echo  (!isset($this_assessmentid)?'0':$this_assessmentid);
        //echo $this_assessmentid; */
        return $this_assessmentid;
    }

// end function
//   =========== My Sites Page===========================================
    //Jane Bohn 1527 Lance 1321
   // http://cleanupdeck.terradex.dev/report_max_control/co_report_vc_mysites/1203
    function co_report_vc_mysites($userid)
    {
        $this->load->model('co_report_model');

        $mysites_return = $this->co_report_model->get_mysites($userid);

        $data['mysites'] = $mysites_return;

        $this->load->view('co_report_mysites', $data);
    }

//end function
    //   =========== My Alerts Page===========================================
    //Jane Bohn 1527 Lance 1321
    //http://cleanupdeck.terradex.dev/report_max_control/co_report_vc_myalerts/1321
    function co_report_vc_myalerts($userid, $status="")
    {
        $this->load->model('co_report_model');

        $myalerts_return = $this->co_report_model->get_myalerts($userid, $status);

        $data['myalerts'] = $myalerts_return;
        $data['status'] = $status;
        $data['userid'] = $userid;

        $this->load->view('co_report_myalerts', $data);
    }

  //   =========== My Mgt Reports===========================================
    //
    //http://cleanupdeck.terradex.dev/report_max_control/co_report_mgt_reports/3237
    function co_report_mgt_reports($siteid)
    {
        $this->load->model('co_report_model');

       $companycharts_result= $this->co_report_model->get_company_reportcharts($siteid); 

         $data['charts'] = $companycharts_result;

     //echo"linecontroller224". print_r($data);

       IF( isset ($data['charts']['0']['alert_barchart_url'])
                OR   isset ($data['charts']['0']['event_barchart_url'])
               OR   isset ($data['charts']['0']['history_piechart_url'])
                OR   isset ($data['charts']['0']['oldclosurerate_piechart_url'])
               OR   isset ($data['charts']['0']['currentclosurerate_piechart_url'])
            
               )
       { $this->load->view('co_report_mgt_reports', $data);}
        ELSE {  echo "Currently no Reports available";}



       // $this->load->view('co_report_mgt_reports', $data);
    }



//end function

  
}
