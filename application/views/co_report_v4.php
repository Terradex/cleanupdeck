<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><title>
            Report
        </title>
		<?php $this->load->view('partials/includes'); ?>
        <script type="text/javascript">
            $(function() {

                // Tabs
                $('#tabs').tabs();
            });
        </script>
        <style type="text/css">
            hr.thinlightgray {
                height: 2px;
                border: 0;
                color: #EDEDED;
                background-color: #EDEDED;
                width: 100%;
            }
            hr.thickdarkgray {
                height: 2px;
                border: 0;
                color: #333;
                background-color: #7575A3;
                width: 100%;
            }
            .grid {


                width:600px;
                border:1px solid;
                border-color: #9e9e9e;
                border-collapse:collapse;
                font-family: tahoma, Verdana,Arial,Helvetica,sans-serif;
                font-size: 1em;
            }

            .greyback
            {
                background-color:#9e9e9e;color:white;
                border-color: white;
                text-align:left;
            }
            .text_med {
                font-family: Tahoma,Monaco, Verdana, Sans-serif;
                font-size: 12px;

            }
            .text_med_bold {
                font-family: Tahoma,Monaco, Verdana, Sans-serif;
                font-size: 12px;
                font-weight: bold;

            }
            ul.none {list-style-type:none;}

            }

        </style>



    </head>


    <body style="background-color: White">

        <table id="Header" cellspacing="0" border="0" style="border-collapse:collapse;">
            <tr>
                <td>
                    <table width="600" border="0" cellspacing="0" cellpadding="0">
                        <tr style="border-width: 0px">
                            <td style="border-width: 0px" width="125" valign="middle" align="left" class="icon">

                                <img src="http://www.terradex.com/gfx/top_logo_white.gif" width="100px" />
                                <!-- Peter URL Test http://feeder.terradex.com/templates/images/landactivity_excavation_r66.png,  http://www.terradex.com/gfx/icon/sensitiveuse_r32.png-->
                            </td>
                            <td valign="top" width="325">
                                <div id="site-header">
                                    <b>Details and Monitoring Report </b>
                                    <br/>
                                    <?php
                                    echo $SiteName;
                                    ?>




                                    <br />
                                    <?= $SiteStreet ?> <br /> <?= $SiteCity ?>, <?= $SiteState ?> <br/>

                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div id="tabs">
            <ul>


                <?php if (isset($sitedocs ['parcel_map_img']['DocURL'])): ?>
                    <li><a href="#tabs-site">Property</a></li>
                <?php endif; ?>
                <li><a href="#tabs-obligation">Zone</a></li>
                <li><a href="#tabs-monitoring">Monitoring</a></li>
                <li><a href="#tabs-investigation">Investigation</a></li>
                <li><a href="#tabs-doccenter">Documents</a></li>
                <li><a href="#tabs-users">Users</a></li>
                <li><a href="#tabs-more">More</a></li>



            </ul>
            <!-- ===========  ZONE TAB  begin =================================-->
            <div id="tabs-obligation">
                <div class="section-title excavation">Zone Details for Site <?= $SiteID ?> </div>



                <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                    <?php
//HEADER LINE
                    foreach ($zones['zoneid_array'] as $key => $valuezone) //  list of ZoneID
                    {

                        echo"
 <tr><td colspan =\"2\"> <hr class =\"thickdarkgray\" > </td></tr>
<tr class=\"greyback\"style=\"font-weight:bold\">
        <td width=\"33%\" >Zone:</td>
        <td >
         " . $valuezone . "      " . $zones[$valuezone]['ZoneName'] . "</br>" . $zones[$valuezone]['Zone_ElemTypeText'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";

// show row only if data
                        if (isset($zones[$valuezone]['ClientReferenceZoneID']))
                        {
                            echo"<tr class=\"text_med\">
        <td width=\"33%\" > Client Zone ID:</td>
        <td >
         " . $zones[$valuezone]['ClientReferenceZoneID'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                        }
                        else
                        {
                            echo'';
                        }

// show row only if data
                        if (isset($zones[$valuezone]['ZoneNotes']))
                        {
                            echo"<tr class=\"text_med\">
        <td width=\"33%\" >Notes:</td>
        <td >
         " . $zones[$valuezone]['ZoneNotes'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                        }
                        else
                        {
                            echo'';
                        }

// show row only if data
                        if (($zones[$valuezone]['Zone_EffectiveDate']) !== ' ')
                        { //NOTE SLQ adds one space, therefore empty not working and must one one space here!!
                            echo"<tr class=\"text_med\">
        <td width=\"33%\" >Date Effective:</td>
        <td >
         " . $zones[$valuezone]['Zone_EffectiveDate'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                        }
                        else
                        {
                            echo'';
                        }

// show row only if data
                        if (isset($zones[$valuezone]['ChemicalsofConcern']))
                        {
                            echo"<tr class=\"text_med\">
        <td width=\"33%\" >Chemicals of Concern:</td>
        <td >
         " . $zones[$valuezone]['ChemicalsofConcern'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                        }
                        else
                        {
                            echo'';
                        }




//HEADER LINE - end
                        // ELEMENT with Test to see if it has any data -BEGIN
//

                        if (($zones[$valuezone]['Count_Media']) > 0):
                            { // yes exists
                                echo"<tr class=\"text_med\">
        <td width=\"33%\">Media:</td>

       <td>
       <ul> ";
                                $elementclass_array = array('Media');
                                foreach ($elements3D['elemid_array'] as $key => $valueelement) // list of ElementID
                                {
                                    foreach ($elementclass_array as $key => $valueclass) // the selected class
                                    {

                                        if (isset($elements3D[$valueelement][$valuezone][$valueclass]))
                                        {
                                            echo "<li>" . $elements3D[$valueelement][$valuezone] [$valueclass]['ElemDetail'] . "</li> ";
                                        }
                                        {
                                            echo"";
                                        }
                                    }//inner
                                }//middle
                                echo"</td>
        </ul>
        </tr>
        <tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                            }// end yes exists
                        endif;
//else {echo"";}
// ELEMENT (with  test) - end
// ELEMENT Objective   - begin
                        echo"<tr class=\"text_med\">
        <td width=\"33%\">Objectives:</td>

       <td>
       <ul> ";

                        $elementclass_array = array('Objective');
                        foreach ($elements3D['elemid_array'] as $key => $valueelement) // list of ElementID
                        {
                            foreach ($elementclass_array as $key => $valueclass) // the selected class
                            {

                                if (isset($elements3D[$valueelement][$valuezone][$valueclass]))
                                {
                                    echo "<li>" . $elements3D[$valueelement][$valuezone] [$valueclass]['ElemDetail'] . "</li> ";
                                }
                                {
                                    echo"";
                                }
                            }//inner
                        }//middle
                        echo"</td>
        </ul>
        </tr>
        <tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
// ELEMENT  - end
                        // ELEMENT  ActivityMonitoring Categories- begin
                        echo"<tr class=\"text_med\">
        <td width=\"33%\">Monitoring Categories:</td>

       <td>
       <ul> ";

                        $elementclass_array = array('ActivityMonitoring');
                        foreach ($elements3D['elemid_array'] as $key => $valueelement) // list of ElementID
                        {
                            foreach ($elementclass_array as $key => $valueclass) // the selected class
                            {

                                if (isset($elements3D[$valueelement][$valuezone][$valueclass]))
                                {
                                    echo "<li>" . $elements3D[$valueelement][$valuezone] [$valueclass]['ElemDetail'] . "</li> ";
                                }
                                else
                                {
                                    echo"";
                                }
                            }//inner
                        }//middle
                        echo"</td>
        </ul>
        </tr>
        <tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
// ELEMENT  - end
                        // ELEMENT with Test to see if it has any data ActivityInvestigation -BEGIN

                        if (($zones[$valuezone]['Count_ActivityInvestigation']) > 0):
                            { // yes exists
                                echo"<tr class=\"text_med\">
        <td width=\"33%\">Investigation:</td>

       <td>
       <ul> ";
                                $elementclass_array = array('ActivityInvestigation');
                                foreach ($elements3D['elemid_array'] as $key => $valueelement) // list of ElementID
                                {
                                    foreach ($elementclass_array as $key => $valueclass) // the selected class
                                    {

                                        if (isset($elements3D[$valueelement][$valuezone][$valueclass]))
                                        {
                                            echo "<li>" . $elements3D[$valueelement][$valuezone] [$valueclass]['ElemDetail'] . "</li> ";
                                        }
                                        {
                                            echo"";
                                        }
                                    }//inner
                                }//middle
                                echo"</td>
        </ul>
        </tr>
        <tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                            }// end yes exists
                        endif;
// ELEMENT (with  test) - end
                        // IMAGES  with and inner <ul> invisible for caption!  Test to see if it has any data -BEGIN

                        if (($zones[$valuezone]['Count_ZoneImage']) > 0):
                            { // yes exists
                                echo"<tr class=\"text_med\">
        <td width=\"33%\">Images:</td>

       <td>
       <ul> ";
                                $doctype_array = array('zone_img');
                                foreach ($zonedocs['docid_array'] as $key => $valueelement) // list of DocID
                                {
                                    echo "<ul class=\"none\">";
                                    foreach ($doctype_array as $key => $valueclass) // the selected type
                                    {

                                        if (isset($zonedocs[$valueelement][$valuezone][$valueclass]))
                                        {
                                            echo"<img src=" . $zonedocs[$valueelement][$valuezone] [$valueclass]['DocURL'] . " width=\"250px\"alt=\"missingmap\" />";

                                            // if there is an image title add it
                                            if (isset($zonedocs[$valueelement][$valuezone][$valueclass]['DocDescription']))
                                            {
                                                echo "<li>" . $zonedocs[$valueelement][$valuezone] [$valueclass]['DocDescription'] . "</li> ";
                                            }
                                            {
                                                echo"";
                                            }
                                        }
                                        {
                                            echo"";
                                        }
                                    } echo "</ul>"; //inner
                                }//middle
                                echo"</td>
        </ul>
        </tr>
        <tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                            }// end yes exists
                        endif;
// DocumentStore IMAGES (with  test) - end
                        //DocumentStore ZONE  URL  with and inner "<ul class=\"none\">"   Test to see if it has any data -BEGIN

                        if (($zones[$valuezone]['Count_ZoneDetailDoc']) > 0):
                            { // yes exists
                                echo"<tr class=\"text_med\">
        <td width=\"33%\">Additional Information:</td>

       <td>
       <ul> ";
                                $doctype_array = array('zone_webpage');
                                foreach ($zonedocs['docid_array'] as $key => $valueelement) // list of DocID
                                {
                                    echo "<ul class=\"none\">";
                                    foreach ($doctype_array as $key => $valueclass) // the selected type
                                    {

                                        if (isset($zonedocs[$valueelement][$valuezone][$valueclass]))
                                        {
                                            echo"<li><a class=\"text_med_bold\" href=\"" . $zonedocs[$valueelement][$valuezone] [$valueclass]['DocURL'] . "\"style=\"color:#0000FF\"  target=\"_blank\">Click to View Zone Web Page</a></li>";
                                        }
                                        {
                                            echo"";
                                        }
                                    } echo "</ul>"; //inner
                                }//middle
                                echo"</td>
        </ul>
        </tr>
        <tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                            }// end yes exists
                        endif;
// IMAGES (with  test) - end
// end 8/31
                    } //outer = zone
                    ?>
                    <!--   END  Block for One Element -  -->

                </table>

            </div>
            <!-- ===============  END ZONE ================  SECOND TAB next tab -->

            <!-- ===============  BEGIN Site/Property  ================  SECOND TAB next tab -->

            <!-- SECOND TAB next tab -->

            <!--   if no parcel map show no parcel Take out all of this Div Tab!! -->


            <div id="tabs-site">
                <!--    NOTE: The Parcel image drives the Tab and this heading, not very good!-->
                <?php if (isset($sitedocs ['parcel_map_img']['DocURL'])): ?>  <!-- endif is line 518 at very end of section  -->

                    <div class="section-title excavation">Additional Property Information</div>


                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">



                        <?php if (isset($sitedocs ['site_map_img']['DocURL'])): ?>
                            <tr>
                                <td colspan="2"><img src="<? echo $sitedocs ['site_map_img']['DocURL'] ?>" width="500px"alt="missingmap" />  </td>
                            </tr>
                        <?php endif; ?>
                        <?php if (isset($sitedocs ['site_img']['DocDescription'])): ?>
                            <tr>
                                <td colspan="2" class="text_med_bold"><? echo "<br/>" . $sitedocs ['site_img']['DocDescription'] ?>  </td>
                            </tr>
                        <?php endif; ?>


                        <?php if (isset($sitedocs ['site_img']['DocURL'])): ?>
                            <tr>
                                <td colspan="2"><img src="<? echo $sitedocs ['site_img']['DocURL'] ?>" width="500px"alt="missingmap" />  </td>
                            </tr>
                        <?php endif; ?>

                        <!--      site url link-->
                        <?php if (isset($sitedocs ['site_detail']['DocURL'])): ?>
                            <tr>
                                <td colspan="2"> <br/><br/>
                                    <a class="text_med_bold" href=<? echo $sitedocs ['site_detail']['DocURL'] ?> style="color:#0000FF"  target="_blank">Site Web Page</a>
                                    <br/>
                                    <br/>
                                </td>
                            </tr>
                        <?php endif; ?>


                    </table>

                    <!-- table with Parcel Information  -->

                    <?php if (isset($sitedocs ['parcel_map_img']['DocURL'])): ?>

                        <hr class ="thinlightgray" >

                        <p class="text_med_bold"> Parcel Detail Information</p>
                        <table class ="grid">

                            <tr>
                                <th class="greyback"> ID </th>
                                <th class="greyback"> APN </th>
                                <th class="greyback"> Street</th>
                                <th class="greyback"> City </th>
                                <th class="greyback"> County </th>
                                <th class="greyback"> State</th>
                                <th class="greyback"> Details</th>

                            </tr>
                            <tr>
                                <?php
                                foreach ($parcels as $parcel)
                                // foreach ($objectives as $objective)
                                {

                                    echo"<td>" . $parcel ['ZoneID'] . "</td>" .
                                    "<td>" . $parcel ['LocationAPN'] . "</td>" .
                                    "<td>" . $parcel ['LocationStreet'] . "</td>" .
                                    "<td>" . $parcel ['LocationCity'] . "</td>" .
                                    "<td>" . $parcel ['LocationCounty'] . "</td>" .
                                    "<td>" . $parcel ['LocationState'] . "</td>" .
                                    "<td>" . $parcel ['LocationDescription'] . "</td>
             </tr>";
                                }
                                ?>
                            </tr>

                        </table>
                    <?php endif; ?>

                    <!--  // old map delete  $images['parcel_map_img']-->


                    <!--  if there is no parcel map image  -->
                    <?php if (isset($sitedocs ['parcel_map_img']['DocURL'])): ?>

                        <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                            <tr><td colspan ="2"> <hr class ="thinlightgray" > </td></tr>
                            <tr>
                                <td colspan ="2" class="text_med_bold"> Parcel Map  <br/></td>

                            </tr>
                            <tr>

                                <td colspan="2"><img src="<? echo $sitedocs ['parcel_map_img']['DocURL'] ?>" width="500px"alt="missing parcel map" />
                            </tr>

                        </table>

                    <?php endif; ?>

                <?php endif; ?>  <!--most outer tab condition from  line 418 -->

            </div>



            <!-- =============== END OF Parcel/Site  =================-->

            <!-- =============== END Begin Monitoring / Alerts =================-->


            <!-- THIRD  tab MONITORING  -->
            <div id="tabs-monitoring">

                <!-- Event Pie Chart if there is data   -->
                <?php if ($Chart_Pie_Event_URL !== "no_data"): ?>

                    <div class="section-title excavation">Events Detected Nearby</div>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td colspan="2">
                                <img src="<?php echo $Chart_Pie_Event_URL ?>" alt="Pie" style="width:400px; height:180px;" />
                            </td>
                        </tr>

                    </table>
                <?php endif; ?>

                <!-- Alert Pie Chart if there is data   -->
                <?php if ($Chart_Pie_Alert_URL !== "no_data"): ?>

                    <div class="section-title excavation">Alerts Issued</div>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td colspan="2">
                                <img src="<?php echo $Chart_Pie_Alert_URL ?>" alt="Pie" style="width:400px; height:180px;" />
                            </td>
                        </tr>

                    </table>
                <?php endif; ?>

                <div class="section-title excavation">Most Recent Alerts</div>


                <table class ="grid">
                    <tr>
                        <td colspan ="4" style="font-weight:bold"> <br/></td>

                    </tr>
                    <tr>
                        <th style="text-align:left">Event Type  </th>
                        <th style="text-align:left">Alert Begin   </th>
                        <th style="text-align:left">Alert End </th>
                        <th style="text-align:left"> Link To Detail Page  </th>
                    </tr>



                        <?php

                        foreach ($alerts as $alert)
                        // foreach ($objectives as $objective)
                        {    echo"<tr>";

                            echo"<td>" . $alert ['EventType'] . "</td>" .
                            "<td>" . $alert ['BeginDate'] . "</td>" .
                            "<td>" . $alert ['EndDate'] . "</td>";

               // show different alert page for DTSC versus the others
                   if ($alert ['PayingParty'] == 'Department of Toxics and Substance Control')
                            {
                                echo "<td><a href=\"http://www.terradex.com/UserPages/UserAlertResults_8020_test.aspx?AlertId=" . $alert ['TransLocToLocID'] . "\" target=\"_blank\" style=\"color:#0000FF\">View Details</a></td>";
                            }

                   else {
                          echo "<td><a href=\"http://www.terradex.com/UserPages/UserAlertResults.aspx?AlertId=" . $alert ['TransLocToLocID'] . "\" target=\"_blank\" style=\"color:#0000FF\">View Details</a></td>";
                         }

                        //  "<td><a href=\"http://www.terradex.com/UserPages/UserAlertResults_8020_test.aspx?AlertId=" . $alert ['TransLocToLocID'] . "\" target=\"_blank\" style=\"color:#0000FF\">View Details</a>   </tr>";

                    echo"</tr>";      }
                       ?>


                </table>
            </div>



            <!-- =============== END OF Monitoring/Alerts  =================-->


            <!-- =========== BEGIN  Investigation  =============-->

            <div id="tabs-investigation">
                <div class="section-title excavation">Most Recent Field Investigation</div>

                <!--  IF there are no $assessmentdetails  no investigation exists, see controler, else  is line 704, -->
                <?php if (isset($assessmentdetails))
                { ?>



                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">

                        <?php
                        foreach ($assessment as $assess_field)
                        {
                            echo" <tr>
        <td width=\"25%\">Investigation ID  </td>
        <td>" . $assess_field ['AssessID'] . "
      </td>
   </tr>
   <tr>
        <td width=\"25%\">Type  </td>
        <td>" . $assess_field ['AssessTypeText'] . "
      </td>
   </tr>
      <tr>
        <td width=\"25%\">Date   </td>
        <td>" . $assess_field ['AssessDate'] . "
      </td>
   </tr>
       <tr>
        <td width=\"25%\"> Mode of Observation </td>
        <td>
             " . $assess_field ['ObservationMode'] . "
      </td>

       <tr>
        <td width=\"25%\"> Investigator </td>
        <td>
             " . $assess_field ['FName'] . "  " . $assess_field ['LName'] . "
      </td>
   </tr>
      <tr>
        <td width=\"25%\"> Company/Organization </td>
        <td>
             " . $assess_field ['OrgName'] . "
      </td>
   </tr>


"

                            ;
                        }
                        ?>

                        <tr><td colspan ="6"> <hr class ="thickdarkgray" > </td></tr>

                    </table>


                    <table class ="grid">
                        <tr>
                            <td colspan ="7" style="font-weight:bold">Investigation Details <br/></td>

                        </tr>

                        <tr>
                            <th class="greyback"> Zone ID </th>
                            <th class="greyback"> Zone Type </th>
                            <th class="greyback"> Observation</th>
                            <th class="greyback"> Observation Details/Comments </th>


                        </tr>
                        <tr>
                            <?php
                            foreach ($assessmentdetails as $detail)
                            // foreach ($objectives as $objective)
                            {

                                echo"<td>" . $detail ['ZoneID'] . "</td>" .
                                "<td>" . $detail ['ElemTypeText'] . "</td>" .
                                "<td>" . $detail ['ItemTypeText'] . "</td>" .
                                "<td>" . $detail ['Detail'] . "</td>

             </tr>";
                            }
                            ?>
                        </tr>

                    </table>

                    <!--  Images-->

                    <div class="section-title excavation">Investigation Photos</div>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">

                        <?php
                        foreach ($assessmentdetails as $detail)
                        // foreach ($objectives as $objective)
                        {

                            if (($detail['Count_AssessItemImage']) > 0)
                            {
                                echo"<tr class=\"text_med\">";

                                echo"
                 <td width=\"25%\">Zone " . $detail['ZoneID'] . " </br>" . $detail['DocDescription'] . "</td>
                 <td ><img src=" . $detail['DocURL'] . " width=\"250px\"alt=\"missingmap\" />  </td>";

                                echo"</tr>";
                            }



                                else'';
                        }
                        ?>




                    </table>
                    <?php
                } else
                {
                    echo"";
                }
                ?>

                <!-- else from if  $assessmentdetails exist, line 585  !  isset-->

                <div class="section-title excavation">Begin New Investigation </div>
                <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">


                    <tr class="text_med">
                        <td width="33%" > New Investigation for This Site:</td>
                        <td >
                            <!--    need to add a dummy AssessID, in none available from current investigation -->
                            <?php
                            if (!isset($assess_field ['AssessorID']))
                            {
                                $userid_of_assessor = '0';
                            }
                            else
                            {
                                $userid_of_assessor = $assess_field ['AssessorID'];
                            };
                            ?>

                            <p><a href="https://admin.terradex.com/co_report_control/co_investigation_ipad_input_v2/<?php echo $SiteID; ?>/<?php echo $userid_of_assessor; ?>" style="color:#0000FF"  target="_blank">Click to Start Field Investigation</a></p>
                            <p><a href="https://admin.terradex.com/report_max_control/unpublish_new_assessments/<?php echo $SiteID; ?>" style="color:#0000FF" >Click to Un-publish Today's Investigations</a></p>



                        </td>
                    </tr>

                </table>

            </div>


            <!--===========  NEW TAB  DOCUMENT CENTER ===================
            Documents Resource Center-->

            <div id="tabs-doccenter">
                <div class="section-title excavation">Document Center </div>

                <table class ="grid">
                    <tr>
            <!--        <td colspan ="7" style="font-weight:bold">Documents and Resources <br/></td>-->

                    </tr>

                    <tr>
                        <th class="greyback"> Doc ID </th>
                <!--        <th class="greyback"> Reference</th>-->
                        <th class="greyback"> Reference ID</th>
                        <th class="greyback"> Document Type  </th>
                        <th class="greyback"> Title  </th>
                        <th class="greyback"> Link </th>

                    </tr>
                    <tr>
                        <?php
                        foreach ($listed_docs as $doc)
// foreach ($objectives as $objective)
                        {

                            echo"<td>" . $doc ['DocID'] . "</td>" .
//                 "<td>".$doc ['BelongsToType']."</td>".
                            "<td>" . $doc ['BelongsToID'] . "</td>" .
                            "<td style=\"font-weight:bold\">" . $doc ['DocTypeText'] . "</td>" .
                            "<td>" . $doc ['DocDescription'] . "</td>" .
                            "<td><a href=\"" . $doc ['DocURL'] . "\"target=\"_blank\" style=\"color:#0000FF\" >View  </a></td>";


                            echo"</tr>";
                        }
                        ?>
                    </tr>

                </table>


            </div> <!--Div Tab Doc Center END  --->


            <!--===========  NEW TAB  USERS  ===================
            user Information -->

            <div id="tabs-users">
                <div class="section-title excavation">Users with Access to the Site in the System  </div>






                <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                    <?php
//HEADER LINE
                    foreach ($site_users as $user) //  list of ZoneID
                    {

                        echo"
 <tr><td colspan =\"2\"> <hr class =\"thickdarkgray\" > </td></tr>
<tr class=\"greyback\"style=\"font-weight:bold\">
        <td  ></td>
        <td >
         " . $user['FName'] . "   " . $user['LName'] . "</br>" . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";

// one row, conditional if exists
                        if (isset($user['OrgName']))
                        {
                            echo"<tr class=\"text_med\">
        <td width=\"33%\" > Organization:</td>
        <td >
         " . $user['OrgName'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                        }
                        else
                        {
                            echo'';
                        }

// one row, always
                        echo"<tr class=\"text_med\">
        <td width=\"33%\" > Address:</td>
        <td >
         " . $user['Street'] . "<br/>" . $user['City'] . "  " . $user['ZIP'] . "  " . $user['State'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";

// one row, always
                        echo"<tr class=\"text_med\">
        <td width=\"33%\" > Contact:</td>
        <td >
         " . $user['email'] . "<br/>" . $user['Phone'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";

// one row, always
                        echo"<tr class=\"text_med\">
        <td width=\"33%\" > Role:</td>
        <td >
         " . $user['UserSiteRoleLabelText'] . "
        </td>
        </tr>
<tr><td colspan =\"2\"> <hr class =\"thinlightgray\" > </td></tr>";
                    }
                    ?>

                </table>

            </div> <!--Div Tab Users  END --->

            <!--===========  NEW TAB  MORE  ===================
            user Information -->

            <div id="tabs-more">
                <div class="section-title excavation">Other Functionality </div>

                <!--  TEMPORARY SECTION requiring UserID-->

          <!--        <form action="/report_max_control/co_report_vc_mysites_manualdataentry" target="_blank" method="post">
                    <table border="0">

                        <tr class="text_med">
                            <td width="33%">Temporary Solution</td>
                            <td> <input type="text" name="field_01" value="Enter UserID" maxlength="10" size="25"></td>

                            <td ><input type="submit" value="View My Sites"></td>
                        </tr>
                        <tr><td colspan ="2"> <hr class ="thinlightgray" > </td></tr>
                    </table>
                </form>

                <form action="/report_max_control/co_report_vc_myalerts_manualdataentry" target="_blank" method="post">
                    <table border="0">

                        <tr class="text_med">
                            <td width="33%">Temporary Solution</td>
                            <td> <input type="text" name="field_01" value="Enter UserID" maxlength="10" size="25"></td>

                            <td ><input type="submit" value="View My Alerts"></td>
                        </tr>
                        <tr><td colspan ="2"> <hr class ="thinlightgray" > </td></tr>
                    </table>
                </form>
   -->

                <!--  End Temporary Section -->



<?php if (isset($user_id))
{ ?>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse">



                        <tr class="text_med">
                            <td width="33%">All My Sites :</td>

                            <td>
                                <ul>
                                    <li><a class="text_med_bold" href="/report_max_control/co_report_vc_mysites/<?php echo $user_id ?>/"style="color:#0000FF"  target="_blank">Click to View List of My Sites</a></li>
                                </ul>
                            </td>

                        </tr>


                        <tr><td colspan ="2"> <hr class ="thinlightgray" > </td></tr>

                        <tr class="text_med">
                            <td width="33%">All My Alerts :</td>

                            <td>
                                <ul>
                                    <li><a class="text_med_bold" href="/report_max_control/co_report_vc_myalerts/<?php echo $user_id ?>/"style="color:#0000FF"  target="_blank">Click to View List of My Alerts</a></li>
                                </ul>
                            </td>

                        </tr>

                    </table>
<?php } ?>

            </div> <!--Div Tab More END --->







        </div> <!-- closes out ALL tabs above --->
    </body>
</html>


