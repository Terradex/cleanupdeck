<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><title>
            My Alerts
        </title>
		<?php $this->load->view('partials/includes'); ?>
        <script type="text/javascript">
            $(function() {

                // Tabs
                $('#tabs').tabs();
            });
        </script>


        <script type="text/javascript">

            function close_window()
            {
                close();
            }
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
        <div class="section-title excavation">My <?php echo $status ?>  Alerts </div>
        <p><input type="button" value="Cancel" onclick="close_window()" > &nbsp;&nbsp;&nbsp;

            <!--    do not show the current version    -->
            <?php
            if ($status == "")
            {
                echo'';
            }
            else
            {
                echo"<a href=\"/report_max_control/co_report_vc_myalerts/" . $userid . "/\" target=\"_self\" style=\"color:#0000FF\" > All Alerts   </a>&nbsp;&nbsp;&nbsp";
            }
            ?>

            <?php
            if ($status == "Open")
            {
                echo'';
            }
            else
            {
                echo"<a href=\"/report_max_control/co_report_vc_myalerts/" . $userid . "/Open\" target=\"_self\" style=\"color:#0000FF\" > Open Alerts Only   </a>&nbsp;&nbsp;&nbsp";
            }
            ?>

            <?php
            if ($status == "Closed")
            {
                echo'';
            }
            else
            {
                echo"<a href=\"/report_max_control/co_report_vc_myalerts/" . $userid . "/Closed\" target=\"_self\" style=\"color:#0000FF\" > Closed Alerts Only   </a>&nbsp;&nbsp;&nbsp";
            }
            ?>


        </p>
        <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">


            <tr> <!--    outer table -->
                <td><!--    outer table -->


                    <table class ="grid">


                        <tr>
                            <th class="greyback"> View Alert </th>
                    <!--        <th class="greyback"> Reference</th>-->
                          <!--   <th class="greyback"> Alert Number</th>-->
                            <th class="greyback"> Map </th>
                            <th class="greyback"> Site Name  </th>
                            <th class="greyback"> Street  </th>
                            <th class="greyback"> City </th>
                            <th class="greyback"> State </th>
                            <th class="greyback"> Type </th>
                <!-- <th class="greyback"> Details </th>-->
                            <th class="greyback"> Begin </th>
                            <th class="greyback"> End </th>
                <!--            <th class="greyback"> User Site ID </th>-->


                        </tr>


                        <?php
                        foreach ($myalerts as $myalert)
                        // foreach ($objectives as $objective)
                        {

                            echo
                            "<tr class=\"text_med\">";
               // show different alert page for DTSC versus the others
                            if ($myalert ['PayingParty'] == 'Department of Toxics and Substance Control')
                            {
                                echo "<td><a href=\"http://www.terradex.com/UserPages/UserAlertResults_8020_test.aspx?AlertId=" . $myalert ['TransLocToLocID'] . "\"target=\"_blank\" style=\"color:#0000FF\" >" . $myalert ['TransLocToLocID'] . " </a></td>";
                            }
                            else
                                echo "<td> <a href=\"http://www.terradex.com/UserPages/UserAlertResults.aspx?AlertId=" . $myalert ['TransLocToLocID'] . "\"target=\"_blank\" style=\"color:#0000FF\" >" . $myalert ['TransLocToLocID'] . " </a></td>";

echo           "<td><a href=\"http://feeder.terradex.com/ic/mapit_lw/alert/". $myalert ['TransLocToLocID'] ."/16/". $myalert ['tdx_datasetid'] ."\"target=\"_blank\" style=\"color:#0000FF\" >Map </a></td>" ;

//             "<td>".$myalert ['ClientReferenceSiteID']."</td>".
                            echo "<td style=\"font-weight:bold\">" . $myalert ['SiteName'] . "</td>" .
                            "<td>" . $myalert ['SiteStreet'] . "</td>" .
                            "<td>" . $myalert ['SiteCity'] . "</td>" .
                            "<td>" . $myalert ['SiteState'] . "</td>" .
                            "<td>" . $myalert ['EventType'] . "</td>" .
//          "<td>".$myalert ['EventDetails']."</td>".
                            //        "<td>".$myalert ['PayingParty']."</td>".
                            "<td>" . $myalert ['AlertBeginDate'] . "</td>" .
                            "<td>" . $myalert ['AlertEndDate'] . "</td>";
//          "<td>".$myalert ['ClientReferenceSiteID']."</td>";



                            echo"</tr>";
                        }
                        ?>


                    </table>
                </td>   <!--    outer table -->
            </tr>  <!--    outer table -->
        </table> <!--    outer table -->
    </body>
</html>
