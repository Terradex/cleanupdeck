<?php
/*
session_start();
if (!isset($_SESSION['username'])){
header("location:login.php");
}
require('mobile_device_detect.php');
mobile_device_detect(true,true,true,true,true,true,true,false,'../index.php');
*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Cleanup Deck Mobile</title>
	<meta name="Author" content="bryanmcbride.com" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" href="resources/jquery.mobile-1.1.0/jquery.mobile-1.1.0.min.css">
	<script src="resources/jquery-1.7.2.min.js"></script>
	<script src="resources/jquery.mobile-1.1.0/jquery.mobile-1.1.0.min.js"></script>
	<script src="resources/OpenLayers-2.12.rc3.mobile.js"></script>
	<style>
	.olImageLoadError {
		visibility: hidden;
	}
	.olControlAttribution {
		font-size: 10px;
		bottom: 5px;
		left: 5px;
	}
	.olTileImage {
		-webkit-transition: opacity 0.2s linear;
		-moz-transition: opacity 0.2s linear;
		-o-transition: opacity 0.2s linear;
		transition: opacity 0.2s linear;
	}
	#mapPage, #mapPage .ui-content, #map {
		width: 100%;
		height: 100%;
	}
	#navigation {
		position: absolute;
		top: 70px;
		left: 10px;
		z-index: 1000;
	}
	#navigation .ui-btn-icon-notext {
		display: block;
		padding: 7px 6px 7px 8px;
	}
	.ui-icon-locate {
		background-image: url(img/locate.png);
	}
	.ui-icon-layers {
		background-image: url(img/layers.png);
	}
	.ui-content .ui-listview-inset, #search_results {
		margin: 1em;
	}
	.ui-content .ui-listview {
		margin: 0px;
	}
	</style>
</head>
<body onLoad="startup()">
<div data-role="page" id="mappage">
  <div data-role="header" data-nobackbtn="true" data-fixed="true">
	<a href="#" id="locate" data-role="button" data-icon="locate" data-iconpos="right" class="ui-btn-right">Locate</a>
	<h1 style="text-align: left; margin: 11px">Cleanup Deck Mobile</h1>
	</div>
  <div id="map" data-role="content" style="padding: 0px;">
  </div>

  <div data-role="footer" data-fixed="true" style="text-align: center; padding: 5px 0;">
	<a href="#searchpage" data-icon="search" data-role="button">Search</a>
	<a href="#layerspage" data-icon="layers" data-role="button">Layers</a>
	<a href="#aboutpage" data-icon="info" data-role="button" data-transition="flip">About</a>
  </div>
  <div id="navigation" data-role="controlgroup" data-type="vertical">
	<a href="#" data-role="button" data-icon="plus" id="plus" data-iconpos="notext"></a>
	<a href="#" data-role="button" data-icon="minus" id="minus" data-iconpos="notext"></a>
  </div>
</div>

<div data-role="page" id="searchpage">
  <div data-role="header" data-position="fixed">
	<a href="#mappage" data-icon="arrow-l">Map</a>
	<h1  style="text-align: center; margin: 11px">Place Name Search</h1>
  </div>
  <div data-role="fieldcontain">
	<center><!--<input type="search" name="query" id="query" value="" style="padding: 5px" placeholder="Albany" autocomplete="off"/>-->
		<p>
			<label for="placequery"><b>Address Search:</b></label>
			<input type="search" name="placequery" id="placequery" value="" placeholder="" autocomplete="off"/>
		</p>
		<p>
			<label for="sitequery"><b>Cleanup Site Search:</b></label>
			<input type="search" name="sitequery" id="sitequery" value="" placeholder="Cleanup Site Search" autocomplete="off"/>
		</p>
        <ul data-role="listview" data-inset="true" id="search_results"></ul>
	</center>
  </div>
  <ul data-role="listview" data-inset="true" id="search_results"></ul>
</div>

<div data-role="page" id="layerspage">
  <div data-role="header" data-position="fixed">
	<a href="#mappage" data-icon="arrow-l">Map</a>
	<h1>Map Layers</h1>
  </div>
	<center>
	<select name="opacity" id="opacity" data-native-menu="false">
	<option value="1">100% Overlay Opacity</option>
	<option value="0.75">75% Overlay Opacity</option>
	<option value="0.5">50% Overlay Opacity</option>
	<option value="0.25">25% Overlay Opacity</option>
	<option value="0">0% Overlay Opacity</option>
	</select>
	</center>
	<div style="padding-left: 5px; padding-right: 5px">
	<h3>Cleanup Deck Layers</h3>
	<fieldset data-role="controlgroup">
		<input autocomplete="off" type="checkbox" name="institutionalControls" id="institutionalControls" checked="checked" class="custom" onchange="if ($('#institutionalControls').is(':checked')) {institutionalControls.setVisibility(true)} else (institutionalControls.setVisibility(false))"/>
		<label for="institutionalControls">Institutional Controls</label>
		<input autocomplete="off" type="checkbox" name="cleanupSites" id="cleanupSites" checked="checked" class="custom" onchange="if ($('#cleanupSites').is(':checked')) {cleanupSites.setVisibility(true)} else (cleanupSites.setVisibility(false))"/>
		<label for="cleanupSites">Cleanup Sites</label>
        <input autocomplete="off" type="checkbox" name="groundwaterPlumes" id="groundwaterPlumes" checked="checked" class="custom" onchange="if ($('#groundwaterPlumes').is(':checked')) {groundwaterPlumes.setVisibility(true)} else (groundwaterPlumes.setVisibility(false))"/>
        <label for="groundwaterPlumes">Groundwater Plumes</label>
	</fieldset>
	<h3>Base Layers</h3>
	<fieldset data-role="controlgroup">
		<input type="radio" name="basemap" id="bingStreets" value="bingStreets" checked="checked" onchange="map.setBaseLayer(bingStreets);" />
		<label for="bingStreets">Bing Streets</label>
		<input type="radio" name="basemap" id="bingImagery" value="bingImagery" onchange="map.setBaseLayer(bingSat);" />
		<label for="bingImagery">Bing Imagery</label>
	</fieldset>
	</div>
</div>

<div data-role="page" id="aboutpage">
	<div data-role="header" data-position="fixed">
	<a href="#mappage" data-icon="arrow-l">Map</a>
	<h1>About</h1>
	</div>
	<div data-role="content" style="padding:5px">
	<div data-role="fieldcontain">
		<div style="text-align:center; font-weight:bold;">Mobil Cleanup Deck</div>
		<br>The mobile Cleanup Deck assembles locations of cleanup sites, institutional controls and groundwater plumes in the United States. Information about a mapped item is available by touching the feature. The information comes from multiple government databases. Terradex is not responsible for errors in representations, and offers no warranty on completeness. Terradex will correct identified errors as part of our interpretation of this data.
		<p>For more information please contact<br>Terradex Inc<br>855 El Camino Real, Suite 309<br>Palo Alto, CA 9430</p>
        <p><a href="www.terradex.com">http://www.terradex.com</a><br><a href="mailto:customer@terradex.com">customer@terradex.com</a><br>650 227 3250</p>
	</div>
	</div>
</div>

<div data-role="page" id="feederpage">
	<div data-role="header" data-position="fixed">
	<a href="#mappage" data-icon="arrow-l">Map</a>
    <h1>Feature Info</h1>
	</div>
	<div id="feederinfo" data-role="content">
	</div>
</div>

<script type="text/javascript">
var map, footer, header, content, viewHeight, contentHeight, myLocation, mapquestOSM, mapquestImagery, bingStreets, bingSat, institutionalControls, cleanupSites, groundwaterPlumes;
OpenLayers.Util.onImageLoadError = function() { this.style.display="none";}
OpenLayers.ProxyHost = "proxy.php?url=";

function fixContentHeight() {
    footer = $("div[data-role='footer']:visible");
    header = $("div[data-role='header']:visible");
    content = $("div[data-role='content']:visible:visible");
    viewHeight = $(window).height();
    contentHeight = viewHeight - footer.outerHeight() - header.outerHeight();
    if ((content.outerHeight() + footer.outerHeight() + header.outerHeight()) !== viewHeight) {
        contentHeight -= (content.outerHeight() - content.height());
        content.height(contentHeight);
    }
    document.getElementById("map").style.height = contentHeight + "px";
}
$(window).bind("orientationchange resize pageshow", fixContentHeight);

function startup() {
    // Start with map page
    if (window.location.hash && window.location.hash != "#mappage") {
        $.mobile.changePage("#mappage");
    }
    fixContentHeight();
    init();
}

function init() {
	var attributionCtrl = new OpenLayers.Control.Attribution();
	var geolocateCtrl = new OpenLayers.Control.Geolocate({
        id: 'locate-control',
        geolocationOptions: {
            enableHighAccuracy: true,
            maximumAge: 0,
            timeout: 5000
        }
    });
	var touchnavCtrl = new OpenLayers.Control.TouchNavigation({
		dragPanOptions: {
			interval: 100,
			enableKinetic: true
		}
	});
    var feederCtrl = new OpenLayers.Control.Click({
        trigger: function (event) {
            $('#feederinfo').empty();
            if (institutionalControls.getVisibility() == true) {
                var institutionalControls_URL = institutionalControls.getFullRequestString({
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
                }, "http://geoload.terradex.com:8080/geoserver/wms");
                OpenLayers.Request.GET({
                    url: institutionalControls_URL,
                    callback: function (response) {
                        if (response.responseText.length > 687) {
                            $.mobile.showPageLoadingMsg();
                            $('#feederinfo').append("<iframe src='"+institutionalControls_URL+"' width='100%' height='500px' frameborder='0' scrolling='no' onLoad='$.mobile.hidePageLoadingMsg();'></iframe>");
                            $.mobile.changePage('#feederpage');
                        }
                    }
                });
            }
            if (cleanupSites.getVisibility() == true) {
                var cleanupSites_URL = cleanupSites.getFullRequestString({
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
                }, "http://geoload.terradex.com:8080/geoserver/wms");
                OpenLayers.Request.GET({
                    url: cleanupSites_URL,
                    callback: function (response) {
                        if (response.responseText.length > 687) {
                            $.mobile.showPageLoadingMsg();
                            $('#feederinfo').append("<iframe src='"+cleanupSites_URL+"' width='100%' height='500px' frameborder='0' scrolling='no' onLoad='$.mobile.hidePageLoadingMsg();'></iframe>");
                            $.mobile.changePage('#feederpage');
                        }
                    }
                });
            }
            if (groundwaterPlumes.getVisibility() == true) {
                var groundwaterPlumes_URL = groundwaterPlumes.getFullRequestString({
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
                }, "http://geoload.terradex.com:8080/geoserver/wms");
                OpenLayers.Request.GET({
                    url: groundwaterPlumes_URL,
                    callback: function (response) {
                        if (response.responseText.length > 687) {
                            $.mobile.showPageLoadingMsg();
                            $('#feederinfo').append("<iframe src='"+groundwaterPlumes_URL+"' width='100%' height='500px' frameborder='0' scrolling='no' onLoad='$.mobile.hidePageLoadingMsg();'></iframe>");
                            $.mobile.changePage('#feederpage');
                        }
                    }
                });
            }
        }
    });
	myLocation = new OpenLayers.Layer.Vector("My Location", {
        displayInLayerSwitcher: true
    });
	arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png",
				"http://otile2.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png",
				"http://otile3.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png",
				"http://otile4.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png"];
	arrayAerial = ["http://oatile1.mqcdn.com/naip/${z}/${x}/${y}.png",
				"http://oatile2.mqcdn.com/naip/${z}/${x}/${y}.png",
				"http://oatile3.mqcdn.com/naip/${z}/${x}/${y}.png",
				"http://oatile4.mqcdn.com/naip/${z}/${x}/${y}.png"];
	mapquestOSM = new OpenLayers.Layer.OSM("MapQuest-OSM", arrayOSM, {
		numZoomLevels: 20
	});
	mapquestImagery = new OpenLayers.Layer.OSM("MapQuest Open Aerial Tiles", arrayAerial, {
		numZoomLevels: 20
	});
	bingStreets = new OpenLayers.Layer.Bing({
		key: "Ap6PC13ktG2lQOnnRUqi7bX6pPwkP93-fshU6LWlMeN503YdcZInCVMczp6k2joo",
		type: "Road",
		metadataParams: {
			mapVersion: "v1"
		},
		name: "Bing Streets",
		transitionEffect: "resize"
	});
	bingSat = new OpenLayers.Layer.Bing({
		key: "Ap6PC13ktG2lQOnnRUqi7bX6pPwkP93-fshU6LWlMeN503YdcZInCVMczp6k2joo",
		type: "AerialWithLabels",
		name: "Bing Imagery",
		transitionEffect: "resize"
	});
	institutionalControls = new OpenLayers.Layer.WMS("Institutional Controls", "http://geoload.terradex.com:8080/geoserver/wms", {
		layers: 'pgterradex:institutional_controls',
			transparent: true,
			format: "image/png"
		}, {
			isBaseLayer: false,
			visibility: true,
			singleTile: false,
			displayInLayerSwitcher: true
	});
	cleanupSites = new OpenLayers.Layer.WMS("Cleanup Sites", "http://geoload.terradex.com:8080/geoserver/wms", {
		layers: 'pgterradex:csms_facility',
			transparent: true,
			format: "image/png"
		}, {
			isBaseLayer: false,
			visibility: true,
			singleTile: false,
			displayInLayerSwitcher: true
	});
    groundwaterPlumes = new OpenLayers.Layer.WMS("Groundwater Plumes", "http://geoload.terradex.com:8080/geoserver/wms", {
        layers: 'pgterradex:groundwater_plumes',
            transparent: true,
            format: "image/png"
        }, {
            isBaseLayer: false,
            visibility: true,
            singleTile: false,
            displayInLayerSwitcher: true
    });

    map = new OpenLayers.Map({
        div: "map",
        theme: null,
        projection: "EPSG:900913",
        displayProjection: "EPSG:4326",
        center: [-10633033.323594, 5189208.294203],
        zoom: 2,
        controls: [geolocateCtrl, attributionCtrl, feederCtrl, touchnavCtrl],
        layers: [bingStreets, bingSat, institutionalControls, cleanupSites, groundwaterPlumes, myLocation]
    });
    feederCtrl.activate();
    var style = {
        fillOpacity: 0.1,
        fillColor: '#000',
        strokeColor: '#f00',
        strokeOpacity: 0.6
    };
    geolocateCtrl.events.register("locationupdated", this, function (e) {
        myLocation.removeAllFeatures();
        myLocation.addFeatures([
        new OpenLayers.Feature.Vector(
        e.point, {}, {
            graphicName: 'cross',
            strokeColor: '#f00',
            strokeWidth: 2,
            fillOpacity: 0,
            pointRadius: 10
        }), new OpenLayers.Feature.Vector(
        OpenLayers.Geometry.Polygon.createRegularPolygon(
        new OpenLayers.Geometry.Point(e.point.x, e.point.y), e.position.coords.accuracy / 2, 50, 0), {}, style)]);
        map.zoomToExtent(myLocation.getDataExtent());
        if (map.getZoom() >= 16) {
            map.zoomTo(16);
        };
    });

}
// Zoom tools
$("#plus").bind('vclick', function () {
    map.zoomIn();
});
$("#minus").bind('vclick', function () {
    map.zoomOut();
});
// GeoLocation
$("#locate").bind('vclick', function () {
    var control = map.getControlsBy("id", "locate-control")[0];
    if (control.active) {
        control.getCurrentLocation();
    } else {
        control.activate();
    }
});
// Search Functionality
$('#searchpage').live('pageshow', function (event, ui) {
	$('#placequery').bind('change', function (e) {
	    $('#search_results').empty();
	    if ($('#placequery')[0].value === '') {
	        return;
	    }
	    $.mobile.showPageLoadingMsg();
	    e.preventDefault();
        var searchUrl = 'http://www.mapquestapi.com/geocoding/v1/address?key=Fmjtd%7Cluua256zl9%2Cbw%3Do5-962llz';
        $.ajax({
            url: searchUrl,
            dataType: 'jsonp',
            crossDomain: true,
            data: {
              location: $('#placequery')[0].value
            },
            success: function(data, textStatus, jqXHR) {
                $.each(data.results[0].locations, function () {
                    var place = this;
                    if (place.street) {
                        var zoom = 16;
                    } else{
                        var zoom = 11;
                    };
                    $('<li>').hide().append($('<h2 />', {
                        text: place.street
                    })).append($('<p />', {
                        html: '<b>' + place.adminArea5 + ', ' + place.adminArea3 + '</b>'
                    })).appendTo('#search_results').click(function () {
                        $.mobile.changePage('#mappage');
                        var lonlat = new OpenLayers.LonLat(place.latLng.lng, place.latLng.lat);
                        map.setCenter(lonlat.transform(map.displayProjection, map.projection), zoom);
                    }).show();
                });
                $('#search_results').listview('refresh');
                $.mobile.hidePageLoadingMsg();
            }
        });
	    /*var searchUrl = 'http://ws.geonames.org/searchJSON?featureClass=P&maxRows=10&countryCode=US';
	    searchUrl += '&name_startsWith=' + $('#placequery')[0].value;
	    $.getJSON(searchUrl, function (data) {
	        $.each(data.geonames, function () {
	            var place = this;
	            $('<li>').hide().append($('<h2 />', {
	                text: place.name
	            })).append($('<p />', {
	                html: '<b>' + place.adminName1 + '</b> ' + place.fcodeName
	            })).appendTo('#search_results').click(function () {
	                $.mobile.changePage('#mappage');
	                var lonlat = new OpenLayers.LonLat(place.lng, place.lat);
	                map.setCenter(lonlat.transform(map.displayProjection, map.projection), 11);
	            }).show();
	        });
	        $('#search_results').listview('refresh');
	        $.mobile.hidePageLoadingMsg();
	    });*/
	});
	$('#sitequery').bind('change', function (e) {
	    $('#search_results').empty();
	    if ($('#sitequery')[0].value === '') {
	        return;
	    }
	    $.mobile.showPageLoadingMsg();
	    e.preventDefault();
	    var searchUrl = 'http://geoload.terradex.com:8080/geoserver/wfs?service=WFS&version=1.0.0&request=GetFeature&typeName=pgterradex:csms_facility&propertyName=sitename,sitecity,sitestateshort&outputformat=json';
	    searchUrl += '&CQL_FILTER=sitename%20like%20%27' + $('#sitequery')[0].value + '%25%27';
	    $.getJSON(searchUrl, function (data) {
	        $.each(data.features, function () {
	            var place = this;
	            $('<li>').hide().append($('<h2 />', {
	                text: place.id
	            })).append($('<p />', {
	                html: '<b>' + place.id + '</b> ' + place.id
	            })).appendTo('#search_results').click(function () {
	                $.mobile.changePage('#mappage');
	                var lonlat = new OpenLayers.LonLat(place.id, place.id);
	                map.setCenter(lonlat.transform(map.displayProjection, map.projection), 16);
	            }).show();
	        });
	        $('#search_results').listview('refresh');
	        $.mobile.hidePageLoadingMsg();
	    });
	});
    // only listen to the first event triggered
    $('#searchpage').die('pageshow', arguments.callee);
});
$("#opacity").live("change", function () {
	var overlayLayers = map.getLayersBy("isBaseLayer", false);
    for (i = 0; i < overlayLayers.length; i++) {
        overlayLayers[i].setOpacity($(this).val());
    }
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
</script>
</body>
</html>
