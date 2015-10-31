<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><title>
            Management Report
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



        <div id="tabs">
            <ul>


                <?php if (isset($charts['0']['alert_barchart_url'] )): ?>
                    <li><a href="#tabs-alert">Alerts</a></li>
                <?php endif; ?>
               <?php if (isset($charts['0']['event_barchart_url'] )): ?>
                    <li><a href="#tabs-event">Events</a></li>
                <?php endif; ?>
                 <?php if (isset($charts['0']['history_piechart_url'] )): ?>
                    <li><a href="#tabs-history">History</a></li>
                <?php endif; ?>
              <?php if( (isset($charts['0']['oldclosurerate_piechart_url'] ))
                      OR  (isset($charts['0']['currentclosurerate_piechart_url'] ))): ?>
                    <li><a href="#tabs-closure">Closure</a></li>
                <?php endif; ?>




            </ul>


            <!-- ===========   TAB Alerts  begin =================================-->
            <div id="tabs-alert">

               <?php if (isset($charts['0']['alert_barchart_url'] )): ?>

                    <div class="section-title excavation">Analysis of Alerts Issued For All Sites</div>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td colspan="2">
                                <img src="<?php echo  $charts['0']['alert_barchart_url']  ?>"  />
                            </td>
                        </tr>

                    </table>
                <?php endif; ?>
            </div>  <!-- =tab -->

           <!-- ===========   TAB Events  begin =================================-->
            <div id="tabs-event">

               <?php if (isset($charts['0']['event_barchart_url'] )): ?>

                    <div class="section-title excavation">Analysis of Reviewed Events Near All Sites</div>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td colspan="2">
                                <img src="<?php echo  $charts['0']['event_barchart_url']  ?>"  />
                            </td>
                        </tr>

                    </table>
                <?php endif; ?>
            </div>  <!-- =tab -->

          <!-- ===========   TAB History Rates  begin =================================-->
            <div id="tabs-history">

               <?php if (isset($charts['0']['history_piechart_url'] )): ?>

                    <div class="section-title excavation">Summary of All Events Nearby and Alerts </div>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td colspan="2">
                                <img src="<?php echo  $charts['0']['history_piechart_url']  ?>"  />
                            </td>
                        </tr>

                    </table>
                <?php endif; ?>
             </div>  <!-- =tab -->

                       <!-- ===========   TAB Hist  begin =================================-->
            <div id="tabs-closure">



                    <div class="section-title excavation">Closure Rate and Response Analysis</div>
                    <table id="SiteDetails" cellspacing="0" border="0" style="border-collapse:collapse;">
                       <?php if (isset($charts['0']['oldclosurerate_piechart_url'] )):?>

                        <tr>
                            <td colspan="2">
                                <img src="<?php echo  $charts['0']['oldclosurerate_piechart_url']  ?>"  />
                            </td>
                        </tr>
                <tr><td colspan ="2"> <hr class ="thinlightgray" > </td></tr>
                     <?php    endif;?>

                         <?php if (isset($charts['0']['currentclosurerate_piechart_url'] )):?>

                        <tr>
                            <td colspan="2">
                                <img src="<?php echo  $charts['0']['currentclosurerate_piechart_url']  ?>"  />
                            </td>
                        </tr>
                       <?php    endif;?>
                    </table>

            </div>  <!-- =tab -->







     </div> <!-- End of ALL tabs-->
            </body>
</html>
