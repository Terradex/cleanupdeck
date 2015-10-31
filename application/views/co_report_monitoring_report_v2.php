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



             <iframe src="https://feeder.terradex.com/lw_reporting/single_site_report/<?php echo $SiteID; ?>/no_header" width="700" height="1000" ></iframe>
        <?php
        // put your code here
        ?>
    </body>
</html>
