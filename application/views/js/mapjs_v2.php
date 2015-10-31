<script type="text/javascript">
document.domain = "<?php echo base_domain(); ?>";
var disclaimer, about, map, tree, selectCtrl, opacitySlider, dvpopup, legendContainer, permalinkProvider, mapLink, keyboardnav, searchMarker, clickMarker, layerRuler, gmap, gsat, ghyb, gphy, bingmap, bingsat, binghyb, ESRI_Imagery, ESRI_Topo, FWS_Wetlands, SSURGO_Soils, ESRI_USA_Median_Household_Income, icPolygons, cleanupSites, streamFlow, daycare, landwatchevent, landwatch_site, landwatch_alert_dtsc, landwatch_site_dtsc, landwatch_site_aps, landwatch_site_basf, landwatch_site_bp, landwatch_site_ge, landwatch_site_nysdec, landwatch_site_pge, landwatch_site_urs, landwatch_site_usepa, landwatch_site_wdig, landwatch_alert_aps, landwatch_alert_basf, landwatch_alert_bp, landwatch_alert_ge, landwatch_alert_nysdec, landwatch_alert_pge, landwatch_alert_urs, landwatch_alert_usepa, landwatch_alert_wdig, parcels, groundwaterplumes, naturalasbestos, csms_facility_polygon, federallands, us_geothermal, us_solarcsp, us_tilt_total, uswpc, balloonLayers,dc_site_de_view, dc_site_wv_view, dc_site_dtsc_view, dc_site_id_view, dc_excavation_with_de_view, dc_excavation_with_wv_view, dc_excavation_with_dtsc_view, dc_excavation_no_de_view, dc_excavation_no_wv_view, dc_excavation_no_dtsc_view;
var searchIcon = new OpenLayers.Icon('img/pinIcon.png', new OpenLayers.Size(32,32), new OpenLayers.Pixel(-35, -35));
var addressIcon = new OpenLayers.Icon('img/pinIcon.png', new OpenLayers.Size(32,32), new OpenLayers.Pixel(-16, -32));
var clickIcon = new OpenLayers.Icon('img/clickIcon.png', new OpenLayers.Size(32,32), null);
var mhi_state = new GeoExt.LegendImage({
    url: "img/mhi_states_legend.png"
});
<?php if (isset($_801)) { ?>
var dcManagementDE = '<iframe style="width:100%; height:100%;" src="https://feeder.terradex.com/landwatch_report/dc_excavation_mgtpanel/11/">';
<?php } ?>
<?php if (isset($_802)) { ?>
var dcManagementWV = '<iframe style="width:100%; height:100%;" src="https://feeder.terradex.com/landwatch_report/dc_excavation_mgtpanel/10/">';
<?php } ?>
<?php if (isset($_2000)) { ?>
var dcManagementID = '<iframe style="width:100%; height:100%;" src="https://feeder.terradex.com/landwatch_report/dc_excavation_mgtpanel/12/">';
<?php } ?>
var mhi_county = new GeoExt.LegendImage({
    url: "img/mhi_counties_legend.png"
});
var mhi_tract = new GeoExt.LegendImage({
    url: "img/mhi_tracts_legend.png"
});
var mhi_blockgroups = new GeoExt.LegendImage({
    url: "img/mhi_blockgroups_legend.png"
});
var parcelsLegend = new GeoExt.LegendImage({
    url: "img/parcelsLegend.png"
});

function addMHILegend() {
    if (map.getZoom() <= 4) {
        mhi_state.show();
        mhi_county.hide();
        mhi_tract.hide();
        mhi_blockgroups.hide();
        Ext.getCmp('legendPanel').doLayout();
    }
    if (map.getZoom() >= 5 && map.getZoom() <= 9) {
        mhi_state.hide();
        mhi_county.show();
        mhi_tract.hide();
        mhi_blockgroups.hide();
        Ext.getCmp('legendPanel').doLayout();
    }
    if (map.getZoom() >= 10 && map.getZoom() <= 11) {
        mhi_state.hide();
        mhi_county.hide();
        mhi_tract.show();
        mhi_blockgroups.hide();
        Ext.getCmp('legendPanel').doLayout();
    }
    if (map.getZoom() >= 12 && map.getZoom() <= 13) {
        mhi_state.hide();
        mhi_county.hide();
        mhi_tract.hide();
        mhi_blockgroups.show();
        Ext.getCmp('legendPanel').doLayout();
    }
}

function filterCleanupSites(name, bbox) {
    if (name === "") {
        cleanupSites.mergeNewParams({
            CQL_FILTER: "tdxdatasetid IS NOT NULL"
        });
    } else {
        // Refresh cleanupSites filtered by dataset_fu (name)
        cleanupSites.mergeNewParams({
            CQL_FILTER: 'tdxdatasetid=\'' + name + '\''
        });
        // Zoom to filtered dataset_fu extent
/** Cannot be implemented until data/extent validity issues are addressed
		var xMin = parseFloat(bbox.split(",")[0]);
		var yMin = parseFloat(bbox.split(",")[1]);
		var xMax = parseFloat(bbox.split(",")[2]);
		var yMax = parseFloat(bbox.split(",")[3]);
		map.zoomToExtent(new OpenLayers.Bounds(xMin, yMin, xMax, yMax).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")));
		*/
    }
};
var cleanupStore = new Ext.data.JsonStore({
    autoLoad: false,
    root: 'features',
    fields: [{
        name: "name",
        mapping: "properties.sitename"
    }, {
        name: "city",
        mapping: "properties.sitecity"
    }, {
        name: "state",
        mapping: "properties.sitestateshort"
    }, {
        name: "bbox",
        mapping: "properties.bbox"
    }],
    sortInfo: {
        field: 'name',
        direction: 'ASC'
    },
    url: 'utility/search'
});
// BEGIN GOOGLE GEOCODE COMBO BOX //
var geocodeStore = new Ext.data.JsonStore({
    autoLoad: false,
    root: 'results',
    fields: [{
        name: "address",
        mapping: "formatted_address"
    }, {
        name: "lat",
        mapping: "geometry.location.lat"
    }, {
        name: "lon",
        mapping: "geometry.location.lng"
    }],
    sortInfo: {
        field: 'address',
        direction: 'ASC'
    },
    proxy: new Ext.data.HttpProxy({
        url: 'geoexplorer_geocode.php'
    })
});
// END GOOGLE GEOCODE COMBO BOX //
Ext.onReady(function () {
    setTimeout(function () {
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({
            remove: true
        });
    }, 250);
    // Create a variable to hold our EXT Form Panel.
    // Assign various config options as seen.
    var login = new Ext.FormPanel({
        height: 250,
        width: 300,
        labelWidth: 80,
        url: 'user/login',
        frame: true,
        title: 'Please Login',
        defaultType: 'textfield',
        monitorValid: true,
        // Specific attributes for the text fields for username / password.
        // The "name" attribute defines the name of variables sent to the server.
        items: [{
            fieldLabel: 'Username',
            name: 'loginUsername',
            allowBlank: false
        }, {
            fieldLabel: 'Password',
            name: 'loginPassword',
            inputType: 'password',
            allowBlank: false
        }, {
            name: 'signon_landwatch',
            inputType: 'hidden',
            value: "1"
        }],

        // All the magic happens after the user clicks the button
        buttons: [{
            text: 'Login',
            formBind: true,
            // Function that fires when user clicks the button
            handler: function () {
                login.getForm().submit({
                    method: 'POST',
                    waitTitle: 'Connecting',
                    waitMsg: 'Sending data...',

                    // Functions that fire (success or failure) when the server responds.
                    // The one that executes is determined by the
                    // response that comes from login.asp as seen below. The server would
                    // actually respond with valid JSON,
                    // something like: response.write "{ success: true}" or
                    // response.write "{ success: false, errors: { reason: 'Login failed. Try again.' }}"
                    // depending on the logic contained within your server script.
                    // If a success occurs, the user is notified with an alert messagebox,
                    // and when they click "OK", they are redirected to whatever page
                    // you define as redirect.
                    success: function () {
                        //                        Ext.Msg.alert('Status', 'Login Successful!', function(btn, text){
                        //                            if (btn == 'ok'){
                        var redirect = '/';
                        window.location = redirect;
                        //                            }
                        //                        });
                    },

                    // Failure function, see comment above re: success and failure.
                    // You can see here, if login fails, it throws a messagebox
                    // at the user telling him / her as much.
                    failure: function (form, action) {
                        if (action.failureType == 'server') {
                            obj = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.alert('Login Failed!', obj.errors.reason);
                        } else {
                            Ext.Msg.alert('Warning!', 'Authentication server is unreachable : ' + action.response.responseText);
                        }
                        login.getForm().reset();
                    }
                });
            }
        }]
    });
    <? if (isset($show_download)) { ?>
    var datacentergrid = new Ext.ux.grid.TableGrid("datacenter-table", {
    stripeRows: true,
    renderTo: 'datacenter-table',
    viewConfig: {
        forceFit: true,
        scrollOffset: 2
    }
    });
    datacentergrid.render();
    <? } ?>
    OpenLayers.ProxyHost = "proxy.php?url=";
    OpenLayers.Lang.en = {
        'scale': "1 : ${scaleDenom}"
    };
    permalinkProvider = new GeoExt.state.PermalinkProvider({
        encodeType: false
    });
    Ext.state.Manager.setProvider(permalinkProvider);
    Ext.QuickTips.init();
    Ext.BLANK_IMAGE_URL = "http://extjs.cachefly.net/ext-3.3.1/resources/images/default/s.gif";
    keyboardnav = new OpenLayers.Control.KeyboardDefaults();
    var options = {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        controls: [/*new OpenLayers.Control.LayerSwitcher(), */new OpenLayers.Control.PanPanel(), new OpenLayers.Control.ZoomPanel(), new OpenLayers.Control.ScaleLine, new OpenLayers.Control.LoadingPanel(), new OpenLayers.Control.Attribution, keyboardnav],
        units: "m",
        numZoomLevels: 20,
        maxResolution: 156543.0339,
        maxExtent: new OpenLayers.Bounds(-20037508, -20037508, 20037508, 20037508.34)
    };
    map = new OpenLayers.Map(options);
    bingmap = new OpenLayers.Layer.Bing({
        key: "Ap6PC13ktG2lQOnnRUqi7bX6pPwkP93-fshU6LWlMeN503YdcZInCVMczp6k2joo",
        type: "Road",
        name: "Bing Streets",
        numZoomLevels: 20
    });
    bingsat = new OpenLayers.Layer.Bing({
        key: "Ap6PC13ktG2lQOnnRUqi7bX6pPwkP93-fshU6LWlMeN503YdcZInCVMczp6k2joo",
        type: "Aerial",
        name: "Bing Imagery",
        numZoomLevels: 20
    });
    binghyb = new OpenLayers.Layer.Bing({
        key: "Ap6PC13ktG2lQOnnRUqi7bX6pPwkP93-fshU6LWlMeN503YdcZInCVMczp6k2joo",
        type: "AerialWithLabels",
        name: "Bing Imagery With Labels",
        numZoomLevels: 20
    });
    gmap = new OpenLayers.Layer.Google("Google Streets", {
        numZoomLevels: 22
    });
    gsat = new OpenLayers.Layer.Google("Google Imagery", {
        type: google.maps.MapTypeId.SATELLITE,
        numZoomLevels: 22
    });
    ghyb = new OpenLayers.Layer.Google("Google Imagery With Labels", {
        type: google.maps.MapTypeId.HYBRID,
        numZoomLevels: 22
    });
    gphy = new OpenLayers.Layer.Google("Google Terrain", {
        type: google.maps.MapTypeId.TERRAIN
    });
    ESRI_Imagery = new OpenLayers.Layer.XYZ("ESRI Imagery", "http://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/${z}/${y}/${x}", {
        //attribution: "<span style='color: #FFFFFF;'>Tiles Courtesy of </span><a href='ttp://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer' target='_blank'>ESRI</a>",
        sphericalMercator: true,
	numZoomLevels: 18,
	isBaseLayer: true,
	visibility: false
    });
    ESRI_Topo = new OpenLayers.Layer.XYZ("ESRI Topo", "http://services.arcgisonline.com/ArcGIS/rest/services/USA_Topo_Maps/MapServer/tile/${z}/${y}/${x}", {
        sphericalMercator: true,
        numZoomLevels: 18,
        isBaseLayer: true,
        visibility: false
    });
    ESRI_USA_Median_Household_Income = new OpenLayers.Layer.XYZ("Median Household Income", "http://server.arcgisonline.com/ArcGIS/rest/services/Demographics/USA_Median_Household_Income/MapServer/tile/${z}/${y}/${x}", {
        //attribution: "<img src='img/mhi_counties_legend.png'></img>",
        sphericalMercator: true,
        numZoomLevels: 18,
        isBaseLayer: false,
        visibility: false,
        opacity: 0.75
    });
    //Override OL getFullRequestString to allow layer with a different CRS code
    //OpenLayers.Layer.WMS.prototype.getFullRequestString =
    //function (newParams, altUrl) {
    //    var projectionCode = this.projection.toString();
    //    this.params.SRS = (projectionCode == "none") ? null : "EPSG:102113";
    //    return OpenLayers.Layer.Grid.prototype.getFullRequestString.apply(
    //    this, arguments);
    //};
    FWS_Wetlands = new OpenLayers.Layer.WMS("FWS Wetlands", "http://137.227.242.85/ArcGIS/services/FWS_Wetlands_WMS/mapserver/wmsserver", {
        layers: '17',
        transparent: true,
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
    FWS_Wetlands.getFullRequestString = function (newParams, altUrl) {
        this.params.SRS = "EPSG:102113";
        return OpenLayers.Layer.Grid.prototype.getFullRequestString.apply(
        this, arguments);
    };
    us_geothermal = new OpenLayers.Layer.WMS("Geothermal Resource Potential", "http://mapsdb.nrel.gov/geoserver/wms", {
    	layers: 're_atlas:us_geothermal',
        transparent: true,
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true,
        opacity: 0.75
    });
    us_solarcsp = new OpenLayers.Layer.WMS("Concentrating Solar Power Radiation", "http://mapsdb.nrel.gov/geoserver/wms", {
    	layers: 're_atlas:us_solarcsp',
        transparent: true,
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true,
        opacity: 0.75
    });
    us_tilt_total = new OpenLayers.Layer.WMS("PV Solar Radiation - Tilt", "http://mapsdb.nrel.gov/geoserver/wms", {
    	layers: 're_atlas:us_tilt_total',
        transparent: true,
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true,
        opacity: 0.75
    });
    uswpc = new OpenLayers.Layer.WMS("Wind Resource Intensity", "http://mapsdb.nrel.gov/geoserver/wms", {
    	layers: 're_atlas:uswpc',
        transparent: true,
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true,
        opacity: 0.75
    });
    SSURGO_Soils = new OpenLayers.Layer.XYZ("SSURGO Soils", "http://server.arcgisonline.com/ArcGIS/rest/services/Specialty/Soil_Survey_Map/MapServer/tile/${z}/${y}/${x}", {
        sphericalMercator: true,
        numZoomLevels: 18,
        isBaseLayer: false,
        visibility: false,
        opacity: 0.75
    });
<?php if (isset($_103)) { ?>
    icPolygons = new OpenLayers.Layer.WMS("Institutional Controls", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
    	layers: 'pgterradex:institutional_controls',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'institutional_controls',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_104)) { ?>
    cleanupSites = new OpenLayers.Layer.WMS("Cleanup Sites", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:csms_facility',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'csms_facility',
        isBaseLayer: false,
        visibility: true,
        displayInLayerSwitcher: true
    });
<?php } ?>
    streamFlow = new OpenLayers.Layer.WMS("USGS Stream Flow", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:realstx',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'realstx',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php if (isset($_201)) { ?>
    daycare = new OpenLayers.Layer.WMS("Daycare (CA)", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:ca_daycare',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'ca_daycare',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_202)) { ?>
    landwatchevent = new OpenLayers.Layer.WMS("Landwatch Event", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_eventdata_02',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_eventdata_02',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_203)) { ?>
    landwatch_site = new OpenLayers.Layer.WMS("Landwatch Site", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_02',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_02',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_204)) { ?>
    landwatch_alert_dtsc = new OpenLayers.Layer.WMS("Landwatch Alerts DTSC", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_dtsc_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_dtsc_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_205)) { ?>
    landwatch_site_dtsc = new OpenLayers.Layer.WMS("Landwatch Sites DTSC", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_dtsc_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_dtsc_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_206)) { ?>
    landwatch_alert_aps = new OpenLayers.Layer.WMS("Landwatch Alerts APS", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_aps_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_aps_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_207)) { ?>
    landwatch_site_aps = new OpenLayers.Layer.WMS("Landwatch Sites APS", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_aps_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_aps_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_208)) { ?>
    landwatch_site_basf = new OpenLayers.Layer.WMS("Landwatch Sites BASF", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_basf_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_basf_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_224)) { ?>
    landwatch_alert_basf = new OpenLayers.Layer.WMS("Landwatch Alerts BASF", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_basf_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_basf_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_209)) { ?>
    landwatch_alert_bp= new OpenLayers.Layer.WMS("Landwatch Alerts BP", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_bp_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_bp_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_210)) { ?>
    landwatch_site_bp = new OpenLayers.Layer.WMS("Landwatch Sites BP", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_bp_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_bp_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_211)) { ?>
    landwatch_site_ge= new OpenLayers.Layer.WMS("Landwatch Sites GE", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_ge_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_ge_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_212)) { ?>
    landwatch_alert_ge = new OpenLayers.Layer.WMS("Landwatch Alerts GE", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_ge_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_ge_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_213)) { ?>
    landwatch_site_nysdec= new OpenLayers.Layer.WMS("Landwatch Sites NYSDEC", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_nysdec_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_nysdec_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_214)) { ?>
    landwatch_alert_nysdec = new OpenLayers.Layer.WMS("Landwatch Alerts NYSDEC", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_nysdec_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_nysdec_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_215)) { ?>
    landwatch_site_pge= new OpenLayers.Layer.WMS("Landwatch Sites PGE", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_pge_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_pge_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_216)) { ?>
    landwatch_alert_pge = new OpenLayers.Layer.WMS("Landwatch Alerts PGE", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_pge_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_pge_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_217)) { ?>
    landwatch_site_urs= new OpenLayers.Layer.WMS("Landwatch Sites URS", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_urs_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_urs_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_218)) { ?>
    landwatch_alert_urs = new OpenLayers.Layer.WMS("Landwatch Alerts URS", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_urs_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_urs_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_222)) { ?>
    landwatch_site_usepa= new OpenLayers.Layer.WMS("Landwatch Sites USEPA", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_usepa_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_usepa_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_221)) { ?>
    landwatch_alert_usepa = new OpenLayers.Layer.WMS("Landwatch Alerts USEPA", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_usepa_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_usepa_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_220)) { ?>
    landwatch_site_wdig= new OpenLayers.Layer.WMS("Landwatch Sites WDIG", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_site_wdig_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_site_wdig_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_219)) { ?>
    landwatch_alert_wdig = new OpenLayers.Layer.WMS("Landwatch Alerts WDIG", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:landwatch_alert_wdig_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'landwatch_alert_wdig_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_801)) { ?>
    dc_site_de = new OpenLayers.Layer.WMS("Dig Clean Sites DE", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_site_de_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_site_de_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
    <?php if (isset($_802)) { ?>
    dc_site_wv = new OpenLayers.Layer.WMS("Dig Clean Sites WV", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_site_wv_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_site_wv_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
        <?php if (isset($_803)) { ?>
    dc_site_dtsc = new OpenLayers.Layer.WMS("Dig Clean Sites DTSC", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_site_dtsc_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_site_dtsc_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
            <?php if (isset($_804)) { ?>
    dc_site_id = new OpenLayers.Layer.WMS("Dig Clean Sites ID", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_site_id_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_site_id_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_811)) { ?>
    dc_excavation_with_de = new OpenLayers.Layer.WMS("Advisory Transmitted DE", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_excavation_with_de_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_excavation_with_de_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
    <?php if (isset($_812)) { ?>
    dc_excavation_with_wv = new OpenLayers.Layer.WMS("Advisory Transmitted WV", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_excavation_with_wv_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_excavation_with_wv_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
 <?php if (isset($_813)) { ?>
    dc_excavation_with_dtsc = new OpenLayers.Layer.WMS("Advisory Transmitted DTSC", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_excavation_with_dtsc_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_excavation_with_dtsc_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
     <?php if (isset($_821)) { ?>
    dc_excavation_no_de = new OpenLayers.Layer.WMS("No Advisory DE", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_excavation_no_de_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_excavation_no_de_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_822)) { ?>
    dc_excavation_no_wv = new OpenLayers.Layer.WMS("No Advisory WV", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_excavation_no_wv_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_excavation_no_wv_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
    <?php if (isset($_823)) { ?>
    dc_excavation_no_dtsc = new OpenLayers.Layer.WMS("No Advisory DTSC", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:dc_excavation_no_dtsc_view',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'dc_excavation_no_dtsc_view',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_404)) { ?>
    groundwaterplumes = new OpenLayers.Layer.WMS("Groundwater Plumes", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:groundwater_plumes',
        transparent: true,
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_402)) { ?>
    naturalasbestos = new OpenLayers.Layer.WMS("Natural Asbestos", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:natural_asbestos',
        transparent: true,
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
<?php if (isset($_102)) { ?>
    csms_facility_polygon = new OpenLayers.Layer.WMS("Site Boundary", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:csms_facility_polygon',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'csms_facility_polygon',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php } ?>
    federallands = new OpenLayers.Layer.WMS("Federal Lands", "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms", {
        layers: 'pgterradex:Federal_Lands',
        transparent: true,
        format: "image/png"
    }, {
        layerid: 'Federal_Lands',
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: true
    });
<?php if (isset($_501)) { ?>
    parcels = new OpenLayers.Layer.WMS("Parcels", "http://feeder.terradex.com/proxy/corelogic/", {
        layers: 'parcelpoint',
        transparent: true,
        <?php if (isset($user_id)) { ?>
        USER_ID: "<?php echo $user_id ?>",
        <?php } ?>
        format: "image/png"
    }, {
        isBaseLayer: false,
        visibility: false,
        displayInLayerSwitcher: false
    });
<?php } ?>
    searchMarker = new OpenLayers.Layer.Markers("Search Marker", {
        displayInLayerSwitcher: false
    });
    clickMarker = new OpenLayers.Layer.Markers("Click Marker", {
        displayInLayerSwitcher: false
    });
    layerRuler = new OpenLayers.Layer.Vector("Measurements", {
        displayInLayerSwitcher: false
    });
    map.addLayers([
    	gmap,
        gphy,
        ghyb,
        gsat,
        ESRI_Topo,
        ESRI_Imagery,
        binghyb,
        bingsat,
        bingmap,
        <?php if (isset($_402)) { ?>naturalasbestos, <?php } ?>
        SSURGO_Soils,
        FWS_Wetlands,
        streamFlow,
        uswpc,
        us_tilt_total,
        us_solarcsp,
        us_geothermal,
         <?php if (isset($_501)){?>parcels, <?php } ?>
        federallands,
        ESRI_USA_Median_Household_Income,
        <?php if (isset($_201)){?>daycare, <?php } ?>
        <?php if (isset($_219)){?>landwatch_alert_wdig, <?php } ?>
        <?php if (isset($_220)){?>landwatch_site_wdig, <?php } ?>
        <?php if (isset($_221)){?>landwatch_alert_usepa, <?php } ?>
        <?php if (isset($_222)){?>landwatch_site_usepa, <?php } ?>
        <?php if (isset($_217)){?>landwatch_alert_urs, <?php } ?>
        <?php if (isset($_218)){?>landwatch_site_urs, <?php } ?>
        <?php if (isset($_215)){?>landwatch_alert_pge, <?php } ?>
        <?php if (isset($_216)){?>landwatch_site_pge, <?php } ?>
        <?php if (isset($_213)){?>landwatch_alert_nysdec, <?php } ?>
        <?php if (isset($_214)){?>landwatch_site_nysdec, <?php } ?>
        <?php if (isset($_211)){?>landwatch_alert_ge, <?php } ?>
        <?php if (isset($_212)){?>landwatch_site_ge, <?php } ?>
        <?php if (isset($_204)){?>landwatch_alert_dtsc, <?php } ?>
        <?php if (isset($_205)){?>landwatch_site_dtsc, <?php } ?>
        <?php if (isset($_209)){?>landwatch_alert_bp, <?php } ?>
        <?php if (isset($_210)){?>landwatch_site_bp, <?php } ?>
        <?php if (isset($_208)){?>landwatch_alert_basf, <?php } ?>
        <?php if (isset($_224)){?>landwatch_site_basf, <?php } ?>
        <?php if (isset($_206)){?>landwatch_alert_aps, <?php } ?>
        <?php if (isset($_207)){?>landwatch_site_aps, <?php } ?>
        <?php if (isset($_203)){?>landwatch_site, <?php } ?>
        <?php if (isset($_202)){?>landwatchevent, <?php } ?>
        <?php if (isset($_803)){?>dc_site_dtsc, <?php } ?>
        <?php if (isset($_804)){?>dc_site_id, <?php } ?>
        <?php if (isset($_802)){?>dc_site_wv, <?php } ?>
        <?php if (isset($_801)){?>dc_site_de, <?php } ?>
        <?php if (isset($_823)){?>dc_excavation_no_dtsc, <?php } ?>
        <?php if (isset($_813)){?>dc_excavation_with_dtsc, <?php } ?>
        <?php if (isset($_822)){?>dc_excavation_no_wv, <?php } ?>
        <?php if (isset($_812)){?>dc_excavation_with_wv, <?php } ?>
        <?php if (isset($_821)){?>dc_excavation_no_de, <?php } ?>
        <?php if (isset($_811)){?>dc_excavation_with_de, <?php } ?>
        <?php if (isset($_404)){?>groundwaterplumes, <?php } ?>
        <?php if (isset($_103)){?>icPolygons, <?php } ?>
        <?php if (isset($_102)){?>csms_facility_polygon, <?php } ?>
        <?php if (isset($_104)){?>cleanupSites, <?php } ?>
        searchMarker,
        clickMarker,
        layerRuler
    ]);
    var nodeCleanup = new Array();
    var nodeActivity = new Array();
    var nodeLandwatch = new Array();
    var nodeDigClean = new Array();
<?php if (isset($_104)) { ?>
    var nodeCleanupSites = {
        nodeType: "gx_layer",
        layer: cleanupSites,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeCleanup.push(nodeCleanupSites);
<?php } ?>
<?php if (isset($_102)) { ?>
    var nodeCsms_facility_polygon = {
        nodeType: "gx_layer",
        layer: csms_facility_polygon,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeCleanup.push(nodeCsms_facility_polygon);
<?php } ?>
<?php if (isset($_103)) { ?>
    var nodeIcPolygons = {
        nodeType: "gx_layer",
        layer: icPolygons,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeCleanup.push(nodeIcPolygons);
<?php } ?>
<?php if (isset($_404)) { ?>
    var nodeGroundwaterplumes = {
        nodeType: "gx_layer",
        layer: groundwaterplumes,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeCleanup.push(nodeGroundwaterplumes);
<?php } ?>
<?php if (isset($_201)) { ?>
    var nodeDaycare = {
        nodeType: "gx_layer",
        layer: daycare,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeActivity.push(nodeDaycare);
<?php } ?>
<?php if (isset($_202)) { ?>
    var nodeLandwatchevent = {
        nodeType: "gx_layer",
        layer: landwatchevent,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchevent);
<?php } ?>
<?php if (isset($_203)) { ?>
    var nodeLandwatchSite = {
        nodeType: "gx_layer",
        layer: landwatch_site,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSite);
<?php } ?>
<?php if (isset($_207)) { ?>
    var nodeLandwatchSiteAps = {
        nodeType: "gx_layer",
        layer: landwatch_site_aps,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteAps);
<?php } ?>
<?php if (isset($_206)) { ?>
    var nodeLandwatchAlertAps = {
        nodeType: "gx_layer",
        layer: landwatch_alert_aps,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertAps);
<?php } ?>
<?php if (isset($_224)) { ?>
    var nodeLandwatchSiteBasf = {
        nodeType: "gx_layer",
        layer: landwatch_site_basf,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteBasf);
<?php } ?>
<?php if (isset($_208)) { ?>
    var nodeLandwatchAlertBasf = {
        nodeType: "gx_layer",
        layer: landwatch_alert_basf,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertBasf);
<?php } ?>
<?php if (isset($_210)) { ?>
    var nodeLandwatchSiteBp = {
        nodeType: "gx_layer",
        layer: landwatch_site_bp,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteBp);
<?php } ?>
<?php if (isset($_209)) { ?>
    var nodeLandwatchAlertBp = {
        nodeType: "gx_layer",
        layer: landwatch_alert_bp,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertBp);
<?php } ?>
<?php if (isset($_205)) { ?>
    var nodeLandwatchSiteDtsc = {
        nodeType: "gx_layer",
        layer: landwatch_site_dtsc,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteDtsc);
<?php } ?>
<?php if (isset($_204)) { ?>
    var nodeLandwatchAlertDtsc = {
        nodeType: "gx_layer",
        layer: landwatch_alert_dtsc,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertDtsc);
<?php } ?>
<?php if (isset($_212)) { ?>
    var nodeLandwatchSiteGe = {
        nodeType: "gx_layer",
        layer: landwatch_site_ge,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteGe);
<?php } ?>
<?php if (isset($_211)) { ?>
    var nodeLandwatchAlertGe = {
        nodeType: "gx_layer",
        layer: landwatch_alert_ge,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertGe);
<?php } ?>
<?php if (isset($_214)) { ?>
    var nodeLandwatchSiteNysdec = {
        nodeType: "gx_layer",
        layer: landwatch_site_nysdec,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteNysdec);
<?php } ?>
<?php if (isset($_213)) { ?>
    var nodeLandwatchAlertNysdec = {
        nodeType: "gx_layer",
        layer: landwatch_alert_nysdec,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertNysdec);
<?php } ?>
<?php if (isset($_216)) { ?>
    var nodeLandwatchSitePge = {
        nodeType: "gx_layer",
        layer: landwatch_site_pge,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSitePge);
<?php } ?>
<?php if (isset($_215)) { ?>
    var nodeLandwatchAlertPge = {
        nodeType: "gx_layer",
        layer: landwatch_alert_pge,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertPge);
<?php } ?>
<?php if (isset($_218)) { ?>
    var nodeLandwatchSiteUrs = {
        nodeType: "gx_layer",
        layer: landwatch_site_urs,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteUrs);
<?php } ?>
<?php if (isset($_217)) { ?>
    var nodeLandwatchAlertUrs = {
        nodeType: "gx_layer",
        layer: landwatch_alert_urs,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertUrs);
<?php } ?>
<?php if (isset($_222)) { ?>
    var nodeLandwatchSiteUsepa = {
        nodeType: "gx_layer",
        layer: landwatch_site_usepa,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteUsepa);
<?php } ?>
<?php if (isset($_221)) { ?>
    var nodeLandwatchAlertUsepa = {
        nodeType: "gx_layer",
        layer: landwatch_alert_usepa,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertUsepa);
<?php } ?>
<?php if (isset($_220)) { ?>
    var nodeLandwatchSiteWdig = {
        nodeType: "gx_layer",
        layer: landwatch_site_wdig,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchSiteWdig);
<?php } ?>
<?php if (isset($_219)) { ?>
    var nodeLandwatchAlertWdig = {
        nodeType: "gx_layer",
        layer: landwatch_alert_wdig,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeLandwatch.push(nodeLandwatchAlertWdig);
<?php } ?>
<?php if (isset($_801)) { ?>
    var nodeDcSiteDe = {
        nodeType: "gx_layer",
        layer: dc_site_de,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcSiteDe);
<?php } ?>
<?php if (isset($_802)) { ?>
    var nodeDcSiteWv = {
        nodeType: "gx_layer",
        layer: dc_site_wv,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcSiteWv);
<?php } ?>
<?php if (isset($_803)) { ?>
    var nodeDcSiteDtsc = {
        nodeType: "gx_layer",
        layer: dc_site_dtsc,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcSiteDtsc);
<?php } ?>
<?php if (isset($_804)) { ?>
    var nodeDcSiteId = {
        nodeType: "gx_layer",
        layer: dc_site_id,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcSiteId);
<?php } ?>
<?php if (isset($_811)) { ?>
    var nodeDcExcavationWithDe = {
        nodeType: "gx_layer",
        layer: dc_excavation_with_de,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcExcavationWithDe);
<?php } ?>
<?php if (isset($_812)) { ?>
    var nodeDcExcavationWithWv = {
        nodeType: "gx_layer",
        layer: dc_excavation_with_wv,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcExcavationWithWv);
<?php } ?>
<?php if (isset($_813)) { ?>
    var nodeDcExcavationWithDtsc = {
        nodeType: "gx_layer",
        layer: dc_excavation_with_dtsc,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcExcavationWithDtsc);
<?php } ?>
<?php if (isset($_821)) { ?>
    var nodeDcExcavationNoDe = {
        nodeType: "gx_layer",
        layer: dc_excavation_no_de,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcExcavationNoDe);
<?php } ?>
<?php if (isset($_822)) { ?>
    var nodeDcExcavationNoWv = {
        nodeType: "gx_layer",
        layer: dc_excavation_no_wv,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcExcavationNoWv);
<?php } ?>
<?php if (isset($_823)) { ?>
    var nodeDcExcavationNoDtsc = {
        nodeType: "gx_layer",
        layer: dc_excavation_no_dtsc,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeDigClean.push(nodeDcExcavationNoDtsc);
<?php } ?>

    var treeConfig = new Array();
    if (nodeCleanup.length != 0) {
        treeConfig.push({
            text: "<b>&nbsp;Cleanup</b>",
            expanded: true,
            singleClickExpand: true,
            children: nodeCleanup
        });
    }
    if (nodeDigClean.length != 0) {
        treeConfig.push({
            text: "<b>&nbsp;Dig Clean</b>",
            expanded: true,
            singleClickExpand: true,
            children: nodeDigClean
        });
    }
    if (nodeLandwatch.length != 0) {
        treeConfig.push({
            text: "<b>&nbsp;Landwatch</b>",
            expanded: true,
            singleClickExpand: true,
            children: nodeLandwatch
        });
    }
    if (nodeActivity.length != 0) {
        treeConfig.push({
            text: "<b>&nbsp;Activity & Use</b>",
            expanded: true,
            singleClickExpand: true,
            children: nodeActivity
        });
    }
    treeConfig.push({
        text: "<b>&nbsp;Populations</b>",
        expanded: true,
        singleClickExpand: true,
        children: [{
            nodeType: "gx_layer",
            layer: ESRI_USA_Median_Household_Income,
            listeners: {
                click: function () {
                    opacitySlider.setLayer(this.layer);
                }
            }
        }]
    });
    var nodeProperty = new Array();
    nodeProperty.push({
        nodeType: "gx_layer",
        layer: federallands,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    })
<?php if (isset($_501)) { ?>
    var nodeParcel = {
        nodeType: "gx_layer",
        layer: parcels,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeProperty.push(nodeParcel);
<?php } ?>
    var nodeRenewable = new Array();
    nodeRenewable.push({
        nodeType: "gx_layer",
        layer: us_geothermal,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    })
    nodeRenewable.push({
        nodeType: "gx_layer",
        layer: us_solarcsp,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    })
    nodeRenewable.push({
        nodeType: "gx_layer",
        layer: us_tilt_total,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    })
    nodeRenewable.push({
        nodeType: "gx_layer",
        layer: uswpc,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    })
    treeConfig.push({
        text: "<b>&nbsp;Property</b>",
        expanded: true,
        singleClickExpand: true,
        children: nodeProperty
    });
    treeConfig.push({
        text: "<b>&nbsp;Renewable Energy Potential</b>",
        expanded: true,
        singleClickExpand: true,
        children: nodeRenewable
    })
    var nodeEnvironmental = [{
        nodeType: "gx_layer",
        layer: streamFlow,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    }, {
        nodeType: "gx_layer",
        layer: FWS_Wetlands,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    }, {
        nodeType: "gx_layer",
        layer: SSURGO_Soils,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    }];
<?php if (isset($_402)) { ?>
    var nodeAsbestos = {
        nodeType: "gx_layer",
        layer: naturalasbestos,
        listeners: {
            click: function () {
                opacitySlider.setLayer(this.layer);
            }
        }
    };
    nodeEnvironmental.push(nodeAsbestos);
<?php } ?>
    treeConfig.push({
        text: "<b>&nbsp;Environmental Background</b>",
        expanded: true,
        singleClickExpand: true,
        children: nodeEnvironmental
    });
    treeConfig.push({
        text: "<b>&nbsp;Background Maps</b>",
        expanded: true,
        singleClickExpand: true,
        children: [{
            text: "&nbsp;Google",
            expanded: true,
            singleClickExpand: true,
            children: [{
                nodeType: "gx_layer",
                layer: gmap
                //icon: "img/icon-osm.png"
            }, {
                nodeType: "gx_layer",
                layer: gsat
            }, {
                nodeType: "gx_layer",
                layer: ghyb
            }, {
                nodeType: "gx_layer",
                layer: gphy
            }]
        }, {
            text: "&nbsp;Bing",
            expanded: false,
            singleClickExpand: true,
            children: [{
                nodeType: "gx_layer",
                layer: bingmap
            }, {
                nodeType: "gx_layer",
                layer: bingsat
            }, {
                nodeType: "gx_layer",
                layer: binghyb
            }]
        }, {
            text: "&nbsp;ESRI",
            expanded: false,
            singleClickExpand: true,
            children: [{
                nodeType: "gx_layer",
                layer: ESRI_Imagery
            }, {
                nodeType: "gx_layer",
                layer: ESRI_Topo
            }]
        }]
    });

    // Build our custom WMSGetFeatureInfo Control
    function cleanupSitesHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('cleanupSites_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('cleanupSites_Info').innerHTML = "";
        }
    };
    function csms_facility_polygonHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('csms_facility_polygon_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('csms_facility_polygon_Info').innerHTML = "";
        }
    };
    function icPolygonsHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('icPolygons_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('icPolygons_Info').innerHTML = "";
        }
    };
    function groundwaterplumesHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('groundwaterplumes_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('groundwaterplumes_Info').innerHTML = "";
        }
    };
    function landwatcheventHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatchevent_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatchevent_Info').innerHTML = "";
        }
    };
    function landwatch_siteHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_Info').innerHTML = "";
        }
    };
    function landwatch_site_apsHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_aps_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_aps_Info').innerHTML = "";
        }
    };
    function landwatch_alert_apsHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_aps_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_aps_Info').innerHTML = "";
        }
    };
    function landwatch_site_basfHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_basf_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_basf_Info').innerHTML = "";
        }
    };
    function landwatch_alert_basfHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_basf_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_basf_Info').innerHTML = "";
        }
    };
    function landwatch_site_bpHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_bp_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_bp_Info').innerHTML = "";
        }
    };
    function landwatch_alert_bpHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_bp_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_bp_Info').innerHTML = "";
        }
    };
    function landwatch_site_dtscHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_dtsc_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_dtsc_Info').innerHTML = "";
        }
    };
    function landwatch_alert_dtscHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_dtsc_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_dtsc_Info').innerHTML = "";
        }
    };
    function landwatch_site_geHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_ge_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_ge_Info').innerHTML = "";
        }
    };
    function landwatch_alert_geHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_ge_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_ge_Info').innerHTML = "";
        }
    };
    function landwatch_site_nysdecHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_nysdec_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_nysdec_Info').innerHTML = "";
        }
    };
    function landwatch_alert_nysdecHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_nysdec_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_nysdec_Info').innerHTML = "";
        }
    };
    function landwatch_site_pgeHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_pge_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_pge_Info').innerHTML = "";
        }
    };
    function landwatch_alert_pgeHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_pge_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_pge_Info').innerHTML = "";
        }
    };
    function landwatch_site_ursHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_urs_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_urs_Info').innerHTML = "";
        }
    };
    function landwatch_alert_ursHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_urs_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_urs_Info').innerHTML = "";
        }
    };
    function landwatch_site_usepaHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_usepa_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_usepa_Info').innerHTML = "";
        }
    };
    function landwatch_alert_usepaHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_usepa_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_usepa_Info').innerHTML = "";
        }
    };
    function landwatch_site_wdigHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_site_wdig_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_site_wdig_Info').innerHTML = "";
        }
    };
    function landwatch_alert_wdigHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('landwatch_alert_wdig_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('landwatch_alert_wdig_Info').innerHTML = "";
        }
    };
    function daycareHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('daycare_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('daycare_Info').innerHTML = "";
        }
    };
    function federallandsHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('federallands_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('federallands_Info').innerHTML = "";
        }
    };
    function parcelsHTML(response) {
            document.getElementById('parcels_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
    };
    function us_geothermalHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('us_geothermal_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
            document.getElementById('us_geothermal_Info').innerHTML = "";
        }
    };
    function us_solarcspHTML(response) {
	if (response.responseText.length > 687) {
		document.getElementById('us_solarcsp_Info').innerHTML = response.responseText;
		Ext.getCmp("southPanel").expand();
	}
	if (response.responseText.length <= 687) {
		document.getElementById('us_solarcsp_Info').innerHTML = "";
	}
    };
    function us_tilt_totalHTML(response) {
            if (response.responseText.length > 687) {
                    document.getElementById('us_tilt_total_Info').innerHTML = response.responseText;
                    Ext.getCmp("southPanel").expand();
            }
            if (response.responseText.length <= 687) {
                    document.getElementById('us_tilt_total_Info').innerHTML = "";
            }
    };
    function uswpcHTML(response) {
            if (response.responseText.length > 687) {
                    document.getElementById('uswpc_Info').innerHTML = response.responseText;
                    Ext.getCmp("southPanel").expand();
            }
            if (response.responseText.length <= 687) {
                    document.getElementById('uswpc_Info').innerHTML = "";
            }
    };
    function streamFlowHTML(response) {
        if (response.responseText.length > 687) {
            //alert(response.responseText.length);
            document.getElementById('streamFlow_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
            document.getElementById('streamFlow_Info').innerHTML = "";
        }
    };
    function dc_site_deHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_site_de_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_site_de_Info').innerHTML = "";
        }
    };
        function dc_site_wvHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_site_wv_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_site_wv_Info').innerHTML = "";
        }
    };
        function dc_site_dtscHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_site_dtsc_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_site_dtsc_Info').innerHTML = "";
        }
    };
        function dc_site_idHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_site_id_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_site_id_Info').innerHTML = "";
        }
    };
    function dc_excavation_with_deHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_excavation_with_de_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_excavation_with_de_Info').innerHTML = "";
        }
    };
        function dc_excavation_with_wvHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_excavation_with_wv_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_excavation_with_wv_Info').innerHTML = "";
        }
    };
        function dc_excavation_with_dtscHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_excavation_with_dtsc_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_excavation_with_dtsc_Info').innerHTML = "";
        }
    };
     function dc_excavation_no_deHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_excavation_no_de_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_excavation_no_de_Info').innerHTML = "";
        }
    };
            function dc_excavation_no_wvHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_excavation_no_wv_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_excavation_no_wv_Info').innerHTML = "";
        }
    };
        function dc_excavation_no_dtscHTML(response) {
        if (response.responseText.length > 687) {
            document.getElementById('dc_excavation_no_dtsc_Info').innerHTML = response.responseText;
            Ext.getCmp("southPanel").expand();
        }
        if (response.responseText.length <= 687) {
           document.getElementById('dc_excavation_no_dtsc_Info').innerHTML = "";
        }
    };

    selectCtrl = new OpenLayers.Control.Click({
        trigger: function (event) {
            var maploc = map.getLonLatFromViewPortPx(event.xy);
            clickMarker.clearMarkers();
            clickMarker.addMarker(new OpenLayers.Marker(maploc,clickIcon));
            document.getElementById('responseText').innerHTML = "";
            document.getElementById('cleanupSites_Info').innerHTML = "";
            document.getElementById('csms_facility_polygon_Info').innerHTML = "";
            document.getElementById('icPolygons_Info').innerHTML = "";
            document.getElementById('groundwaterplumes_Info').innerHTML = "";
            document.getElementById('landwatchevent_Info').innerHTML = "";
            document.getElementById('landwatch_site_Info').innerHTML = "";
            document.getElementById('landwatch_site_aps_Info').innerHTML = "";
            document.getElementById('landwatch_alert_aps_Info').innerHTML = "";
            document.getElementById('landwatch_site_basf_Info').innerHTML = "";
            document.getElementById('landwatch_alert_basf_Info').innerHTML = "";
            document.getElementById('landwatch_site_bp_Info').innerHTML = "";
            document.getElementById('landwatch_alert_bp_Info').innerHTML = "";
            document.getElementById('landwatch_site_dtsc_Info').innerHTML = "";
            document.getElementById('landwatch_alert_dtsc_Info').innerHTML = "";
            document.getElementById('landwatch_site_ge_Info').innerHTML = "";
            document.getElementById('landwatch_alert_ge_Info').innerHTML = "";
            document.getElementById('landwatch_site_nysdec_Info').innerHTML = "";
            document.getElementById('landwatch_alert_nysdec_Info').innerHTML = "";
            document.getElementById('landwatch_site_pge_Info').innerHTML = "";
            document.getElementById('landwatch_alert_pge_Info').innerHTML = "";
            document.getElementById('landwatch_site_urs_Info').innerHTML = "";
            document.getElementById('landwatch_alert_urs_Info').innerHTML = "";
            document.getElementById('landwatch_site_usepa_Info').innerHTML = "";
            document.getElementById('landwatch_alert_usepa_Info').innerHTML = "";
            document.getElementById('landwatch_site_wdig_Info').innerHTML = "";
            document.getElementById('landwatch_alert_wdig_Info').innerHTML = "";
            document.getElementById('daycare_Info').innerHTML = "";
            document.getElementById('federallands_Info').innerHTML = "";
            document.getElementById('parcels_Info').innerHTML = "";
            document.getElementById('us_geothermal_Info').innerHTML = "";
            document.getElementById('us_solarcsp_Info').innerHTML = "";
            document.getElementById('us_tilt_total_Info').innerHTML = "";
            document.getElementById('uswpc_Info').innerHTML = "";
            document.getElementById('streamFlow_Info').innerHTML = "";
            document.getElementById('dc_site_de_Info').innerHTML = "";
            document.getElementById('dc_site_wv_Info').innerHTML = "";
            document.getElementById('dc_site_dtsc_Info').innerHTML = "";
            document.getElementById('dc_site_id_Info').innerHTML = "";
            document.getElementById('dc_excavation_with_de_Info').innerHTML = "";
            document.getElementById('dc_excavation_with_wv_Info').innerHTML = "";
            document.getElementById('dc_excavation_with_dtsc_Info').innerHTML = "";
            document.getElementById('dc_excavation_no_de_Info').innerHTML = "";
            document.getElementById('dc_excavation_no_wv_Info').innerHTML = "";
            document.getElementById('dc_excavation_no_dtsc_Info').innerHTML = "";
            <?php if (isset($_104)){?>
            if (cleanupSites.getVisibility() == true) {
                document.getElementById('cleanupSites_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_102)){?>
            if (csms_facility_polygon.getVisibility() == true) {
                document.getElementById('csms_facility_polygon_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_103)){?>
            if (icPolygons.getVisibility() == true) {
                document.getElementById('icPolygons_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_404)){?>
            if (groundwaterplumes.getVisibility() == true) {
                document.getElementById('groundwaterplumes_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_202)){?>
            if (landwatchevent.getVisibility() == true) {
                document.getElementById('landwatchevent_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_203)){?>
            if (landwatch_site.getVisibility() == true) {
                document.getElementById('landwatch_site_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_207)){?>
	    if (landwatch_site_aps.getVisibility() == true) {
                document.getElementById('landwatch_site_aps_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_206)){?>
            if (landwatch_alert_aps.getVisibility() == true) {
                document.getElementById('landwatch_alert_aps_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_224)){?>
	    if (landwatch_site_basf.getVisibility() == true) {
                document.getElementById('landwatch_site_basf_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_208)){?>
	    if (landwatch_alert_basf.getVisibility() == true) {
                document.getElementById('landwatch_alert_basf_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_210)){?>
	    if (landwatch_site_bp.getVisibility() == true) {
                document.getElementById('landwatch_site_bp_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_209)){?>
	    if (landwatch_alert_bp.getVisibility() == true) {
                document.getElementById('landwatch_alert_bp_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_205)){?>
	    if (landwatch_site_dtsc.getVisibility() == true) {
                document.getElementById('landwatch_site_dtsc_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_204)){?>
	    if (landwatch_alert_dtsc.getVisibility() == true) {
                document.getElementById('landwatch_alert_dtsc_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_212)){?>
	    if (landwatch_site_ge.getVisibility() == true) {
                document.getElementById('landwatch_site_ge_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_211)){?>
	    if (landwatch_alert_ge.getVisibility() == true) {
                document.getElementById('landwatch_alert_ge_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_214)){?>
	    if (landwatch_site_nysdec.getVisibility() == true) {
                document.getElementById('landwatch_site_nysdec_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_213)){?>
	    if (landwatch_alert_nysdec.getVisibility() == true) {
                document.getElementById('landwatch_alert_nysdec_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_216)){?>
	    if (landwatch_site_pge.getVisibility() == true) {
                document.getElementById('landwatch_site_pge_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_215)){?>
	    if (landwatch_alert_pge.getVisibility() == true) {
                document.getElementById('landwatch_alert_pge_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_218)){?>
	    if (landwatch_site_urs.getVisibility() == true) {
                document.getElementById('landwatch_site_urs_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_217)){?>
            if (landwatch_alert_urs.getVisibility() == true) {
                document.getElementById('landwatch_alert_urs_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_222)){?>
	    if (landwatch_site_usepa.getVisibility() == true) {
                document.getElementById('landwatch_site_usepa_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_221)){?>
	    if (landwatch_alert_usepa.getVisibility() == true) {
                document.getElementById('landwatch_alert_usepa_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_220)){?>
	    if (landwatch_site_wdig.getVisibility() == true) {
                document.getElementById('landwatch_site_wdig_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_219)){?>
	    if (landwatch_alert_wdig.getVisibility() == true) {
                document.getElementById('landwatch_alert_wdig_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_201)){?>
	    if (daycare.getVisibility() == true) {
                document.getElementById('daycare_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            if (federallands.getVisibility() == true) {
                document.getElementById('federallands_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php if (isset($_501)){?>
            if (parcels.getVisibility() == true) {
                document.getElementById('parcels_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            if (us_geothermal.getVisibility() == true) {
                document.getElementById('us_geothermal_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            if (us_solarcsp.getVisibility() == true) {
                document.getElementById('us_solarcsp_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            if (us_tilt_total.getVisibility() == true) {
                document.getElementById('us_tilt_total_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            if (uswpc.getVisibility() == true) {
                document.getElementById('uswpc_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            if (streamFlow.getVisibility() == true) {
                document.getElementById('streamFlow_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php if (isset($_801)){?>
	    if (dc_site_de.getVisibility() == true) {
                document.getElementById('dc_site_de_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_811)){?>
	    if (dc_excavation_with_de.getVisibility() == true) {
                document.getElementById('dc_excavation_with_de_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_821)){?>
	    if (dc_excavation_no_de.getVisibility() == true) {
                document.getElementById('dc_excavation_no_de_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_802)){?>
	    if (dc_site_wv.getVisibility() == true) {
                document.getElementById('dc_site_wv_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_812)){?>
	    if (dc_excavation_with_wv.getVisibility() == true) {
                document.getElementById('dc_excavation_with_wv_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_822)){?>
	    if (dc_excavation_no_wv.getVisibility() == true) {
                document.getElementById('dc_excavation_no_wv_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_803)){?>
	    if (dc_site_dtsc.getVisibility() == true) {
                document.getElementById('dc_site_dtsc_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_813)){?>
	    if (dc_excavation_with_dtsc.getVisibility() == true) {
                document.getElementById('dc_excavation_with_dtsc_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_823)){?>
	    if (dc_excavation_no_dtsc.getVisibility() == true) {
                document.getElementById('dc_excavation_no_dtsc_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_804)){?>
	    if (dc_site_id.getVisibility() == true) {
                document.getElementById('dc_site_id_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
            }
            <?php } ?>
            <?php if (isset($_104)){?>
            var cleanupSites_url = cleanupSites.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:csms_facility'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (cleanupSites.getVisibility() == true) {
                OpenLayers.loadURL(cleanupSites_url, '', this, cleanupSitesHTML);
            }
            <?php } ?>

            <?php if (isset($_102)){?>
            var csms_facility_polygon_url = csms_facility_polygon.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:csms_facility_polygon'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (csms_facility_polygon.getVisibility() == true) {
                OpenLayers.loadURL(csms_facility_polygon_url, '', this, csms_facility_polygonHTML);
            }
            <?php } ?>

            <?php if (isset($_103)){?>
            var icPolygons_url = icPolygons.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:institutional_controls'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (icPolygons.getVisibility() == true) {
                OpenLayers.loadURL(icPolygons_url, '', this, icPolygonsHTML);
            }
            <?php } ?>

            <?php if (isset($_404)){?>
            var groundwaterplumes_url = groundwaterplumes.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:groundwater_plumes'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (groundwaterplumes.getVisibility() == true) {
                OpenLayers.loadURL(groundwaterplumes_url, '', this, groundwaterplumesHTML);
            }
            <?php } ?>

            <?php if (isset($_202)){?>
            var landwatchevent_url = landwatchevent.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_eventdata_02'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatchevent.getVisibility() == true) {
                OpenLayers.loadURL(landwatchevent_url, '', this, landwatcheventHTML);
            }
            <?php } ?>

            <?php if (isset($_203)){?>
            var landwatch_site_url = landwatch_site.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_02'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_url, '', this, landwatch_siteHTML);
            }
            <?php } ?>

            <?php if (isset($_207)){?>
            var landwatch_site_aps_url = landwatch_site_aps.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_aps_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_aps.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_aps_url, '', this, landwatch_site_apsHTML);
            }
            <?php } ?>

            <?php if (isset($_206)){?>
            var landwatch_alert_aps_url = landwatch_alert_aps.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_aps_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_aps.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_aps_url, '', this, landwatch_alert_apsHTML);
            }
            <?php } ?>

            <?php if (isset($_208)){?>
	    var landwatch_site_basf_url = landwatch_site_basf.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_basf_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_basf.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_basf_url, '', this, landwatch_site_basfHTML);
            }
            <?php } ?>

            <?php if (isset($_224)){?>
	    var landwatch_alert_basf_url = landwatch_alert_basf.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_basf_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_basf.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_basf_url, '', this, landwatch_alert_basfHTML);
            }
            <?php } ?>

            <?php if (isset($_210)){?>
	    var landwatch_site_bp_url = landwatch_site_bp.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_bp_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_bp.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_bp_url, '', this, landwatch_site_bpHTML);
            }
            <?php } ?>

            <?php if (isset($_209)){?>
	    var landwatch_alert_bp_url = landwatch_alert_bp.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_bp_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_bp.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_bp_url, '', this, landwatch_alert_bpHTML);
            }
            <?php } ?>

            <?php if (isset($_205)){?>
            var landwatch_site_dtsc_url = landwatch_site_dtsc.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_dtsc_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_dtsc.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_dtsc_url, '', this, landwatch_site_dtscHTML);
            }
            <?php } ?>

            <?php if (isset($_204)){?>
            var landwatch_alert_dtsc_url = landwatch_alert_dtsc.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_dtsc_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_dtsc.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_dtsc_url, '', this, landwatch_alert_dtscHTML);
            }
            <?php } ?>

            <?php if (isset($_211)){?>
            var landwatch_site_ge_url = landwatch_site_ge.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_ge_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_ge.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_ge_url, '', this, landwatch_site_geHTML);
            }
            <?php } ?>

            <?php if (isset($_212)){?>
            var landwatch_alert_ge_url = landwatch_alert_ge.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_ge_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_ge.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_ge_url, '', this, landwatch_alert_geHTML);
            }
            <?php } ?>

            <?php if (isset($_214)){?>
            var landwatch_site_nysdec_url = landwatch_site_nysdec.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_nysdec_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_nysdec.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_nysdec_url, '', this, landwatch_site_nysdecHTML);
            }
            <?php } ?>

            <?php if (isset($_213)){?>
            var landwatch_alert_nysdec_url = landwatch_alert_nysdec.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_nysdec_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_nysdec.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_nysdec_url, '', this, landwatch_alert_nysdecHTML);
            }
            <?php } ?>

            <?php if (isset($_216)){?>
            var landwatch_site_pge_url = landwatch_site_pge.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_pge_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_pge.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_pge_url, '', this, landwatch_site_pgeHTML);
            }
            <?php } ?>

            <?php if (isset($_215)){?>
            var landwatch_alert_pge_url = landwatch_alert_pge.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_pge_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_pge.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_pge_url, '', this, landwatch_alert_pgeHTML);
            }
            <?php } ?>

            <?php if (isset($_218)){?>
            var landwatch_site_urs_url = landwatch_site_urs.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_urs_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_urs.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_urs_url, '', this, landwatch_site_ursHTML);
            }
	    <?php } ?>

            <?php if (isset($_217)){?>
            var landwatch_alert_urs_url = landwatch_alert_urs.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_urs_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_urs.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_urs_url, '', this, landwatch_alert_ursHTML);
            }
            <?php } ?>

            <?php if (isset($_222)){?>
            var landwatch_site_usepa_url = landwatch_site_usepa.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_usepa_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_usepa.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_usepa_url, '', this, landwatch_site_usepaHTML);
            }
            <?php } ?>

            <?php if (isset($_221)){?>
            var landwatch_alert_usepa_url = landwatch_alert_usepa.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_usepa_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_usepa.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_usepa_url, '', this, landwatch_alert_usepaHTML);
            }
            <?php } ?>

            <?php if (isset($_220)){?>
            var landwatch_site_wdig_url = landwatch_site_wdig.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_site_wdig_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_site_wdig.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_site_wdig_url, '', this, landwatch_site_wdigHTML);
            }
            <?php } ?>

            <?php if (isset($_219)){?>
            var landwatch_alert_wdig_url = landwatch_alert_wdig.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:landwatch_alert_wdig_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (landwatch_alert_wdig.getVisibility() == true) {
                OpenLayers.loadURL(landwatch_alert_wdig_url, '', this, landwatch_alert_wdigHTML);
            }
            <?php } ?>

            <?php if (isset($_201)){?>
            var daycare_url = daycare.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:ca_daycare'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (daycare.getVisibility() == true) {
                OpenLayers.loadURL(daycare_url, '', this, daycareHTML);
            }
            <?php } ?>

            var federallands_url = federallands.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:Federal_Lands'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (federallands.getVisibility() == true) {
                OpenLayers.loadURL(federallands_url, '', this, federallandsHTML);
            }

            <?php if (isset($_501)){?>
            var parcels_url = parcels.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'application/vnd.ogc.gml',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'parcelpoint'
            }, "http://feeder.terradex.com/proxy/corelogicFeature/");
            if (parcels.getVisibility() == true) {
                OpenLayers.loadURL(parcels_url, '', this, parcelsHTML);
            }
            <?php } ?>

            var us_geothermal_url = us_geothermal.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 're_atlas:us_geothermal'
            }, "http://mapsdb.nrel.gov/geoserver/wms");
            if (us_geothermal.getVisibility() == true) {
                OpenLayers.loadURL(us_geothermal_url, '', this, us_geothermalHTML);
            }

            var us_solarcsp_url = us_solarcsp.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 're_atlas:us_solarcsp'
            }, "http://mapsdb.nrel.gov/geoserver/wms");
            if (us_solarcsp.getVisibility() == true) {
                    OpenLayers.loadURL(us_solarcsp_url, '', this, us_solarcspHTML);
            }

            var us_tilt_total_url = us_tilt_total.getFullRequestString({
                    REQUEST: "GetFeatureInfo",
                    EXCEPTIONS: "application/vnd.ogc.se_xml",
                    BBOX: map.getExtent().toBBOX(),
                    X: event.xy.x,
                    Y: event.xy.y,
                    INFO_FORMAT: 'text/html',
                    FEATURE_COUNT: 1,
                    WIDTH: map.size.w,
                    HEIGHT: map.size.h,
                    QUERY_LAYERS: 're_atlas:us_tilt_total'
            }, "http://mapsdb.nrel.gov/geoserver/wms");
            if (us_tilt_total.getVisibility() == true) {
                    OpenLayers.loadURL(us_tilt_total_url, '', this, us_tilt_totalHTML);
            }

            var uswpc_url = uswpc.getFullRequestString({
                    REQUEST: "GetFeatureInfo",
                    EXCEPTIONS: "application/vnd.ogc.se_xml",
                    BBOX: map.getExtent().toBBOX(),
                    X: event.xy.x,
                    Y: event.xy.y,
                    INFO_FORMAT: 'text/html',
                    FEATURE_COUNT: 1,
                    WIDTH: map.size.w,
                    HEIGHT: map.size.h,
                    QUERY_LAYERS: 're_atlas:uswpc'
            }, "http://mapsdb.nrel.gov/geoserver/wms");
            if (uswpc.getVisibility() == true) {
                    OpenLayers.loadURL(uswpc_url, '', this, uswpcHTML);
            }

            var streamFlow_url = streamFlow.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:realstx'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (streamFlow.getVisibility() == true) {
                OpenLayers.loadURL(streamFlow_url, '', this, streamFlowHTML);
            }
        <?php if (isset($_801)){?>
	    var dc_site_de_url = dc_site_de.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_site_de_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_site_de.getVisibility() == true) {
                OpenLayers.loadURL(dc_site_de_url, '', this, dc_site_deHTML);
            }
            <?php } ?>
        <?php if (isset($_811)){?>
	    var dc_excavation_with_de_url = dc_excavation_with_de.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_excavation_with_de_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_excavation_with_de.getVisibility() == true) {
                OpenLayers.loadURL(dc_excavation_with_de_url, '', this, dc_excavation_with_deHTML);
            }
            <?php } ?>
          <?php if (isset($_821)){?>
	    var dc_excavation_no_de_url = dc_excavation_no_de.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_excavation_no_de_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_excavation_no_de.getVisibility() == true) {
                OpenLayers.loadURL(dc_excavation_no_de_url, '', this, dc_excavation_no_deHTML);
            }
            <?php } ?>
            <?php if (isset($_802)){?>
	    var dc_site_wv_url = dc_site_wv.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_site_wv_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_site_wv.getVisibility() == true) {
                OpenLayers.loadURL(dc_site_wv_url, '', this, dc_site_wvHTML);
            }
            <?php } ?>
        <?php if (isset($_812)){?>
	    var dc_excavation_with_wv_url = dc_excavation_with_wv.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_excavation_with_wv_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_excavation_with_wv.getVisibility() == true) {
                OpenLayers.loadURL(dc_excavation_with_wv_url, '', this, dc_excavation_with_wvHTML);
            }
            <?php } ?>
          <?php if (isset($_822)){?>
	    var dc_excavation_no_wv_url = dc_excavation_no_wv.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_excavation_no_wv_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_excavation_no_wv.getVisibility() == true) {
                OpenLayers.loadURL(dc_excavation_no_wv_url, '', this, dc_excavation_no_wvHTML);
            }
            <?php } ?>
                         <?php if (isset($_803)){?>
	    var dc_site_dtsc_url = dc_site_dtsc.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_site_dtsc_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_site_dtsc.getVisibility() == true) {
                OpenLayers.loadURL(dc_site_dtsc_url, '', this, dc_site_dtscHTML);
            }
            <?php } ?>
        <?php if (isset($_813)){?>
	    var dc_excavation_with_dtsc_url = dc_excavation_with_dtsc.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_excavation_with_dtsc_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_excavation_with_dtsc.getVisibility() == true) {
                OpenLayers.loadURL(dc_excavation_with_dtsc_url, '', this, dc_excavation_with_dtscHTML);
            }
            <?php } ?>
          <?php if (isset($_823)){?>
	    var dc_excavation_no_dtsc_url = dc_excavation_no_dtsc.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_excavation_no_dtsc_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_excavation_no_dtsc.getVisibility() == true) {
                OpenLayers.loadURL(dc_excavation_no_dtsc_url, '', this, dc_excavation_no_dtscHTML);
            }
            <?php } ?>
           <?php if (isset($_804)){?>
	    var dc_site_id_url = dc_site_id.getFullRequestString({
                REQUEST: "GetFeatureInfo",
                EXCEPTIONS: "application/vnd.ogc.se_xml",
                BBOX: map.getExtent().toBBOX(),
                X: event.xy.x,
                Y: event.xy.y,
                INFO_FORMAT: 'text/html',
                FEATURE_COUNT: 1,
                WIDTH: map.size.w,
                HEIGHT: map.size.h,
                QUERY_LAYERS: 'pgterradex:dc_site_id_view'
            }, "http://<?= GEOSERVER_URL ?>:8080/geoserver/wms");
            if (dc_site_id.getVisibility() == true) {
                OpenLayers.loadURL(dc_site_id_url, '', this, dc_site_idHTML);
            }
            <?php } ?>
        }
    });
    map.addControl(selectCtrl);
    selectCtrl.activate();

    // Map Navigation control in the 'navigation' toggleGroup
    var panZoom = new GeoExt.Action({
        tooltip: "Pan around the map. Hold control and drag a box to rubber-band zoom.",
        iconCls: "icon-pan",
        toggleGroup: "navigation",
        pressed: true,
        allowDepress: false,
        control: new OpenLayers.Control.Navigation(),
        map: map,
        handler: function () {
            Ext.getCmp('map').body.applyStyles('cursor:default');
            var element = document.getElementById('output');
            element.innerHTML = "";
            layerRuler.removeFeatures(layerRuler.features);
        }
    });
    // Indetify control in the 'navigation' toggleGroup
    var identify = new GeoExt.Action({
        tooltip: "Identify Features",
        iconCls: "icon-identify",
        toggleGroup: "navigation",
        pressed: false,
        allowDepress: false,
        control: selectCtrl,
        map: map,
        handler: function () {
            Ext.getCmp('map').body.applyStyles('cursor:help');
            var element = document.getElementById('output');
            element.innerHTML = "";
            layerRuler.removeFeatures(layerRuler.features);
        }
    });
    // Clear Selection control in the 'navigation' toggleGroup
    var clearSelect = new Ext.Button({
        tooltip: "Clear Map Graphics",
        iconCls: "icon-clearselect",
        handler: function () {
            var element = document.getElementById('output');
            element.innerHTML = "";
            layerRuler.removeFeatures(layerRuler.features);
            length.cancel();
            area.cancel();
            searchMarker.clearMarkers();
            clickMarker.clearMarkers();
        }
    });
    // Zoom In control in the 'navigation' toggleGroup
    var zoomIn = new GeoExt.Action({
        tooltip: "Zoom In",
        iconCls: "icon-zoomin",
        toggleGroup: "navigation",
        pressed: false,
        allowDepress: false,
        control: new OpenLayers.Control.ZoomBox({
            alwaysZoom: true
        }),
        map: map,
        handler: function () {
            Ext.getCmp('map').body.applyStyles('cursor:crosshair');
            var element = document.getElementById('output');
            element.innerHTML = "";
            layerRuler.removeFeatures(layerRuler.features);
        }
    });
    // Zoom Out control
    var zoomOut = new Ext.Button({
        tooltip: "Zoom Out",
        iconCls: "icon-zoomout",
        handler: function () {
            map.zoomOut();
        }
    });
    // Navigation history - two "button" controls
    var navHistoryCtrl = new OpenLayers.Control.NavigationHistory();
    map.addControl(navHistoryCtrl);
    var zoomPrevious = new GeoExt.Action({
        tooltip: "Zoom to Previous Extent",
        iconCls: "icon-zoomprevious",
        control: navHistoryCtrl.previous,
        disabled: true
    });
    var zoomNext = new GeoExt.Action({
        tooltip: "Zoom to Next Extent",
        iconCls: "icon-zoomnext",
        control: navHistoryCtrl.next,
        disabled: true
    });
    // Zoom Extent control
    var zoomExtentBtn = new Ext.Button({
        tooltip: "Zoom to Initial Extent",
        iconCls: "icon-zoomextent",
        handler: function () {
            map.setCenter(new OpenLayers.LonLat(-10736911.231477, 4629660.818508), 4);
            //map.zoomToExtent(new OpenLayers.Bounds(-8209965.6370154, 5267549.9345529, -8200907.8491652, 5272289.0303057));
        }
    });
    var linemeasureStyles = {
        "Point": {
            pointRadius: 4,
            graphicName: "square",
            fillColor: "white",
            fillOpacity: 1,
            strokeWidth: 1,
            strokeOpacity: 1,
            strokeColor: "#333333"
        },
        "Line": {
            strokeColor: "#FF0000",
            strokeOpacity: 0.3,
            strokeWidth: 3,
            strokeLinecap: "square"
        }
    };
    var lineStyle = new OpenLayers.Style();
    lineStyle.addRules([
    new OpenLayers.Rule({
        symbolizer: linemeasureStyles
    })]);
    var linemeasureStyleMap = new OpenLayers.StyleMap({
        "default": lineStyle
    });
    var length = new OpenLayers.Control.Measure(OpenLayers.Handler.Path, {
        displaySystem: 'english',
        geodesic: true,
        persist: true,
        handlerOptions: {
            layerOptions: {
                styleMap: linemeasureStyleMap
            }
        },
        textNodes: null,
        callbacks: {
            create: function () {
                this.textNodes = [];
                layerRuler.removeFeatures(layerRuler.features);
                mouseMovements = 0;
            },
            modify: function (point, line) {
                if (mouseMovements++ < 5) {
                    return;
                }
                var len = line.geometry.components.length;
                var from = line.geometry.components[len - 2];
                var to = line.geometry.components[len - 1];
                var ls = new OpenLayers.Geometry.LineString([from, to]);
                var dist = this.getBestLength(ls);
                if (!dist[0]) {
                    return;
                }
                var total = this.getBestLength(line.geometry);
                var label = dist[0].toFixed(2) + " " + dist[1];
                var textNode = this.textNodes[len - 2] || null;
                if (textNode && !textNode.layer) {
                    this.textNodes.pop();
                    textNode = null;
                }
                if (!textNode) {
                    var c = ls.getCentroid();
                    textNode = new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(c.x, c.y), {}, {
                        label: '',
                        fontColor: "#FF0000",
                        fontSize: "14px",
                        fontFamily: "Arial",
                        fontWeight: "bold",
                        labelAlign: "cm"
                    });
                    this.textNodes.push(textNode);
                    layerRuler.addFeatures([textNode]);
                }
                textNode.geometry.x = (from.x + to.x) / 2;
                textNode.geometry.y = (from.y + to.y) / 2;
                textNode.style.label = label;
                textNode.layer.drawFeature(textNode);
                this.events.triggerEvent('measuredynamic', {
                    measure: dist[0],
                    total: total[0],
                    units: dist[1],
                    order: 1,
                    geometry: ls
                });
            }
        }
    });
    length.events.on({
        "measure": handleMeasurements,
        "measurepartial": handleMeasurements
    });
    var areameasureStyles = {
        "Point": {
            pointRadius: 4,
            graphicName: "square",
            fillColor: "white",
            fillOpacity: 1,
            strokeWidth: 1,
            strokeOpacity: 1,
            strokeColor: "#333333"
        },
        "Polygon": {
            strokeWidth: 3,
            strokeOpacity: 1,
            strokeColor: "red",
            fillColor: "red",
            fillOpacity: 0.3
        }
    };
    var areaStyle = new OpenLayers.Style();
    areaStyle.addRules([
    new OpenLayers.Rule({
        symbolizer: areameasureStyles
    })]);
    var areaStyleMap = new OpenLayers.StyleMap({
        "default": areaStyle
    });
    var area = new OpenLayers.Control.Measure(OpenLayers.Handler.Polygon, {
        displaySystem: 'english',
        geodesic: true,
        persist: true,
        handlerOptions: {
            layerOptions: {
                styleMap: areaStyleMap
            }
        }
    });
    area.events.on({
        "measure": handleMeasurements,
        "measurepartial": handleMeasurements
    });
    map.addControl(length);
    map.addControl(area);
    var measureLength = new GeoExt.Action({
        tooltip: "Measure Length",
        iconCls: "icon-measure-length",
        toggleGroup: "navigation",
        pressed: false,
        allowDepress: false,
        control: length,
        map: map,
        handler: function () {
            Ext.getCmp('map').body.applyStyles('cursor:crosshair');
            var element = document.getElementById('output');
            element.innerHTML = "";
        }
    });
    var measureArea = new GeoExt.Action({
        tooltip: "Measure Area",
        iconCls: "icon-measure-area",
        toggleGroup: "navigation",
        pressed: false,
        allowDepress: false,
        control: area,
        map: map,
        handler: function () {
            Ext.getCmp('map').body.applyStyles('cursor:crosshair');
            var element = document.getElementById('output');
            element.innerHTML = "";
            layerRuler.removeFeatures(layerRuler.features);
        }
    });

    function handleMeasurements(event) {
        var geometry = event.geometry;
        var units = event.units;
        var order = event.order;
        var measure = event.measure;
        var element = document.getElementById('output');
        var acres;
        var out = "";
        if (order == 1) {
            out += measure.toFixed(2) + " " + units;
        } else if (order == 2 && units === "ft" && measure >= 43560) {
            acres = measure / 43560;
            out += acres.toFixed(2) + " acres";
        } else {
            out += measure.toFixed(2) + " " + units + "<sup>2</" + "sup>";
        }
        element.innerHTML = "&nbsp;&nbsp;" + out;
    };
    var bookmarks = new Ext.form.ComboBox({
        tpl: '<tpl for="."><div ext:qtip="{label}" class="x-combo-list-item">{label}</div></tpl>',
        store: bookmarkStore,
        displayField: 'label',
        typeAhead: true,
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        width: 125,
        emptyText: 'Zoom To A State',
        selectOnFocus: true,
        listeners: {
            'select': function (combo, record) {
                map.zoomToExtent(new OpenLayers.Bounds(record.data.xmin, record.data.ymin, record.data.xmax, record.data.ymax));
            },
            "focus": function () {
                keyboardnav.deactivate();
            },
            "blur": function () {
                keyboardnav.activate();
            }
        }
    });
    var cleanupsiteSearch = new Ext.form.ComboBox({
        queryParam: 'query',
        store: cleanupStore,
        displayField: 'name',
        typeAhead: false,
        loadingText: 'Searching...',
        width: 300,
        //pageSize: 10,
        emptyText: 'Search Active Map Layers (enter min 4 characters)',
        hideTrigger: true,
        tpl: '<tpl for="."><div ext:qtip="{name}" class="search-item"><b>{name}</b><br>{city},&nbsp;{state}</div><hr/></tpl>',
        itemSelector: 'div.search-item',
        listeners: {
            'select': function (combo, record) {
                map.zoomToExtent(
                new OpenLayers.Bounds(record.data.bbox[0], record.data.bbox[1], record.data.bbox[2], record.data.bbox[3]).transform(
                new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")));
                map.zoomTo(16);
                searchMarker.clearMarkers();
                searchMarker.addMarker(new OpenLayers.Marker(map.getCenter(),searchIcon));
            },
            'beforequery': function () {
                var featureLayers = map.getLayersBy("visibility", true);
                //                alert(featureLayers.length);
                cleanupStore.baseParams = {};
                for (var i = 0; i < featureLayers.length; i++) {
                    //                    alert(escape(featureLayers[i].params.LAYERS));
                    eval("cleanupStore.baseParams." + escape(featureLayers[i].options.layerid) + "=" + featureLayers[i].getVisibility());
                    //                    featureLayers[i].setVisibility(true);
                }
                //                cleanupStore.baseParams.layer = cleanupSites.getVisibility();
            },
            "focus": function () {
                keyboardnav.deactivate();
            },
            "blur": function () {
                keyboardnav.activate();
            }
        }
    });
    var addressSearch = new Ext.form.ComboBox({
        queryParam: 'query',
        store: geocodeStore,
        displayField: 'address',
        typeAhead: false,
        queryDelay: 1000,
        loadingText: 'Searching...',
        width: 150,
        //pageSize: 10,
        emptyText: 'Zoom To An Address',
        hideTrigger: true,
        tpl: '<tpl for="."><div class="search-item"><b>{address}</b></div><hr/></tpl>',
        itemSelector: 'div.search-item',
        listeners: {
            'select': function (combo, record) {
                map.setCenter(new OpenLayers.LonLat(record.data.lon, record.data.lat).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")), 16);
                searchMarker.clearMarkers();
                searchMarker.addMarker(new OpenLayers.Marker(map.getCenter(),addressIcon));
            },
            "focus": function () {
                keyboardnav.deactivate();
            },
            "blur": function () {
                keyboardnav.activate();
            }
        }
    });
    opacitySlider = new GeoExt.LayerOpacitySlider({
        //layer: cleanupSites,
        aggressive: true,
        width: 150,
        isFormField: true,
        inverse: true,
        fieldLabel: "opacity",
        plugins: new GeoExt.LayerOpacitySliderTip({
            template: '<div>Transparency: {opacity}%</div>'
        })
    });
    var mapLinkButton = new Ext.Button({
        text: "<b>Get Map Link</b>",
        iconCls: "icon-link",
        iconAlign: "right",
        handler: function () {
            Ext.MessageBox.alert('Map Link', '<a style="color:#000000;" href="' + mapLink + '" target="blank">Right click to copy map link</a>');
        }
    });
    var srsSelector = new Ext.Button({
        menu: {
            items: [{
                text: "Decimal Degrees",
                checked: true,
                group: "srs",
                handler: function () {
                    document.getElementById('ddcoords').style.display = 'inline';
                    document.getElementById('dmscoords').style.display = 'none';
                    document.getElementById('googcoords').style.display = 'none';
                    srsSelector.setText("<b>Decimal Degrees</b>");
                }
            }, {
                text: "Degrees Minutes Seconds",
                checked: false,
                group: "srs",
                handler: function () {
                    document.getElementById('ddcoords').style.display = 'none';
                    document.getElementById('dmscoords').style.display = 'inline';
                    document.getElementById('googcoords').style.display = 'none';
                    srsSelector.setText("<b>Degrees Minutes Seconds</b>");
                }
            }, {
                text: "Web Mercator (Meters)",
                checked: false,
                group: "srs",
                handler: function () {
                    document.getElementById('ddcoords').style.display = 'none';
                    document.getElementById('dmscoords').style.display = 'none';
                    document.getElementById('googcoords').style.display = 'inline';
                    srsSelector.setText("<b>Web Mercator (Meters)</b>");
                }
            }]
        },
        //tooltip: "Select a Coordinate System",
        text: "<b>Decimal Degrees</b>"
    });
    var fullScreenButton = new Ext.Button({
        id: "fullScreenButton",
        text: "<b>Full Screen</b>",
        iconCls: "icon-fullscreen",
        iconAlign: "right",
        enableToggle: true,
        handler: function () {
            if (Ext.getCmp("fullScreenButton").pressed == true) {
                Ext.getCmp("northPanel").collapse();
                Ext.getCmp("westPanel").collapse();
                Ext.getCmp("southPanel").collapse();
            }
            if (Ext.getCmp("fullScreenButton").pressed == false) {
                Ext.getCmp("northPanel").expand();
                Ext.getCmp("westPanel").expand();
                //Ext.getCmp("southPanel").expand();
            }
        }
    });
    var toolBar = [panZoom, zoomIn, zoomOut, zoomPrevious, zoomNext, zoomExtentBtn, "-", clearSelect, "-", measureLength, measureArea, '<div id="output" style="color: red; font-weight: bold; font-size:12px; text-align: right;">&nbsp;&nbsp;&nbsp;&nbsp;</div>', '->', bookmarks, '-', addressSearch, '-', cleanupsiteSearch];
    var bottomBar = [mapLinkButton, "-", '<div style="font-weight: bold;">Map Scale:&nbsp;</div>', '<div id="scale"></div>', "-", srsSelector, " ", '<div id="ddcoords"></div><div id="dmscoords" style="display:none;"></div><div id="googcoords" style="display:none;"></div>', "->", fullScreenButton];
    // create map panel
    var zoomSlider = new GeoExt.ZoomSlider({
        map: map,
        aggressive: true,
        vertical: true,
        height: 100,
        plugins: new GeoExt.ZoomSliderTip({
            template: "<div>Zoom Level: {zoom}</div><div>Scale: 1 : {scale}</div>"
        })
    });
    var mapPanel = new GeoExt.MapPanel({
        id: "map",
        title: "Interactive Map",
        iconCls: "icon-interactivemap",
        region: "center",
        height: 400,
        width: 600,
        map: map,
        tbar: toolBar,
        bbar: bottomBar,
        center: new OpenLayers.LonLat(-10736911.231477, 4629660.818508),
        zoom: 4,
        items: [zoomSlider],
        stateId: "map",
        prettyStateKeys: false
    });
    tree = new Ext.tree.TreePanel({
        /*root: new GeoExt.tree.LayerContainer({
            text: 'Map Layers',
            layerStore: mapPanel.layers,
            leaf: false,
            expanded: true
        }),*/
        loader: new Ext.tree.TreeLoader({
            applyLoader: false
        }),
        root: {
            nodeType: "async",
            children: treeConfig
        },
        border: false,
        rootVisible: false,
        enableDD: false
    });
    var win = new Ext.Window({
        layout: 'fit',
        width: 300,
        height: 150,
        closable: true,
        resizable: false,
        plain: true,
        border: false,
        closeAction: 'hide',
        items: login
    });
<?php if (isset($user_id)) { ?>
    var loginoutButton = new Ext.Button({
        text: "<b>Logout<?php echo ' ' . $first_name ?></b>",
        cls: 'login',
        handler: function () {
            Ext.Ajax.request({
                url: 'user/logout',
                method: 'GET',
                success: function (result, request) {
                    var redirect = '/';
                    window.location = redirect;
                },
                failure: function (result, request) {
                    Ext.MessageBox.alert('Failed', result.responseText);
                }
            });
        }
    })
<?php } else { ?>
    var loginoutButton = new Ext.Button({
        text: "<b>Login</b>",
        cls: 'login',
        handler: function () {
            win.show();
        }
    })
<?php } ?>
    var headerPanel = new Ext.Container({
        region: 'north',
        height: 50,
        autoEl: [{
            tag: 'div',
            cls: 'header',
            //html: '<a href="http://www.terradex.com" target="_blank"><img style="width: 83px; height: 40px; position:absolute; left:30px; top:5px;" alt="Cleanup Deck- by terradex" src="img/top_logo_white.gif" align="middle"></a><img style="width: 348px; height: 40px; position:absolute; right:30px; top:5px;" alt="Cleanup Deck V2.0" src="img/cleanupdeck.png" align="middle">'
            html: '<img style="width: 348px; height: 40px; position:absolute; left:30px; top:5px;" alt="Cleanup Deck V2.0" src="img/cleanupdeck.png" align="middle">'
        }]
    });
    if (typeof (loginoutButton) != "undefined") {
        headerPanel.add(loginoutButton);
    }
    var northPanel = new Ext.Panel({
        region: 'north',
        id: 'northPanel',
        height: 50,
        split: true,
        collapseMode: 'mini',
        items: [headerPanel]
    });
    var southPanel = new Ext.Panel({
        id: "southPanel",
        region: 'south',
        title: "Feature Info",
        iconCls: "icon-featureinfo",
        autoScroll: true,
        border: true,
        split: true,
        height: 250,
        minSize: 100,
        collapseMode: "mini",
        collapsed: true,
        //margins: '0 0 0 0',
        html: "<div id='responseText'><p>Click on a map feature for information.</p></div></div><div id='cleanupSites_Info'></div><div id='csms_facility_polygon_Info'></div><div id='icPolygons_Info'></div><div id='groundwaterplumes_Info'></div><div id='landwatchevent_Info'></div><div id='landwatch_site_Info'></div><div id='landwatch_site_aps_Info'></div><div id='landwatch_alert_aps_Info'></div><div id='landwatch_site_basf_Info'></div><div id='landwatch_alert_basf_Info'></div><div id='landwatch_site_bp_Info'></div><div id='landwatch_alert_bp_Info'></div><div id='landwatch_site_dtsc_Info'></div><div id='landwatch_alert_dtsc_Info'></div><div id='landwatch_site_ge_Info'></div><div id='landwatch_alert_ge_Info'></div><div id='landwatch_site_nysdec_Info'></div><div id='landwatch_alert_nysdec_Info'></div><div id='landwatch_site_pge_Info'></div><div id='landwatch_alert_pge_Info'></div><div id='landwatch_site_urs_Info'></div><div id='landwatch_alert_urs_Info'></div><div id='landwatch_site_usepa_Info'></div><div id='landwatch_alert_usepa_Info'></div><div id='landwatch_site_wdig_Info'></div><div id='landwatch_alert_wdig_Info'></div><div id='daycare_Info'></div><div id='federallands_Info'></div><div id='parcels_Info'></div><div id='us_geothermal_Info'></div><div id='us_solarcsp_Info'></div><div id='us_tilt_total_Info'></div><div id='uswpc_Info'></div><div id='streamFlow_Info'></div><div id='dc_site_de_Info'></div><div id='dc_excavation_with_de_Info'></div><div id='dc_excavation_no_de_Info'></div><div id='dc_site_wv_Info'></div><div id='dc_excavation_with_wv_Info'></div><div id='dc_excavation_no_wv_Info'></div><div id='dc_site_dtsc_Info'></div><div id='dc_excavation_with_dtsc_Info'></div><div id='dc_excavation_no_dtsc_Info'></div><div id='dc_site_id_Info'></div>"
   });
    var layersContainer = new Ext.Panel({
        autoScroll: true,
        border: false,
        region: 'center',
        title: "Map Layers",
        iconCls: "icon-maplayers",
        items: [tree],
        bbar: ["Transparency:&nbsp;&nbsp;", opacitySlider]
    });
    var filterPanel = new Ext.Panel({
        id: "filterPanel",
        title: "Data Filters",
        iconCls: "icon-filter",
        closable: false,
        autoScroll: true,
        border: false,
        contentEl: "cleanupSitesFilter"
    });
    var layersfilterPanel = new Ext.TabPanel({
        region: 'center',
        deferredRender: false,
        activeTab: 0,
        items: [layersContainer, filterPanel]
    })
    legendContainer = new GeoExt.LegendPanel({
        id: "legendPanel",
        title: "Map Legend",
        iconCls: "icon-maplegend",
        border: false,
        region: 'south',
        height: 250,
        collapseMode: "mini",
        split: true,
        autoScroll: true,
        ascending: false,
        map: map,
        defaults: {
            cls: 'legend-item'
        },
        items: [mhi_state, mhi_county, mhi_tract, mhi_blockgroups, parcelsLegend]
    });
    var westPanel = new Ext.Panel({
        id: "westPanel",
        border: true,
        layout: "border",
        region: "west",
        width: 250,
        split: true,
        collapseMode: "mini",
        items: [layersfilterPanel, legendContainer]
    });
    var eastPanel = new Ext.Panel({
        id: "eastPanel",
        title: "Feature Info",
        iconCls: "icon-featureinfo",
        border: true,
        region: "east",
        width: 250,
        split: true,
        collapseMode: "mini",
        collapsed: "true",
        autoScroll: true,
        html: "<div id='responseText'><p>Click on a map feature for information.</p></div>"
    });
    var aboutPanel = new Ext.Panel({
        id: "tab2",
        title: "About",
        iconCls: "icon-about",
        closable: false,
        autoScroll: true,
        border: false,
        html: about
    });
    <? if (isset($show_download)) { ?>
    var dataPanel = new Ext.Panel({
        id: "tab3",
        title: "Data Download",
        iconCls: "icon-data",
        closable: false,
        autoScroll: true,
        border: false,
        contentEl: "data"
    });
    <? } ?>
    var detailsPanel = new Ext.Panel({
        id: "tabDetails",
        title: "Details",
        iconCls: "icon-leaf",
        closable: false,
        autoScroll: true,
        border: false
    });
<?php if (isset($_801)) { ?>
    var dcManagementDEPanel = new Ext.Panel({
        id: "dcManagementDEPanel",
        title: "Dig Clean Actvity",
        iconCls: "icon-leaf",
        closable: false,
        autoScroll: true,
        border: false,
        html: dcManagementDE
    });
<?php } ?>
<?php if (isset($_802)) { ?>
    var dcManagementWVPanel = new Ext.Panel({
        id: "dcManagementWVPanel",
        title: "Dig Clean Actvity",
        iconCls: "icon-leaf",
        closable: false,
        autoScroll: true,
        border: false,
        html: dcManagementWV
    });
<?php } ?>
    var centerPanel = new Ext.TabPanel({
        id: "centerPanel",
        region: 'center',
        deferredRender: false,
        activeTab: 0,
        items: [mapPanel, <?= (isset($_801)? 'dcManagementDEPanel,': '') ?> <?= (isset($_802)? 'dcManagementWVPanel,': '') ?> <? if (isset($show_download)) { ?>dataPanel,<? } ?> aboutPanel]
    });
    var viewport = new Ext.Viewport({
        layout: 'border',
        items: [northPanel, westPanel, centerPanel, southPanel]
    });
    map.setBaseLayer(gmap);
    mhi_state.hide();
    mhi_county.hide();
    mhi_tract.hide();
    mhi_blockgroups.hide();
    parcelsLegend.hide();
    map.addControl(new OpenLayers.Control.Scale($('scale')));

    function formatLonlats(lonLat) {
        var lat = lonLat.lat;
        var long = lonLat.lon;
        var ns = OpenLayers.Util.getFormattedLonLat(lat);
        var ew = OpenLayers.Util.getFormattedLonLat(long, 'lon');
        return ns + ', ' + ew;
    }
    map.addControl(new OpenLayers.Control.MousePosition({
        "div": OpenLayers.Util.getElement("ddcoords"),
        displayProjection: new OpenLayers.Projection("EPSG:4326")
    }));
    map.addControl(new OpenLayers.Control.MousePosition({
        "div": OpenLayers.Util.getElement("dmscoords"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        formatOutput: formatLonlats
    }));
    map.addControl(new OpenLayers.Control.MousePosition({
        "div": OpenLayers.Util.getElement("googcoords"),
        displayProjection: new OpenLayers.Projection("EPSG:900913")
    }));
    // update link when state changes
    var onStatechange = function (provider) {
            mapLink = provider.getLink();
        };
    permalinkProvider.on({
        statechange: onStatechange
    });
    // BEGIN DISCLAIMER CODE //
//    Ext.MessageBox.confirm('Disclaimer', disclaimer, showResult);
    // END DISCLAIMER CODE //
    ESRI_USA_Median_Household_Income.events.register('visibilitychanged', this, function (feature) {
        if (ESRI_USA_Median_Household_Income.getVisibility() == true) {
            addMHILegend();
        }
        if (ESRI_USA_Median_Household_Income.getVisibility() == false) {
            mhi_state.hide();
            mhi_county.hide();
            mhi_tract.hide();
            mhi_blockgroups.hide();
            Ext.getCmp('legendPanel').doLayout();
        }
    });
    map.events.register("zoomend", this, function () {
        var scale = map.getScale();
        if (ESRI_USA_Median_Household_Income.getVisibility() == true) {
            addMHILegend();
        }
        if (ESRI_USA_Median_Household_Income.getVisibility() == false) {
            mhi_state.hide();
            mhi_county.hide();
            mhi_tract.hide();
            mhi_blockgroups.hide();
            Ext.getCmp('legendPanel').doLayout();;
        }
    });
<?php if (isset($_501)) { ?>
    parcels.events.register('visibilitychanged', this, function (feature) {
        if (parcels.getVisibility() == true) {
            parcelsLegend.show();
            Ext.getCmp('legendPanel').doLayout();
        }
        if (parcels.getVisibility() == false) {
            parcelsLegend.hide();
            Ext.getCmp('legendPanel').doLayout();
        }
    });
<?php } ?>
});

OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {

    defaultHandlerOptions: {
        single: true,
        double: false,
        pixelTolerance: 0,
        stopSingle: true
    },

    initialize: function (options) {

        this.handlerOptions = OpenLayers.Util.extend(
        options && options.handlerOptions || {}, this.defaultHandlerOptions);
        OpenLayers.Control.prototype.initialize.apply(
        this, arguments);
        this.handler = new OpenLayers.Handler.Click(
        this, {
            click: this.trigger
        }, this.handlerOptions);
    },

    CLASS_NAME: "OpenLayers.Control.Click"

});

function showResult(btn) {
    if (btn != "yes") {
        location.href = 'http://terradex.com/';
    }
};

function showDetails(site_id) {
    url = "<?php echo site_url('report_max_control/co_report_viewcontrol/') ?>/" + site_id;
    var tabDetails = Ext.getCmp('tabDetails');
    var centerPanel = Ext.getCmp('centerPanel');
    centerPanel.add(tabDetails);
//    centerPanel.setActiveTab(tabDetails);
    centerPanel.doLayout();
    tabDetails.update("<iframe src=" + url + " width='100%' height='100%'></iframe>");
    tabDetails.show();
}
</script>
