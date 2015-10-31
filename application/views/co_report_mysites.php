<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><title>
            My Sites
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
        <div class="section-title excavation">My Active Sites </div>
        <p><input type="button" value="Cancel" onclick="close_window()" ></p>
        <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">


            <tr> <!--    outer table -->
                <td><!--    outer table -->


                    <table class ="grid">


                        <tr>
                            <th class="greyback"> View Site </th>
                            <th class="greyback"> Map </th>
                    <!--        <th class="greyback"> Reference</th>-->
                            <th class="greyback"> Client ID</th>

                            <th class="greyback"> Name  </th>
                            <th class="greyback"> Street  </th>
                            <th class="greyback"> City </th>
                            <th class="greyback"> State </th>
                <!--             <th class="greyback"> Activation  </th>-->

                        </tr>


                        <?php
                        foreach ($mysites as $mysite)
                        // foreach ($objectives as $objective)
                        {

                            echo
                            "<tr class=\"text_med\">" .
                            "<td><a href=\"http://www.terradex.com/UserPages/UserSiteSentinel.aspx?SiteID=". $mysite ['SiteID'] ."\"target=\"_blank\" style=\"color:#0000FF\" >" . $mysite ['SiteID'] . " </a></td>" .
                            "<td><a href=\"https://feeder.terradex.com/ic/mapit_lw/site/". $mysite ['SiteID'] ."/16/". $mysite ['tdx_datasetid'] ."\"target=\"_blank\" style=\"color:#0000FF\" >Map </a></td>" .
                            "<td>" . $mysite ['ClientReferenceSiteID'] . "</td>" .
                            "<td style=\"font-weight:bold\">" . $mysite ['SiteName'] . "</td>" .
                            "<td>" . $mysite ['SiteStreet'] . "</td>" .
                            "<td>" . $mysite ['SiteCity'] . "</td>" .
                            "<td>" . $mysite ['SiteState'] . "</td>";
//                "<td>".$mysite ['ActivationStatus']."</td>";

                            echo"</tr>";
                        }
                        ?>


                    </table>
                </td>   <!--    outer table -->
            </tr>  <!--    outer table -->
        </table> <!--    outer table -->
    </body>
</html>
