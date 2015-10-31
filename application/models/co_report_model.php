<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 * @property CI_Security $security
 */
class Co_report_model extends CI_Model
{

    private $msterradex;

    function __construct()
    {
        parent::__construct();
        $this->msterradex = $this->load->database('msterradex', TRUE);
        //$this->pgterradex = $this->load->database('pgterradex', TRUE);
        
        //open database
        //$msterradex = $this->load->database('msterradex', TRUE);
        // $pgterradex = $this->load->database('pgterradex', TRUE);
    }

    // =====================================================
    // 5/24/12 v3 Version get Site Level Information
    function get_site_info($siteid)
    {

        //echo"check if siteid is pass in  site id".$siteid;
        try
        {


            $sql = '

         SELECT     TOP (1) Site.SiteID,
                    Site.SiteName,
                    Site.SiteStreet,
                    Site.SiteCity,
                    Site.SiteState,
                    Site.PayingParty,
                    Site.PayingParty_short,
                    
              -- took out 9/7/12 not needed anymore
              --(CASE WHEN Site.Chart_Pie_Event_URL IS NULL THEN "no_data" ELSE Site.Chart_Pie_Event_URL END) AS Chart_Pie_Event_URL,
               --    (CASE WHEN Site.Chart_Pie_Alert_URL IS NULL THEN "no_data" ELSE Site.Chart_Pie_Alert_URL END) AS Chart_Pie_Alert_URL,

                    (CASE WHEN Site.ClientReferenceSiteID IS NULL
                                          THEN "" ELSE Site.ClientReferenceSiteID END) AS ClientReferenceSiteID,
                    Site.Count_SiteAssessment,
                    Organization.OrgName
                    FROM         Site INNER JOIN
                                          TransactionSiteOrganization ON Site.SiteID = TransactionSiteOrganization.SiteID INNER JOIN
                                          Organization ON TransactionSiteOrganization.OrgID = Organization.OrgID
                    WHERE     (Site.SiteID = ?)

  ';

            $query = $this->msterradex->query($sql, array($siteid));
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    // get Site Level Information
    function get_parcel_info($siteid =3237)
    {
        try
        {


            $sql = '
                    SELECT     Location.BelongsToID AS ZoneID,
                    Location.LocationAPN,
                    Location.LocationStreet,
                    Location.LocationCity,
                    Location.LocationCountry AS LocationCounty,
                    Location.LocationState,
                    Location.LocationDescription
                    FROM         Location INNER JOIN
                         TransZoneSite ON Location.BelongsToID = TransZoneSite.ZoneID
                    WHERE     (Location.BelongsToType = "zone")
                    AND (TransZoneSite.SiteID = ? )
                    AND (Location.LocType_S = "parcel")
                    AND (Location.LocStatus = "active")
                    ORDER BY ZoneID
  ';

            $query = $this->msterradex->query($sql, array($siteid));
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    // get zone Level Information
    function get_zone_info($siteid =3237)
    {
        try
        {


            $sql = '
                    SELECT
                    Element.ElemID,
                    Element.ZoneID,
                    Element.ElemClassText,
                    Element.ElemTypeText,
                    Element.ElemDetail
FROM         Element INNER JOIN
                     TransZoneSite ON Element.ZoneID = TransZoneSite.ZoneID
WHERE     (TransZoneSite.SiteID = ?)
AND (Element.ElemStatus = "active")
ORDER BY Element.ZoneID, Element.ElemClass_S DESC, Element.ElemType_S
  ';

            $query = $this->msterradex->query($sql, array($siteid));
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
//Used in v4 AND iPad co_investigation_ipad_input_v2
    // get all zone Level Information, across a wide range of tables,
    // as well as subarray with the list of ZoneID's
    function get_zone_info2($siteid)
    {
        try
        {


            $sql = "
                        SELECT
                        Zone.ZoneID,
                         Zone.SiteID,
                         (CASE WHEN Element.ElemID IS NULL THEN '0' ELSE Element.ElemID END ) AS ElemID,
                        Zone.ZoneNotes,
                         Zone.ChemicalsofConcern,
                         Zone.ZoneName,
                        Zone.ClientReferenceZoneID,
                         (CASE WHEN Zone.EffectiveDate IS NULL  THEN '' ELSE CONVERT (varchar(10), Zone.EffectiveDate,120) END) AS EffectiveDate, Zone.Duration,
                         Zone.ZoneStatus,

                        Zone.Count_ZoneDetailDoc,
                        Zone.Count_ZoneImage,
                        Zone.Count_ZoneDetailDoc,
                        Element.ElemClass_S,
                        Element.ElemClassText,
                        Element.ElemGroup_S,
                        Element.ElemGroupText,
                        Element.ElemType_S,
                        Element.ElemTypeText,
                        Element.ElemDetail,
                        Element.NextDateType,
                        (CASE WHEN Element.NextDate IS NULL  THEN ''ELSE CONVERT (varchar(10), Element.NextDate,120) END) AS NextDate,
                        Element.ElemStatus,

               (CASE WHEN Zone.Count_ActivityInvestigation IS NULL  THEN '0'ELSE Zone.Count_ActivityInvestigation END ) AS Count_ActivityInvestigation ,
               (CASE WHEN Zone.Count_ActivityMonitoring IS NULL  THEN '0'ELSE Zone.Count_ActivityMonitoring END ) AS Count_ActivityMonitoring ,
               (CASE WHEN Zone.Count_Media IS NULL  THEN '0'ELSE Zone.Count_Media END ) AS Count_Media ,
               (CASE WHEN Zone.Count_Objective IS NULL  THEN '0'ELSE Zone.Count_Objective END ) AS Count_Objective ,
              (CASE WHEN Zone.Count_ZoneType IS NULL  THEN '0'ELSE Zone.Count_ZoneType END ) AS Count_ZoneType

                    FROM         Site INNER JOIN
                                          Zone ON Site.SiteID = Zone.SiteID LEFT OUTER JOIN
                                          Element ON Zone.ZoneID = Element.ZoneID
                    WHERE     (Site.ActivationStatus = 'active')
                            AND (Zone.ZoneStatus = 'active')
                            AND (Element.ElemStatus = 'active')
                            AND (Element.ElemClass_S = 'ZoneType')
                            AND (Site.SiteID= $siteid)
                            ORDER BY  Zone.ZoneID
                      ";

            $query = mssql_query($sql);

            if (!mssql_num_rows($query))
            {
                echo '';
            }
            else
            {
                $zonetable = array();

                while ($row = mssql_fetch_assoc($query))
                {
                    // echo '<li>' . $row['ZoneID'] . ' ' . $row['ElemID']  .' ' . $row['ElemClassText'] .' ' . $row['ElemTypeText'] .' ' . $row['ElemDetail'] . '</li>';
                    // build array of arrayes,  where Index is the ZoneID
                    //Array ( [71171] => Array ( [ZoneID] => 71171 [ElemID] => 68400 [ElemClassText] => ZoneType ...

                    $zonetable [$row['ZoneID']] = array(
                        'ZoneID' => $row['ZoneID'],
                        'SiteID' => $row['SiteID'],
                        'ElemID' => $row['ElemID'],
                        'ZoneNotes' => $row['ZoneNotes'],
                        'ChemicalsofConcern' => $row['ChemicalsofConcern'],
                        'ZoneName' => $row['ZoneName'],
                        'ClientReferenceZoneID' => $row['ClientReferenceZoneID'],
                        'Zone_EffectiveDate' => $row['EffectiveDate'],
                        'Count_ZoneDetailDoc' => $row['Count_ZoneDetailDoc'],
                        'Count_ZoneImage' => $row['Count_ZoneImage'],
                        'Count_ZoneDetailDoc' => $row['Count_ZoneDetailDoc'],
                        'Zone_ElemClass_S' => $row['ElemClass_S'],
                        'Zone_ElemClassText' => $row['ElemClassText'],
                        'Zone_ElemGroup_S' => $row['ElemGroup_S'],
                        'Zone_ElemGroupText' => $row['ElemGroupText'],
                        'Zone_ElemType_S' => $row['ElemType_S'],
                        'Zone_ElemTypeText' => $row['ElemTypeText'],
                        'Zone_ElemDetail' => $row['ElemDetail'],
                        'Zone_NextDateType' => $row['NextDateType'],
                        'Zone_NextDate' => $row['NextDate'],
                        'Zone_ElemStatus' => $row['ElemStatus'],
                        'Count_ActivityInvestigation' => $row['Count_ActivityInvestigation'],
                        'Count_ActivityMonitoring' => $row['Count_ActivityMonitoring'],
                        'Count_Media' => $row['Count_Media'],
                        'Count_Objective' => $row['Count_Objective'],
                        'Count_ZoneType' => $row['Count_ZoneType']
                    );

                    $zoneid_array[] = $row['ZoneID']; // creates Array ( [0] => 71171 [1] => 194166 [2] => 194167 )
                } // end while
                $zonetable['zoneid_array'] = $zoneid_array; // add this to the table
            } // end else of main block
            //
            //QC
            //print_r($zonetable);
            //  print_r ($zoneid_array);
            //  echo "===================<br/>";
            //  print_r( $zonetable['zoneid_array']);  // same output as print_r ($zoneid_array);
            //  echo "===================<br/>";
            //echo"<br/> selected item from a specific zone: ".$zonetable[71171]['ZoneID']."is ".$zonetable[71171]['Zone_ElemTypeText'];
            return $zonetable;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
//=====================================================
//EXPERIMTENTAL DELETE IF NOT USED!!!
    // get all zone Level Information, across a wide range of tables,
    // as well as subarray with the list of ZoneID's
    function get_element_info($siteid, $elemclass)
    {
        try
        {
            $sql = "
                    SELECT
                        Element.ElemID,
                        Element.ZoneID,
                        Element.ElemClass_S,
                        Element.ElemClassText,
                        Element.ElemGroup_S,
                        Element.ElemGroupText,
                        Element.ElemType_S,
                        Element.ElemTypeText,
                        Element.ElemDetail,
                        Element.NextDateType,
                        (CASE WHEN Element.NextDate IS NULL  THEN ''ELSE CONVERT (varchar(10), Element.NextDate,120) END) AS NextDate,
                        Element.ElemStatus

                FROM         Element INNER JOIN
                                     TransZoneSite ON Element.ZoneID = TransZoneSite.ZoneID
                WHERE     (TransZoneSite.SiteID = $siteid)
                AND (Element.ElemStatus = 'active')
                AND (Element.ElemClass_S= '$elemclass')
                ORDER BY Element.ZoneID, Element.ElemClass_S DESC, Element.ElemType_S
  ";

            $query = mssql_query($sql);

            if (!mssql_num_rows($query))
            {
                echo '';
            }
            else
            {
                $elemtable = array();

                while ($row = mssql_fetch_assoc($query))
                {
                    // echo '<li>' . $row['ElemID']  .' ' . $row['ElemClassText'] .' ' . $row['ElemTypeText'] .' ' . $row['ElemDetail'] . '</li>';
                    // build array of arrayes,  where Index is the ZoneID
                    //Array ( [71171] => Array ( [ZoneID] => 71171 [ElemID] => 68400 [ElemClassText] => ZoneType ...

                    $elemtable [$row['ElemID']] = array(
                        'ElemID' => $row['ElemID'],
                        'ZoneID' => $row['ZoneID'],
                        'ElemClass_S' => $row['ElemClass_S'],
                        'ElemClassText' => $row['ElemClassText'],
                        'ElemGroup_S' => $row['ElemGroup_S'],
                        'ElemGroupText' => $row['ElemGroupText'],
                        'ElemType_S' => $row['ElemType_S'],
                        'ElemTypeText' => $row['ElemTypeText'],
                        'ElemDetail' => $row['ElemDetail'],
                        'NextDateType' => $row['NextDateType'],
                        'NextDate' => $row['NextDate'],
                        'ElemStatus' => $row['ElemStatus']);

                    $elemid_array[] = $row['ElemID']; // creates Array ( [0] => 71171 [1] => 194166 [2] => 194167 )
                } // end while
                $elemtable['elemid_array'] = $elemid_array; // add this to the table
            } // end else of main block
            //
            //QC
            //print_r($zonetable);
            print_r($elemid_array);
            echo "===================<br/>";
            print_r($elemtable['elemid_array']);  // same output as print_r ($elemeid_array);
            echo "===================<br/>";

            //echo"<br/> selected item from a specific zone: ".$zonetable[71171]['ZoneID']."is ".$zonetable[71171]['Zone_ElemTypeText'];
            return $elemtable;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    //===================================================
//Used as 2 D Version
    // get all Element  Level Information, across a wide range of tables,
    // as well as 2 D subarray with the list of ZoneID's and second level ElemID
    function get_elementbyzone_info($siteid, $elemclass)
    {
        try
        {
            $sql = "
                    SELECT
                        Element.ElemID,
                        Element.ZoneID,
                        Element.ElemClass_S,
                        Element.ElemClassText,
                        Element.ElemGroup_S,
                        Element.ElemGroupText,
                        Element.ElemType_S,
                        Element.ElemTypeText,
                        Element.ElemDetail,
                        Element.NextDateType,
                        (CASE WHEN Element.NextDate IS NULL  THEN ''ELSE CONVERT (varchar(10), Element.NextDate,120) END) AS NextDate,
                        Element.ElemStatus

                FROM         Element INNER JOIN
                                     TransZoneSite ON Element.ZoneID = TransZoneSite.ZoneID
                WHERE     (TransZoneSite.SiteID = $siteid)
                AND (Element.ElemStatus = 'active')
               AND (Element.ElemClass_S= '$elemclass')
                ORDER BY Element.ZoneID, Element.ElemClass_S DESC, Element.ElemType_S
  ";

            $query = mssql_query($sql);

            if (!mssql_num_rows($query))
            {
                echo '';
            }
            else
            {
                $elemtable = array();
                $elemtable_2D = array();


                while ($row = mssql_fetch_assoc($query))
                {
                    // echo '<li>' . $row['ElemID']  .' ' . $row['ElemClassText'] .' ' . $row['ElemTypeText'] .' ' . $row['ElemDetail'] . '</li>';
                    // build array of arrayes,  where Index  is the ElemID
                    //Array ( [68410] => Array ( [ElemID] => 68410 [ZoneID] => 71171 [ElemClass_S] => Media [ElemClassText] => Media [ElemGroup_S] => [ElemGroupText] => [ElemType_S] => soil [ElemTypeText] => Soil [ElemDetail] => Subsurface soil

                    $elemtable [$row['ElemID']] = array(
                        'ElemID' => $row['ElemID'],
                        'ZoneID' => $row['ZoneID'],
                        'ElemClass_S' => $row['ElemClass_S'],
                        'ElemClassText' => $row['ElemClassText'],
                        'ElemGroup_S' => $row['ElemGroup_S'],
                        'ElemGroupText' => $row['ElemGroupText'],
                        'ElemType_S' => $row['ElemType_S'],
                        'ElemTypeText' => $row['ElemTypeText'],
                        'ElemDetail' => $row['ElemDetail'],
                        'NextDateType' => $row['NextDateType'],
                        'NextDate' => $row['NextDate'],
                        'ElemStatus' => $row['ElemStatus']);

                    // Build Array with Index ElemID, then with INdex of ZoneID, where that one includes the same data as above
                    //Array ( [68410] => Array ( [71171] => Array ( [ElemID] => 68410 [ZoneID] => 71171 [ElemClass_S] => Media [ElemClassText] => Media [ElemGroup_S] => [ElemGroupText] => [ElemType_S] => soil [ElemTypeText] => Soil

                    $elemtable_2D [$row['ElemID']] [$row['ZoneID']] = array(
                        'ElemID' => $row['ElemID'],
                        'ZoneID' => $row['ZoneID'],
                        'ElemClass_S' => $row['ElemClass_S'],
                        'ElemClassText' => $row['ElemClassText'],
                        'ElemGroup_S' => $row['ElemGroup_S'],
                        'ElemGroupText' => $row['ElemGroupText'],
                        'ElemType_S' => $row['ElemType_S'],
                        'ElemTypeText' => $row['ElemTypeText'],
                        'ElemDetail' => $row['ElemDetail'],
                        'NextDateType' => $row['NextDateType'],
                        'NextDate' => $row['NextDate'],
                        'ElemStatus' => $row['ElemStatus']);





                    $elemid_array[] = $row['ElemID']; // creates Array of just the elementid's ( [0] => 71171 [1] => 194166 [2] => 194167 )
                    //  $elementclass_array=array('Objective', 'Media','ActivityInvestigation','ActivityMonitoring');
                    //$elementclass_array=array('ActivityMonitoring');
                } // end while
                $elemtable_2D['elemid_array'] = $elemid_array; // add this to the table
                //   $elemtable_3D['elementclass_array']= $elementclass_array;
            } // end else of main block
            //
            //QC Begin (nice print outs to see what is happening !
            /*
              echo " <br/><br/> by ElementID <br/>";
              print_r($elemtable);

              echo "<br/><br/>== 2D  ElemID and ZoneID===<br/>";
              print_r($elemtable_2D);
             *
             *
              echo "<br/><br/>== 3D  ElemID and ZoneID===<br/>";
              print_r($elemtable_3D);


              print_r ($elemid_array);
              echo "===================<br/>";

              print_r( $elemtable_2D['elemid_array']);  // same output as print_r ($elemeid_array);
              echo "===================<br/>";

              //echo"<br/> selected item from a specific zone: ".$zonetable[71171]['ZoneID']."is ".$zonetable[71171]['Zone_ElemTypeText'];
             */  // QC End
            //print_r($elemtable_3D);

            return $elemtable_2D;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    // Used as 3 D Version, which is used for report
    // note this is stripped down version of the GREAT demo below function get_elementbyzone_info_3D($siteid, $elemclass)
    function get_elementbyzonebyclass_info($siteid)
    {
        try
        {
            $sql = "
                    SELECT
                        Element.ElemID,
                        Element.ZoneID,
                        Element.ElemClass_S,
                        Element.ElemClassText,
                        Element.ElemGroup_S,
                        Element.ElemGroupText,
                        Element.ElemType_S,
                        Element.ElemTypeText,
                        Element.ElemDetail,
                        Element.NextDateType,
                        (CASE WHEN Element.NextDate IS NULL  THEN ''ELSE CONVERT (varchar(10), Element.NextDate,120) END) AS NextDate,
                        Element.ElemStatus

                FROM         Element INNER JOIN
                                     TransZoneSite ON Element.ZoneID = TransZoneSite.ZoneID
                WHERE     (TransZoneSite.SiteID = $siteid)
                AND (Element.ElemStatus = 'active')
             
                ORDER BY Element.ZoneID, Element.ElemClass_S DESC, Element.ElemType_S
  ";

            $query = mssql_query($sql);

            if (!mssql_num_rows($query))
            {
                echo '';
            }
            else
            {

                $elemtable_3D = array();

                while ($row = mssql_fetch_assoc($query))
                {
                    // echo '<li>' . $row['ElemID']  .' ' . $row['ElemClassText'] .' ' . $row['ElemTypeText'] .' ' . $row['ElemDetail'] . '</li>';



                    $elemtable_3D [$row['ElemID']] [$row['ZoneID']] [$row['ElemClass_S']] = array(
                        'ElemID' => $row['ElemID'],
                        'ZoneID' => $row['ZoneID'],
                        'ElemClass_S' => $row['ElemClass_S'],
                        'ElemClassText' => $row['ElemClassText'],
                        'ElemGroup_S' => $row['ElemGroup_S'],
                        'ElemGroupText' => $row['ElemGroupText'],
                        'ElemType_S' => $row['ElemType_S'],
                        'ElemTypeText' => $row['ElemTypeText'],
                        'ElemDetail' => $row['ElemDetail'],
                        'NextDateType' => $row['NextDateType'],
                        'NextDate' => $row['NextDate'],
                        'ElemStatus' => $row['ElemStatus']);

                    $elemid_array[] = $row['ElemID']; // creates Array of just the elementid's ( [0] => 71171 [1] => 194166 [2] => 194167 )
                } // end while

                $elemtable_3D['elemid_array'] = $elemid_array; // add this to the table
                //  echo"modelline 551 ".print_r($elemtable_3D);
                //
                // for 3D model inner cycle
                $elementclass_array_all = array('Objective', 'Media', 'ActivityInvestigation', 'ActivityMonitoring');


                $elemtable_3D['elementclass_array_all'] = $elementclass_array_all;
                //  echo"modelline 558 ".print_r($elemtable_3D['elemid_array']);
            } // end else of main block
            //
            //QC  View Array  Begin (nice print outs to see what is happening !
            /*
              echo "<br/><br/>== 3D  Array Print Out ===<br/>";
              print_r($elemtable_3D);

              print_r ($elemid_array);
              echo "===================<br/>";


              //echo"<br/> selected item from a specific zone: ".$zonetable[71171]['ZoneID']."is ".$zonetable[71171]['Zone_ElemTypeText'];

              print_r($elementclass_array_all);
              echo "===================<br/>";
              print_r($elemtable_3D['elementclass_array_all']);
              echo "===================<br/>";

             */  // QC End



            return $elemtable_3D;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //====================================
    ////CORE EXAMPLE FOR DEMOS  1D, 2D and 3D Arrays!!!
    //// Not used in application
    //Works for 3 D testing !!
    // get all Element  Level Information, across a wide range of tables,
    // as well as 2 D subarray with the list of ZoneID's and second level ElemID

    function get_elementbyzone_info_3D($siteid, $elemclass)
    {
        try
        {
            $sql = "
                    SELECT
                        Element.ElemID,
                        Element.ZoneID,
                        Element.ElemClass_S,
                        Element.ElemClassText,
                        Element.ElemGroup_S,
                        Element.ElemGroupText,
                        Element.ElemType_S,
                        Element.ElemTypeText,
                        Element.ElemDetail,
                        Element.NextDateType,
                        (CASE WHEN Element.NextDate IS NULL  THEN ''ELSE CONVERT (varchar(10), Element.NextDate,120) END) AS NextDate,
                        Element.ElemStatus

                FROM         Element INNER JOIN
                                     TransZoneSite ON Element.ZoneID = TransZoneSite.ZoneID
                WHERE     (TransZoneSite.SiteID = $siteid)
                AND (Element.ElemStatus = 'active')
              --  AND (Element.ElemClass_S= '$elemclass')
                ORDER BY Element.ZoneID, Element.ElemClass_S DESC, Element.ElemType_S
  ";

            $query = mssql_query($sql);

            if (!mssql_num_rows($query))
            {
                echo '';
            }
            else
            {
                $elemtable = array();
                $elemtable_2D = array();
                $elemtable_3D = array();

                while ($row = mssql_fetch_assoc($query))
                {
                    // echo '<li>' . $row['ElemID']  .' ' . $row['ElemClassText'] .' ' . $row['ElemTypeText'] .' ' . $row['ElemDetail'] . '</li>';
                    // build array of arrayes,  where Index  is the ElemID
                    //Array ( [68410] => Array ( [ElemID] => 68410 [ZoneID] => 71171 [ElemClass_S] => Media [ElemClassText] => Media [ElemGroup_S] => [ElemGroupText] => [ElemType_S] => soil [ElemTypeText] => Soil [ElemDetail] => Subsurface soil

                    $elemtable [$row['ElemID']] = array(
                        'ElemID' => $row['ElemID'],
                        'ZoneID' => $row['ZoneID'],
                        'ElemClass_S' => $row['ElemClass_S'],
                        'ElemClassText' => $row['ElemClassText'],
                        'ElemGroup_S' => $row['ElemGroup_S'],
                        'ElemGroupText' => $row['ElemGroupText'],
                        'ElemType_S' => $row['ElemType_S'],
                        'ElemTypeText' => $row['ElemTypeText'],
                        'ElemDetail' => $row['ElemDetail'],
                        'NextDateType' => $row['NextDateType'],
                        'NextDate' => $row['NextDate'],
                        'ElemStatus' => $row['ElemStatus']);

                    // Build Array with Index ElemID, then with INdex of ZoneID, where that one includes the same data as above
                    //Array ( [68410] => Array ( [71171] => Array ( [ElemID] => 68410 [ZoneID] => 71171 [ElemClass_S] => Media [ElemClassText] => Media [ElemGroup_S] => [ElemGroupText] => [ElemType_S] => soil [ElemTypeText] => Soil

                    $elemtable_2D [$row['ElemID']] [$row['ZoneID']] = array(
                        'ElemID' => $row['ElemID'],
                        'ZoneID' => $row['ZoneID'],
                        'ElemClass_S' => $row['ElemClass_S'],
                        'ElemClassText' => $row['ElemClassText'],
                        'ElemGroup_S' => $row['ElemGroup_S'],
                        'ElemGroupText' => $row['ElemGroupText'],
                        'ElemType_S' => $row['ElemType_S'],
                        'ElemTypeText' => $row['ElemTypeText'],
                        'ElemDetail' => $row['ElemDetail'],
                        'NextDateType' => $row['NextDateType'],
                        'NextDate' => $row['NextDate'],
                        'ElemStatus' => $row['ElemStatus']);

                    $elemtable_3D [$row['ElemID']] [$row['ZoneID']] [$row['ElemClass_S']] = array(
                        'ElemID' => $row['ElemID'],
                        'ZoneID' => $row['ZoneID'],
                        'ElemClass_S' => $row['ElemClass_S'],
                        'ElemClassText' => $row['ElemClassText'],
                        'ElemGroup_S' => $row['ElemGroup_S'],
                        'ElemGroupText' => $row['ElemGroupText'],
                        'ElemType_S' => $row['ElemType_S'],
                        'ElemTypeText' => $row['ElemTypeText'],
                        'ElemDetail' => $row['ElemDetail'],
                        'NextDateType' => $row['NextDateType'],
                        'NextDate' => $row['NextDate'],
                        'ElemStatus' => $row['ElemStatus']);





                    $elemid_array[] = $row['ElemID']; // creates Array of just the elementid's ( [0] => 71171 [1] => 194166 [2] => 194167 )
                    //$elementclass_array=array('ActivityMonitoring');
                } // end while
                $elemtable_2D['elemid_array'] = $elemid_array; // add this to the table
                $elemtable_3D['elemid_array'] = $elemid_array; // add this to the table
                //
                // for 3D model inner cycle
                $elementclass_array_all = array('Objective', 'Media', 'Activity', 'Investigation', 'ActivityMonitoring');


                $elemtable_3D['elementclass_array_all'] = $elementclass_array_all;
            } // end else of main block
            //
            //QC Begin (nice print outs to see what is happening !
            /*
              echo " <br/><br/> by ElementID <br/>";
              print_r($elemtable);

              echo "<br/><br/>== 2D  ElemID and ZoneID===<br/>";
              print_r($elemtable_2D);
             *
             *
              echo "<br/><br/>== 3D  ElemID and ZoneID===<br/>";
              print_r($elemtable_3D);


              print_r ($elemid_array);
              echo "===================<br/>";

              print_r( $elemtable_2D['elemid_array']);  // same output as print_r ($elemeid_array);
              echo "===================<br/>";

              //echo"<br/> selected item from a specific zone: ".$zonetable[71171]['ZoneID']."is ".$zonetable[71171]['Zone_ElemTypeText'];

              print_r($elementclass_array_all);
              echo "===================<br/>";
              print_r($elemtable_3D['elementclass_array_all']);
              echo "===================<br/>";

             */  // QC End


            echo "<br/><br/>== 3D  ElemID and ZoneID===<br/>";
            print_r($elemtable_3D);

            return $elemtable_3D;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    //===================================================
    // get parcel and  map images
    function get_images($siteid)
    {
        $img_array = array();
        try
        {   // get map imagage
            $sql = '
                   
                SELECT  Top(1)     DocID,
                            DocURL
                FROM         DocumentStore
                WHERE         (DocType_S = "site_map_img") AND
                            (BelongsToType = "site")
                        AND (BelongsToID = ?)
                        AND (DocStatus = "active")    ';


            $query = $this->msterradex->query($sql, array($siteid));
            $resultquery = $query->result_array();

            if (!empty($resultquery)):
                {

                    $img_array['site_map_img'] = $resultquery['0']['DocURL'];
                } endif;

            // get parcel imagage

            $sql_2 = '
                    SELECT   Top(1)
                    DocURL
                        FROM         DocumentStore
                        WHERE     (DocType_S = "parcel_map_img")
                        AND (BelongsToType = "site")
                        AND (BelongsToID = ?)
                        AND (DocStatus = "active")  ';


            $query_2 = $this->msterradex->query($sql_2, array($siteid));


            $resultquery_2 = $query_2->result_array();

            if (!empty($resultquery)):
                {

                    $img_array['parcel_map_img'] = $resultquery_2['0']['DocURL'];
                } endif;
//
            //     print_r($img_array);
            //     echo"<br/> output ".$img_array['site_map_img']."<br/>";
            //     echo"<br/> output ".$img_array['parcel_map_img']."<br/>";

            return $img_array;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================

    /*    // get parcel map image
      function get_parcelmap_img($siteid =3237)
      {
      try
      {
      $sql ='
      SELECT   Top(1)
      DocURL
      FROM         DocumentStore
      WHERE     (DocType_S = "parcel_map_img")
      AND (BelongsToType = "site")
      AND (BelongsToID = ?)
      AND (DocStatus = "active")
      ';

      $query = $this->msterradex->query($sql, array($siteid));
      return $query->result_array();
      } catch (Exception $e)
      {
      throw $e;
      }

      }// end function

     */
    //===================================================
    // get most recent assessment
    function get_assessment($siteid =3237)
    {
        try
        {
            $sql = "
                        SELECT     TOP (1) Assessment.AssessID,
                        Assessment.SiteID,
                        Assessment.AssessorID,
                        Assessment.AssessTypeText,

                        Assessment.ObservationMode,
                   

                            (CASE WHEN Assessment.AssessDate IS NULL THEN '' ELSE CONVERT(varchar(10), Assessment.AssessDate, 120) END) AS AssessDate,
                        Assessment.AssessStatus,
                       -- Assessment.UpdateDate,
                        User2.FName,
                        User2.LName,
                     User2.Phone, User2.email, Organization.OrgName, Site.SiteName, Site.SiteStreet, Site.SiteCity, Site.SiteState, Site.SiteURL, Site.PayingParty
FROM         Organization INNER JOIN
                     User2 ON Organization.OrgID = User2.OrgID INNER JOIN
                     Assessment ON User2.UserID2 = Assessment.AssessorID INNER JOIN
                     Site ON Assessment.SiteID = Site.SiteID
WHERE     (Assessment.AssessStatus = \"active\") AND (Assessment.SiteID = ?)
ORDER BY Assessment.AssessID DESC
  ";

            $query = $this->msterradex->query($sql, array($siteid));
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    // get  assessment by AssessID
    function get_assessment_byassessid($assessid)
    {
        try
        {
            $sql = "
                        SELECT     TOP (1) Assessment.AssessID,
                        Assessment.SiteID,
                        Assessment.AssessorID,
                        Assessment.AssessTypeText,
                        (CASE WHEN  Assessment.AssessStatus IS NULL  THEN '' ELSE Assessment.AssessStatus END)  AS AssessStatus ,

                        Assessment.ObservationMode,


                            (CASE WHEN Assessment.AssessDate IS NULL THEN '' ELSE CONVERT(varchar(10), Assessment.AssessDate, 120) END) AS AssessDate,
                        Assessment.AssessStatus,
                       -- Assessment.UpdateDate,
                        User2.FName,
                        User2.LName,
                     User2.Phone, User2.email, Organization.OrgName, Site.SiteName, Site.SiteStreet, Site.SiteCity, Site.SiteState, Site.SiteURL, Site.PayingParty
FROM         Organization INNER JOIN
                     User2 ON Organization.OrgID = User2.OrgID INNER JOIN
                     Assessment ON User2.UserID2 = Assessment.AssessorID INNER JOIN
                     Site ON Assessment.SiteID = Site.SiteID
WHERE     (Assessment.AssessID = ?)
ORDER BY Assessment.AssessID DESC
  ";

            $query = $this->msterradex->query($sql, array($assessid));
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
//NOT USED IS INTERSTING long Code Version COULD BE DELETED
    // Long Code Version, works good example but not used
    // get ACTIVE observation details with Element and Document details
    function get_assessment_details_v3($assessid =80078)
    {


        $functionreturn = array();

        $sql = "

         SELECT
                        DocumentStore.DocID,
                        Assess_Item.ItemID,
                        Assess_Item.ZoneID,
                    (CASE WHEN Assessment.AssessDate IS NULL THEN '' ELSE CONVERT(varchar(10), Assessment.AssessDate, 120) END) AS AssessDate

            FROM         Assessment INNER JOIN
                      Assess_Item ON Assessment.AssessID = Assess_Item.AssessID INNER JOIN
                      DocumentStore ON Assess_Item.ItemID = DocumentStore.BelongsToID INNER JOIN
                      Element ON Assess_Item.ZoneID = Element.ZoneID
                WHERE     (NOT (DocumentStore.DocURL IS NULL))
                        AND (DocumentStore.BelongsToType = 'assess_item')
                        AND (Assessment.AssessID = $assessid)
                        AND  (Assessment.AssessStatus = 'active')
                      AND (Assess_Item.Count_AssessItemImage > 0)
                      AND (Element.ElemClass_S = 'zonetype')
                      AND (Element.ElemStatus = 'active')



      ";

        $query = mssql_query($sql);

        if (!mssql_num_rows($query))
        {
            echo '';
        }
        else
        {
            while ($row = mssql_fetch_assoc($query))
            {


                $functionreturn[] = array('ItemID' => $row['ItemID'],
                    'ZoneID' => $row['ZoneID'],
                    'DocID' => $row['DocID'],
                    'AssessDate' => $row['AssessDate']
                );
            }
            return $functionreturn;
        }
    }

// end function
    //===================================================
    //===================================================
    // get ACTIVE (used in Report v4) observation details with Element and Document details
    function get_assessment_details($assessid)
    {
        try
        {
            $sql = "
                SELECT     Assessment.AssessID,
  (CASE WHEN Assessment.AssessDate IS NULL THEN '' ELSE CONVERT(varchar(10), Assessment.AssessDate, 120) END) AS AssessDate,

                        Assess_Item.ZoneID,
                        Assess_Item.ItemTypeText,
                        Assess_Item.Detail,
                        Assess_Item.ItemID,
                        Assessment.AssessStatus,
     (CASE WHEN  Assess_Item.Count_AssessItemImage IS NULL THEN '0'  ELSE Assess_Item.Count_AssessItemImage END) AS Count_AssessItemImage,
                      DocumentStore.DocID,
                      DocumentStore.DocType_S,
                      DocumentStore.DocTypeText,
                      DocumentStore.DocURL,
                      DocumentStore.DocDescription,
                      Element.ElemClass_S,
                      Element.ElemType_S,
                      Element.ElemTypeText,
                      Element.ElemDetail

                        FROM         Assessment INNER JOIN
                                              Assess_Item ON Assessment.AssessID = Assess_Item.AssessID INNER JOIN
                                              DocumentStore ON Assess_Item.ItemID = DocumentStore.BelongsToID INNER JOIN
                                              Element ON Assess_Item.ZoneID = Element.ZoneID
                        WHERE     (NOT (DocumentStore.DocURL IS NULL)) AND (DocumentStore.BelongsToType = 'assess_item') AND (Assessment.AssessID = $assessid) AND
                                              (Assessment.AssessStatus = 'active') AND (Assess_Item.Count_AssessItemImage > 0) AND (Element.ElemClass_S = 'zonetype')AND (Element.ElemStatus = 'active')

                        UNION ALL

                        SELECT
                        Assessment.AssessID,
                          (CASE WHEN Assessment.AssessDate IS NULL THEN '' ELSE CONVERT(varchar(10), Assessment.AssessDate, 120) END) AS AssessDate,

                        Assess_Item.ZoneID,
                        Assess_Item.ItemTypeText,
                        Assess_Item.Detail,
                        Assess_Item.ItemID,
                      Assessment.AssessStatus,
 (CASE WHEN  Assess_Item.Count_AssessItemImage IS NULL THEN '0'  ELSE Assess_Item.Count_AssessItemImage END) AS Count_AssessItemImage,
                      0 AS DocID,
                       '' AS DocType_S,
                        '' AS DocTypeText,
                         '' AS DocURL,
                      '' AS DocDescription,
                      Element.ElemClass_S,
                      Element.ElemType_S,
                      Element.ElemTypeText,
                      Element.ElemDetail

                        FROM         Assessment INNER JOIN
                                              Assess_Item ON Assessment.AssessID = Assess_Item.AssessID INNER JOIN
                                              Element ON Assess_Item.ZoneID = Element.ZoneID
                        WHERE     (Assessment.AssessID = $assessid) AND (Assessment.AssessStatus = 'active') AND (Assess_Item.Count_AssessItemImage = 0) AND
                                              (Element.ElemStatus = 'active') AND (Element.ElemClass_S = 'zonetype')
                        ORDER BY Assess_Item.ItemID
 ";




            $query = $this->msterradex->query($sql, array($assessid));


            return $query->result_array();

            print_r($query->result_array());
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
//==============================================
    // get ANY (including inactive AssessStatus) observation details
    // Active used in Dashboard co_report_assess_editbegin_contr/80107
    function get_assessment_details_incl_inactive($assessid =80078)
    {
        try
        {
            $sql = '
                SELECT     Assess_Item.ItemID,
                Assess_Item.AssessID,
                Assess_Item.ZoneID,
                Assess_Item.ItemType_S,
                Assess_Item.ItemTypeText,
                Assess_Item.Detail,
(CASE WHEN  Assess_Item.ItemStatus IS NULL  THEN "" ELSE Assess_Item.ItemStatus END)  AS ItemStatus ,
           
 (CASE WHEN Assess_Item.UpdateDate IS NULL  THEN "" ELSE CONVERT (varchar(10),Assess_Item.UpdateDate ,120)END) AS UpdateDate,
                TransZoneSite.SiteID,
                Element.ElemClass_S,
                Element.ElemType_S,
                Element.ElemTypeText,

                Element.ElemDetail
FROM         Assess_Item INNER JOIN
                     TransZoneSite ON Assess_Item.ZoneID = TransZoneSite.ZoneID INNER JOIN
                     Element ON Assess_Item.ZoneID = Element.ZoneID
WHERE     (Element.ElemClass_S = "zonetype")
AND (Element.ElemStatus = "active")

AND (Assess_Item.AssessID = ?)
ORDER BY Assess_Item.ZoneID, Assess_Item.ItemID
  ';

            $query = $this->msterradex->query($sql, array($assessid));
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    // gets alerts form pg database!!
    // NOTE 9/25/2011 EXCLUDED the Local Governemnt Events for Sample Site Demo!!
    // remove this to get them back AND Event.EventType<>'Local Government'

    function get_alerts($siteid)
    {
        try
        {
            $sql = "SELECT DISTINCT
                    
                     TransactionLocationToLocation.TransLocToLocID,
                Event.EventType,
                   (CASE WHEN TransactionLocationToLocation.AlertBeginDate IS NULL THEN '' ELSE CONVERT(varchar(10), TransactionLocationToLocation.AlertBeginDate, 120) END) AS BeginDate,

                 (CASE WHEN TransactionLocationToLocation.AlertEndDate IS NULL THEN '' ELSE CONVERT(varchar(10), TransactionLocationToLocation.AlertEndDate, 120) END) AS EndDate,
                   TransactionLocationToLocation.TransLocToLocID ,
                   Site.PayingParty,
                   Site.PayingParty_short
FROM         Zone INNER JOIN
                     Site ON Zone.SiteID = Site.SiteID INNER JOIN
                     TransactionLocationToLocation ON Zone.LocationID = TransactionLocationToLocation.LocationID1 INNER JOIN
                     Event ON TransactionLocationToLocation.LocationID2 = Event.LocationID
WHERE     (Site.SiteID = ?)
AND (TransactionLocationToLocation.AlertBeginDate > CONVERT(DATETIME, '2006-01-01 00:00:00', 102))
AND Event.EventType<>'Local Government'
ORDER BY TransactionLocationToLocation.TransLocToLocID DESC   ";

            $query = $this->msterradex->query($sql, array($siteid));
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

//5/24/2012 ===================================================

        function get_live_alert_count($siteid,$daysopen=30,$daysreport=364)
    {
           // NOTE: default dates set above
            
        try
        {
            $sql = "
                
  --==Open Alerts Now minus x days                
SELECT     COUNT(TransactionLocationToLocation.TransLocToLocID) AS count
FROM         TransactionReportSite INNER JOIN
                      Zone ON TransactionReportSite.SiteID = Zone.SiteID INNER JOIN
                      TransactionLocationToLocation ON Zone.LocationID = TransactionLocationToLocation.LocationID1
WHERE       (DATEDIFF(dd, TransactionLocationToLocation.AlertBeginDate, GETDATE() ) < $daysopen)
AND       NOT (TransactionLocationToLocation.AlertEndDate IS NULL)
                      AND (Zone.SiteID = ?)
GROUP BY TransactionReportSite.SiteID

";

     $query_1 = $this->msterradex->query($sql, array($siteid));



  //--==All Alerts Now minus x days
            $sql_2 = "
SELECT     COUNT(TransactionLocationToLocation.TransLocToLocID) AS count
FROM         TransactionReportSite INNER JOIN
                      Zone ON TransactionReportSite.SiteID = Zone.SiteID INNER JOIN
                      TransactionLocationToLocation ON Zone.LocationID = TransactionLocationToLocation.LocationID1
WHERE       (DATEDIFF(dd, TransactionLocationToLocation.AlertBeginDate, GETDATE() ) < $daysreport)
                      AND (Zone.SiteID = ?)
GROUP BY TransactionReportSite.SiteID
                                         ";

       $query_2 = $this->msterradex->query($sql_2, array($siteid));

    // same as below $query_return= array($query_1->result_array(),$query_2->result_array())  ;
       $query_return['OpenAlertCount_now_minus_x']= $query_1->result_array();
       $query_return['OpenAlertCount_All_ReportDuration']= $query_2->result_array() ;

              return $query_return;

        } catch (Exception $e)
        {
            throw $e;
        }
        

    }


     //5/24/2012  ===================================================
    //  returns also the ReportID ! and Total Alert Counts for ALL sitesin reprot
       function get_company_reportcharts($siteid)
    {

   
        try
        {
            $sql = "

            SELECT Top(1)

   REPLACE(REPLACE(Report_ChartInput.AlertDetailChartURL, LEFT(Report_ChartInput.AlertDetailChartURL, 10), ''),
                      RIGHT(REPLACE(Report_ChartInput.AlertDetailChartURL, LEFT(Report_ChartInput.AlertDetailChartURL, 10), ''), 30), '') AS alert_barchart_url,
                      REPLACE(REPLACE(Report_ChartInput.EventDetailChartURL, LEFT(Report_ChartInput.EventDetailChartURL, 10), ''),
                      RIGHT(REPLACE(Report_ChartInput.EventDetailChartURL, LEFT(Report_ChartInput.EventDetailChartURL, 10), ''), 30), '') AS event_barchart_url,

                      REPLACE(REPLACE(Report_ChartInput.HistoryPieChartURL, LEFT(Report_ChartInput.HistoryPieChartURL, 10), ''),
                      RIGHT(REPLACE(Report_ChartInput.HistoryPieChartURL, LEFT(Report_ChartInput.HistoryPieChartURL, 10), ''), 28), '') AS history_piechart_url,



                       REPLACE(REPLACE(Report_ChartInput.ClosureRatePieChartURL, LEFT(Report_ChartInput.ClosureRatePieChartURL, 10), ''),
                      RIGHT(REPLACE(Report_ChartInput.ClosureRatePieChartURL, LEFT(Report_ChartInput.ClosureRatePieChartURL, 10), ''), 27), '') AS oldclosurerate_piechart_url,




                       REPLACE(REPLACE(Report_ChartInput.ClosureRatePieChartURL_Current, LEFT(Report_ChartInput.ClosureRatePieChartURL_Current, 10), ''),
                      RIGHT(REPLACE(Report_ChartInput.ClosureRatePieChartURL_Current, LEFT(Report_ChartInput.ClosureRatePieChartURL_Current, 10), ''), 27), '') AS currentclosurerate_piechart_url,


                      Report_ChartInput.SiteCount,
                      Report_ChartInput.EventCount,
                      Report_ChartInput.AlertCount,
                      TransactionReportSite.ReportID

FROM         Report_ChartInput INNER JOIN
                      TransactionReportSite ON Report_ChartInput.ReportID = TransactionReportSite.ReportID
WHERE     (TransactionReportSite.SiteID = $siteid)
            AND (Report_ChartInput.ChartID >1)
ORDER BY TransactionReportSite.ReportID
              ";

            $query = $this->msterradex->query($sql);
            return $query->result_array();
        } catch (Exception $e)
        {
            throw $e;
        }
    }

    //===================================================
    // get documents where there is only one document for the window/site
    // "one-to-one relationship
    function get_docs_site($siteid)
    {
        try
        {
            $sql = "

              SELECT     SiteID,
                         DocID,
                         DocType_S,
                         DocTypeText,
                         DocURL,
                         DocDescription

              FROM         DocumentStore
            WHERE     (DocStatus = 'active') AND (NOT (DocURL IS NULL))
                        AND (SiteID = $siteid)
                 AND (DocType_S = 'site_pdf' OR
                      DocType_S = 'site_img' OR
                      DocType_S = 'site_detail' OR
                      DocType_S = 'site_map_img' OR
                      DocType_S = 'parcel_map_img' OR
                      DocType_S = 'logo_img' )
                     ORDER BY DocType_S
  ";

            $query = mssql_query($sql);

            if (!mssql_num_rows($query))
            {
                $site_docs = array();
            } // return empty array if no records are found
            else
            {
                $site_docs = array();

                while ($row = mssql_fetch_assoc($query))
                {

                    // build array of arrayes,  where Index  the  DocTyp_S
                    //Array ( [logo_img] => Array ( [SiteID] => 3237 [DocID] => 70732 [DocType_S] => logo_img [DocTypeText] => Logo Image [DocURL] => http://admin.terradex.com/imgages/terradex_top_logo_white_gif [DocDescription] => dark blue Terradex rectangle )
                    //  [parcel_map_img] => Array ( [SiteID] => 3237 [DocID] => 70716 [DocType_S] => parcel_map_img


                    $site_docs [$row['DocType_S']] = array(
                        'SiteID' => $row['SiteID'],
                        'DocID' => $row['DocID'],
                        'DocType_S' => $row['DocType_S'],
                        'DocTypeText' => $row['DocTypeText'],
                        'DocURL' => $row['DocURL'],
                        'DocDescription' => $row['DocDescription']);
                } // end while
            }// end else (records were found)
            // print_r($site_docs);

            return $site_docs;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===================================================
    // get documents where there is one to many relationship
    // "one-to-many relationship
    // 3 D array, DocID, ZoneID, DocType_S
    function get_docs_zone_many($siteid)
    {
        try
        {
            $sql = "

             SELECT
                 SiteID,
                 BelongsToID AS ZoneID,
                 DocID,
                 DocType_S,
                 DocTypeText,
                 DocURL,
                 DocDescription

                    FROM         DocumentStore
                    WHERE     (DocStatus = 'active')
                             AND (NOT (DocURL IS NULL))
                             AND (SiteID = $siteid)
                             AND (BelongsToType = 'zone')
                    ORDER BY ZoneID, DocType_S, DocID

                    ";

            $query = mssql_query($sql);

            if (!mssql_num_rows($query))
            {
                $zone_docs = array();
            } // return empty array if no records are found
            else
            {
                $zone_docs = array();
                $docid_array = array();

                while ($row = mssql_fetch_assoc($query))
                {

                    // build2D  array of arrayes,  where Index  the  DocTyp_S
                    /*    Array ( [70723] =>
                     *            Array ( [71171] =>
                     *                  Array ( [zone_img] => Array ( [ZoneID] => 71171 [DocID] => 70723 [DocType_S] => zone_img [DocTypeText] => Zone Image [DocURL] => http://admin.terradex.com/images/zone_71171_parkingfront_02.jpg [DocDescription] => Front Section ) ) )
                     *            [70724] =>
                     *                   Array ( [71171] =>
                     *                            Array ( [zone_img] => Array ( [ZoneID] => 71171 [DocID] => 70724 [DocType_S] => zone_img [DocTypeText] => Zone Image [DocURL] => http://admin.terradex.com/images/zone_71171_parkingback_01.jpg [

                     */ $zone_docs [$row['DocID']] [$row['ZoneID']] [$row['DocType_S']] = array(
                        'ZoneID' => $row['ZoneID'],
                        'DocID' => $row['DocID'],
                        'DocType_S' => $row['DocType_S'],
                        'DocTypeText' => $row['DocTypeText'],
                        'DocURL' => $row['DocURL'],
                        'DocDescription' => $row['DocDescription']);


                    $docid_array[] = $row['DocID']; // creates Array of list of docid's ( [0] => 71171 [1] =>
                } // end while
            }// end else (records were found)

            if (isset($docid_array))
            {
                $zone_docs['docid_array'] = $docid_array; // add this to the table
            }
            else
            {

            };
            //  print_r($zone_docs);

            return $zone_docs;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
//===========================
    // get ACTIVE documents for the Document Center Page
    function get_documents_forlisting($siteid)
    {
        try
        {
            $sql = "
                SELECT     DocID, SiteID, BelongsToType, BelongsToID, DocType_S, DocTypeText, DocDescription, DocURL,
                 (CASE WHEN UpdateDate IS NULL  THEN ''ELSE CONVERT (varchar(10), UpdateDate,120)END) AS UpdateDate

             FROM         DocumentStore
                WHERE     (DocStatus = 'active') AND (SiteID = $siteid)
                                 AND (DocType_S = 'assessment_pdf' OR
                                      DocType_S = 'co_plan_pdf' OR
                                      DocType_S = 'zone_webpage' OR
             DocType_S = 'documentstore' OR
                                      DocType_S = 'site_detail')

                Order by   UpdateDate Desc, DocDescription
 ";




            $query = $this->msterradex->query($sql, array($siteid));


            return $query->result_array();

            //     print_r($query->result_array());
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
//==============================================
    //===========================
    // get users, as in Site Sentinel
    function get_site_users($siteid)
    {
        try
        {
            $sql = "
              SELECT DISTINCT
             User2.UserID2,
             User2.FName ,
             User2.LName ,
             Organization.OrgName,
             Organization.Street,
             Organization.City,
             Organization.State,
             Organization.ZIP,
             User2.Phone,
             Site.SiteID,
             aspnet_Users.UserName AS email,
             TransactionSiteUser.UserSiteRoleLabelText,

             CASE TransactionSiteUser.UserSiteRole when 'AlertCC' then 'true' else 'false' end as isCC,
             CASE TransactionSiteUser.UserSiteRole when 'AlertRecipient' then 'true' else 'false' end as isTo
                FROM         Site INNER JOIN
                                      TransactionSiteUser ON Site.SiteID = TransactionSiteUser.SiteID INNER JOIN
                                      User2 ON TransactionSiteUser.UserID2 = User2.UserID2 INNER JOIN
                                      Organization ON User2.OrgID = Organization.OrgID INNER JOIN
                                      aspnet_Users ON User2.UserId = aspnet_Users.UserId
                WHERE     (Site.SiteID = $siteid) AND (TransactionSiteUser.UserSiteRole = 'alertrecipient' OR
                                      TransactionSiteUser.UserSiteRole = 'user'OR
                                      TransactionSiteUser.UserSiteRole = 'alertcc')
                ORDER BY User2.LName


 ";




            $query = $this->msterradex->query($sql, array($siteid));


            return $query->result_array();

            //     print_r($query->result_array());
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===========================
    // get users, as in Site Sentinel
    function get_mysites($userid)
    {
        try
        {
            $sql = "
       SELECT DISTINCT
              dbo.TransactionSiteUser.SiteID,
              dbo.Site.ClientReferenceSiteID,
              dbo.Site.SiteName,
              dbo.Site.SiteStreet,
              dbo.Site.SiteCity,
              dbo.Site.SiteState,
               dbo.Site.ActivationStatus,
            dbo.Site.tdx_datasetid
       FROM         dbo.UserName_and_User2 INNER JOIN
                      dbo.TransactionSiteUser ON dbo.UserName_and_User2.UserID2 = dbo.TransactionSiteUser.UserID2 INNER JOIN
                      dbo.Site ON dbo.TransactionSiteUser.SiteID = dbo.Site.SiteID

                      WHERE (TransactionSiteUser.UserID2 = $userid)
             Order by SiteCity

 ";




            $query = $this->msterradex->query($sql, array($userid));


            return $query->result_array();

            //     print_r($query->result_array());
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
    //===========================
    // get users, as in Site Sentinel
    function get_myalerts($userid, $status ="")
    {
        try

// check for which kind. Note that controller passess on Open, Closed
// and uses it as header text, therefore capitalization does matter
        {
            //  echo $status;
            if ($status == "Closed")
            {
                $wherestatement = "AND (NOT (TransactionLocationToLocation.AlertEndDate IS NULL))";
            }
            elseif ($status == "Open")
            {
                $wherestatement = "AND (TransactionLocationToLocation.AlertEndDate IS NULL)";
            }

            else
                $wherestatement="";


            $sql = "
             SELECT DISTINCT
  TOP (200) TransactionLocationToLocation.TransLocToLocID,
             Site.SiteName,
             Site.SiteStreet,
             Site.SiteCity,
             Site.SiteState,
             Site.ClientReferenceSiteID,
             Site.PayingParty,
             Site.tdx_datasetid,
             Event.EventType,

             Event.EventDetails,
       
               (CASE WHEN TransactionLocationToLocation.AlertBeginDate IS NULL THEN '' ELSE CONVERT(varchar(10), TransactionLocationToLocation.AlertBeginDate, 120) END) AS AlertBeginDate,
               (CASE WHEN TransactionLocationToLocation.AlertEndDate IS NULL THEN '' ELSE CONVERT(varchar(10), TransactionLocationToLocation.AlertEndDate, 120) END) AS AlertEndDate,
         
             TransactionSiteUser.UserID2
                FROM         Event INNER JOIN
                      TransactionLocationToLocation ON Event.LocationID = TransactionLocationToLocation.LocationID2 INNER JOIN
                      Zone INNER JOIN
                      TransactionSiteUser ON Zone.SiteID = TransactionSiteUser.SiteID INNER JOIN
                      Site ON Zone.SiteID = Site.SiteID ON TransactionLocationToLocation.LocationID1 = Zone.LocationID
                WHERE     (TransactionSiteUser.UserID2 = $userid) 
                      AND (NOT (TransactionLocationToLocation.AlertBeginDate IS NULL))
                      AND (Event.EventType <>\"Local Government\")

                      $wherestatement

                ORDER BY TransactionLocationToLocation.TransLocToLocID DESC


 ";




            $query = $this->msterradex->query($sql, array($userid));


            return $query->result_array();

            //     print_r($query->result_array());
        } catch (Exception $e)
        {
            throw $e;
        }
    }

// end function
//==============================================
}

//end Class
?>
