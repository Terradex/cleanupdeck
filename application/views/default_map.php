<html>
    <head>
        <title>Cleanup Deck 2.0</title>
        <link rel="shortcut icon" href="img/favicon.ico"/>
<!--        <link rel="stylesheet" type="text/css" href="http://openlayers.org/dev/theme/default/style.css" />-->
        <link rel="stylesheet" type="text/css" href="resources/OpenLayers-dev/theme/default/style.css" />
        <link rel="stylesheet" type="text/css" href="resources/ext-3.3.1/resources/css/ext-all.css" />
        <link rel="stylesheet" type="text/css" href="resources/ext-3.3.1/resources/css/xtheme-gray.css" />
        <link rel="stylesheet" type="text/css" href="css/default.css">
        <link rel="stylesheet" type="text/css" href="resources/GeoExt/resources/css/geoext-all.css" />

        <!-- LIBS -->
        <script type="text/javascript" src="resources/ext-3.3.1/adapter/ext/ext-base.js"></script>
        <!-- ENDLIBS -->

        <script type="text/javascript" src="resources/ext-3.3.1/ext-all.js"></script>
        <script type="text/javascript" src="resources/ext-3.3.1/examples/ux/TableGrid.js"></script>
        <script type="text/javascript" src="https://maps.google.com/maps/api/js?v=3.2&amp;sensor=false"></script>
        <script type="text/javascript" src="resources/OpenLayers-dev/lib/OpenLayers.js"></script>
<!--        <script type="text/javascript" src="http://openlayers.org/dev/OpenLayers.js"></script>-->
        <!--<script src="http://openlayers.org/dev/OpenLayers.js"></script>-->
        <script type="text/javascript" src="resources/GeoExt/script/GeoExt.js"></script>
        <script type="text/javascript" src="js/bookmarks.js"></script>
		<script type="text/javascript" src="js/LoadingPanel.js"></script>
        <script type="text/javascript">
            disclaimer = 'The data provided on this site is for informational and planning purposes only.<br><br>Absolutely no accuracy or completeness guarantee is implied or intended. All information on this map is subject to such variations and corrections as might result from a complete title search and/or accurate field survey.<br><br>Please press "Yes" to accept this disclaimer and access the application.';
            about = '<div id="about-wrapper"><div id="about-col1"><h1>INTRODUCING THE CLEANUP DECK</h1><p>The Cleanup Deck is part of a mapping service being developed by <ahref="www.terradex.com">Terradex</a> with support from USEPA to help users further discover and learn details about cleanup sites. The Cleanup Deck\'s target users are the public, local government and regulatory agencies.  The Deck strives to:</p><ul><li>Be a one-stop Internet resource for state and federal cleanup site information,</li><li>Allow intuitive and standardized understanding of cleanup sites and institutional controls, and</li><li>Leverage cleanup sites with auxiliary map content leading to safe redevelopment and use.</li></ul><h1>THE CLEANUP DECK STEP-BY-STEP</h1><p class="section"><b>Step 1 - Visit the Web Site.</b>  Point your Internet browser to:</p><p class="link"><a href="http://cleanupdeck.terradex.com"><b>cleanupdeck.terradex.com</b></a></p><p>A mobile version can be tested at:</p><p class="link"><a href="http://cleanupdeck.terradex.com/mobile"><b>cleanupdeck.terradex.com/mobile</b></a></p><p class="section"><b>Step 2 - Request Login Permission.</b>The Cleanup Deck is a secure web service under development. A username and password can be requested at <a href="mailto:cleanupdeck@terradex.com">cleanupdeck@terradex.com</a>. With login credentials, you can begin exploring the features of the Cleanup Deck.</p> </div><div id="about-col2"><p class="section"><b>Step 3 - Focus the Map on Your Location.</b>  In the screenshot below are the basic features to select map layers to display, zoom to feature information, and search for sites.</p><p class="section"><b>Step 4 - Gain Information and Then Share It.</b>   The Cleanup Deck supplies information for the following features:</p><ul><li><b>Cleanup Sites. </b>About 400,000-cleanup sites summary cleanup status and agency information.</li><li><b>Institutional Controls. </b>About 10,000 institutional controls with summary land advisories, document links and contacts.</li><li><b>LandWatch. </b>Tracking land activities like excavations and building permits at cleanup sites applying ICs.</li><li><b>Activity & Use. </b>A folder for sensitive use layers such as California\'s day care.</li><li><b>Populations. </b>Helping community impact assessment by incorporating census information.</li><li><b>Property. </b>A national parcel layer with property information request services.</li><li><b>Renewable Energy. </b>National Renewable Energy Labs brings solar, wind and geothermal information.</li><li><b>Environmental Background. </b>Introduces wetlands, stream flow and other pertinent information.</li><li><b>Background Map. </b>Choose various map layers including street, map and aerial.</li></ul><p class="section"><b>Step 5 - Let Us Know if the Cleanup Deck Works for You.</b>  This is an USEPA tool looking to fit your application and need. This web resource is very much under development, and reliance is cautioned. Please provide feedback to <a href="mailto:cleanupdeck@terradex.com">cleanupdeck@terradex.com</a> or call Bob Wenzlau at 650-227-3251.</p></div></div><img src="https://img.skitch.com/20110802-dxq9aisba75f4k4txyqhq3gr9y.jpg">';
        </script>
    </head>
    <body>
		<div id="loading-mask"></div>
		<div id="loading">
		  <div class="loading-indicator">
			Loading Map...
		  </div>
		</div>
        <? if (isset($show_download))
        {
 ?>
            <div id="data">
                <p><b>Welcome to the Data Center</b></p><br>
                <p>The Data Center is your one-stop shop for access to up to date GIS data served on Cleanup Deck. Please take a moment to review the metadata available for each of the datasets listed. Metadata contains important information about the dataset, including source information, vintage, useage and access constraints, entity and attribute information, and appropriate scale range.</p>
                <br>
                <p>Before downloading the data, please click on the Preview link to verify your download.</p>
                <br><br>
                <div class="center">
                    <table cellspacing="0" id="datacenter-table" name="datacenter-table">
                        <thead>
                            <tr style="background:#eeeeee;">
                                <th style="width: 160px;">DATA SET</th>
                                <th>METADATA</th>
    <!--                                <th>PREVIEW</th>-->
                                <th>SHAPEFILE</th>
                                <th style="width: 60px;">KMZ</th>
                                <th style="width: 60px;">WMS</th>
                                <th style="width: 60px;">WFS</th>
                                <th style="width: 60px;">ArcGIS</th>
                            </tr>
                        </thead>
                        <tbody>
<? if (isset($_104d))
            { ?>
                        <tr>
                            <td>Cleanup Sites</td>
                            <td>Metadata</td>
<!--                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wms?service=WMS&version=1.1.0&request=GetMap&layers=pgterradex:ce_kml_data_joined_all&styles=&bbox=-176.667,-33.873,150.906,71.293&width=1027&height=330&srs=EPSG:4326&format=application/openlayers" target="_blank">Preview</a></td>-->
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wfs?request=GetFeature&amp;version=1.0.0&amp;typeName=pgterradex:ce_kml_data_joined_all&amp;outputFormat=SHAPE-ZIP&amp;maxFeatures=50" target="_blank">Shapefile</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wms/kml?layers=pgterradex:ce_kml_data_joined_all" target="_blank">KMZ</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/ows?service=wms&amp;version=1.1.1&amp;namespace=pgterradex&amp;request=GetCapabilities" target="_blank">WMS</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/ows?service=wfs&amp;version=1.1.0&amp;namespace=pgterradex&amp;request=GetCapabilities" target="_blank">WFS</a></td>
                            <td><a href="lyr/CleanupSites.lyr">Download Lyr</a></td>
                        </tr>
<? } ?>
<? if (isset($_103d))
            { ?>
                        <tr>
                            <td>Institutional Controls</td>
                            <td>Metadata</td>
<!--                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wms?service=WMS&version=1.1.0&request=GetMap&layers=pgterradex:Institutional%20Controls&styles=&bbox=-124.233,32.557,-72.897,44.672&width=1398&height=330&srs=EPSG:4326&format=application/openlayers" target="_blank">Preview</a></td>-->
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wfs?request=GetFeature&amp;version=1.0.0&amp;typeName=pgterradex:Institutional%20Controls&amp;outputFormat=SHAPE-ZIP&amp;maxFeatures=50" target="_blank">Shapefile</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wms/kml?layers=pgterradex:Institutional%20Controls" target="_blank">KMZ</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/ows?service=wms&amp;version=1.1.1&amp;namespace=pgterradex&amp;request=GetCapabilities" target="_blank">WMS</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/ows?service=wfs&amp;version=1.1.0&amp;namespace=pgterradex&amp;request=GetCapabilities" target="_blank">WFS</a></td>
                            <td><a href="lyr/InstitutionalControls.lyr">Download Lyr</a></td>
                        </tr>
<? } ?>
<? if (isset($_202d))
            { ?>
                        <tr>
                            <td>Landwatch Events</td>
                            <td>Metadata</td>
<!--                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wms?service=WMS&version=1.1.0&request=GetMap&layers=pgterradex:landwatch_eventdata_02&styles=&bbox=-176.667,-33.873,150.906,71.293&width=1027&height=330&srs=EPSG:4326&format=application/openlayers" target="_blank">Preview</a></td>-->
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wfs?request=GetFeature&amp;version=1.0.0&amp;typeName=pgterradex:landwatch_eventdata_02&amp;outputFormat=SHAPE-ZIP&amp;maxFeatures=50" target="_blank">Shapefile</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/wms/kml?layers=pgterradex:landwatch_eventdata_02" target="_blank">KMZ</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/ows?service=wms&amp;version=1.1.1&amp;namespace=pgterradex&amp;request=GetCapabilities" target="_blank">WMS</a></td>
                            <td><a href="http://<?= GEOSERVER_URL ?>:8080/geoserver/ows?service=wfs&amp;version=1.1.0&amp;namespace=pgterradex&amp;request=GetCapabilities" target="_blank">WFS</a></td>
                            <td><a href="lyr/CleanupSites.lyr">Download Lyr</a></td>
                        </tr>
<? } ?>
                    </tbody>
                </table>
            </div>
        </div>
<? } ?>
        <div id="cleanupSitesFilter">
            <p><span>Select a Filter and Zoom in to View Results</span>
                <br/>
                <select name="cleanupSite" id="cleanupSite" onchange="filterCleanupSites(document.getElementById('cleanupSite')[document.getElementById('cleanupSite').selectedIndex].value, this.value);"  width="180" style="width: 180px">
                    <option value="" selected>All Sites</option>
                    <?php
                    foreach ($datasets as $dataset)
                    {
                        //echo "<option value='" . $dataset['bbox'] . "'>" . $dataset['dataset_fu'] . "</option>";
                        echo "<option value='" . $dataset['tdxdatas_1'] . "'>" . $dataset['dataset_fu'] . "</option>";
                    }
                    ?>
                </select></p>
        </div>
        <!-- begin SnapEngage code -->
        <script type="text/javascript">
            (function() {
                var se = document.createElement('script'); se.type = 'text/javascript'; se.async = true;
                se.src = '//storage.googleapis.com/code.snapengage.com/js/ad12b8fb-17a0-41a3-8a08-41743852646f.js';
                var done = false;
                se.onload = se.onreadystatechange = function() {
                    if (!done&&(!this.readyState||this.readyState==='loaded'||this.readyState==='complete')){
                             done = true;
                             /* Place your SnapEngage JS API code below */
                     /* SnapEngage.allowChatSound(true); Example JS API: Enable sounds
                     for Visitors. */
                    }
                };
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(se, s);
                })();
        </script>
        <!-- end SnapEngage code -->
</body>
</html>