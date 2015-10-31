<script type="text/javascript">
	document.domain = "<?php echo base_domain(); ?>";
	//var disclaimer, about, map, tree, westnorthPanel, selectCtrl, opacitySlider, dvpopup, permalinkProvider, mapLink, keyboardnav, searchMarker, clickMarker, layerRuler, gmap, gsat, ghyb, gphy, bingmap, bingsat, binghyb, ESRI_Imagery, ESRI_Topo, FWS_Wetlands, SSURGO_Soils, ESRI_USA_Median_Household_Income, icPolygons, cleanupSites, streamFlow, daycare, landwatchevent, landwatch_site, landwatch_alert_dtsc, landwatch_site_dtsc, landwatch_site_aps, landwatch_site_basf, landwatch_site_bp, landwatch_site_ge, landwatch_site_nysdec, landwatch_site_pge, landwatch_site_urs, landwatch_site_usepa, landwatch_site_wdig, landwatch_alert_aps, landwatch_alert_basf, landwatch_alert_bp, landwatch_alert_ge, landwatch_alert_nysdec, landwatch_alert_pge, landwatch_alert_urs, landwatch_alert_usepa, landwatch_alert_wdig, parcels, groundwaterplumes, naturalasbestos, csms_facility_polygon, federallands, us_geothermal, us_solarcsp, us_tilt_total, uswpc, balloonLayers,dc_site_de_view, dc_site_wv_view, dc_site_dtsc_view, dc_site_id_view, dc_excavation_with_de_view, dc_excavation_with_wv_view, dc_excavation_with_dtsc_view, dc_excavation_no_de_view, dc_excavation_no_wv_view, dc_excavation_no_dtsc_view;
	var disclaimer, about, map, tree, westCenterPanel, selectCtrl, opacitySlider, dvpopup, permalinkProvider, mapLink, keyboardnav, searchMarker, clickMarker, layerRuler, gmap, gsat, ghyb, gphy, bingmap, bingsat, binghyb, ESRI_Imagery, ESRI_Topo, FWS_Wetlands, SSURGO_Soils, ESRI_USA_Median_Household_Income;
<?php echo (isset($vars) ? $vars : ''); ?>
	//icPolygons, cleanupSites, streamFlow, daycare, landwatchevent, landwatch_site, landwatch_alert_dtsc, landwatch_site_dtsc, landwatch_site_aps, landwatch_site_basf, landwatch_site_bp, landwatch_site_ge, landwatch_site_nysdec, landwatch_site_pge, landwatch_site_urs, landwatch_site_usepa, landwatch_site_wdig, landwatch_alert_aps, landwatch_alert_basf, landwatch_alert_bp, landwatch_alert_ge, landwatch_alert_nysdec, landwatch_alert_pge, landwatch_alert_urs, landwatch_alert_usepa, landwatch_alert_wdig, parcels, groundwaterplumes, naturalasbestos, csms_facility_polygon, federallands, us_geothermal, us_solarcsp, us_tilt_total, uswpc, balloonLayers,dc_site_de_view, dc_site_wv_view, dc_site_dtsc_view, dc_site_id_view, dc_excavation_with_de_view, dc_excavation_with_wv_view, dc_excavation_with_dtsc_view, dc_excavation_no_de_view, dc_excavation_no_wv_view, dc_excavation_no_dtsc_view;
	var searchIcon = new OpenLayers.Icon('img/pinIcon.png', new OpenLayers.Size(32,32), new OpenLayers.Pixel(-35, -35));
	var addressIcon = new OpenLayers.Icon('img/pinIcon.png', new OpenLayers.Size(32,32), new OpenLayers.Pixel(-16, -32));
	var clickIcon = new OpenLayers.Icon('img/clickIcon.png', new OpenLayers.Size(32,32), null);
	var mhi_state = new GeoExt.LegendImage({
		url: "img/mhi_states_legend.png"
	});
	var timeFilterCombo, typeFilterCombo;
<?php if (isset($_801))
{ ?>
		var dcManagementDE = '<iframe style="width:100%; height:100%;" src="https://feeder.terradex.com/dc_cleanupdeck/dc_excavation_mgtpanel/11/">';
<?php } ?>
<?php if (isset($_802))
{ ?>
		var dcManagementWV = '<iframe style="width:100%; height:100%;" src="https://feeder.terradex.com/dc_cleanupdeck/dc_excavation_mgtpanel/10/">';
<?php } ?>
<?php if (isset($portfolio_url)) { ?>
		var portfolioHTML = '<iframe style="width:100%; height:100%;" src="<?=$portfolio_url?>">';
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

	function pad(number, length) {

		var str = '' + number;
		while (str.length < length) {
			str = '0' + str;
		}

		return str;

	}

	function layerProperties() {
		if (tree.getSelectionModel().getSelectedNode() == null || tree.getSelectionModel().getSelectedNode().attributes.layer == undefined) {
			alert("Please select a layer!");
		} else{
			timeFilterCombo = new Ext.form.ComboBox({
				xtype: "combo",
				fieldLabel: 'Time Filter',
				readOnly: false,
				store: new Ext.data.SimpleStore({
					fields: ['name', 'value'],
					data: tree.getSelectionModel().getSelectedNode().attributes.time_filter
				}),
				displayField: 'name',
				valueField: 'value',
				//value: tree.getSelectionModel().getSelectedNode().layer.params.CQL_FILTER,
				mode: "local",
				triggerAction: "all",
				typeAhead: true,
				listeners: {
					select: timeFilterComboSelected
				}
			});
			function timeFilterComboSelected(combo) {
				var cql;
				if (combo.getValue() == "time_filter>=''"){
					var dt = new Date();
					var dtstring = dt.getFullYear()
						+ '-' + pad(dt.getMonth()+1, 2)
						+ '-' + pad(dt.getDate(), 2);
					cql = "time_filter<='" + dtstring + "'";
				} else {
					cql = combo.getValue();
				}
				if (typeFilterCombo.getValue() == "type_filter=''" || typeFilterCombo.getValue() == "") {
					cql+= " AND type_filter LIKE '%'";
				} else {
					cql+= " AND " + typeFilterCombo.getValue();
				}

				tree.getSelectionModel().getSelectedNode().layer.mergeNewParams({
					cql_filter: cql
				});

			}

			typeFilterCombo = new Ext.form.ComboBox({
				xtype: "combo",
				fieldLabel: 'Type Filter',
				readOnly: false,
				store: new Ext.data.SimpleStore({
					fields: ['name', 'value'],
					data: tree.getSelectionModel().getSelectedNode().attributes.type_filter
				}),
				displayField: 'name',
				valueField: 'value',
				//value: tree.getSelectionModel().getSelectedNode().layer.params.CQL_FILTER,
				mode: "local",
				triggerAction: "all",
				typeAhead: true,
				listeners: {
					select: typeFilterComboSelected
				}
			});

			function typeFilterComboSelected(combo) {
				var cql;
				if (combo.getValue() == "type_filter=''"){
					cql = "type_filter LIKE '%'";
					tree.getSelectionModel().getSelectedNode().layer.mergeNewParams({
						cql_filter: "type_filter LIKE '%'"
					});
				} else {
					cql = combo.getValue();
				}
				if (timeFilterCombo.getValue() == '') {
					var dt = new Date();
					var dtstring = dt.getFullYear()
						+ '-' + pad(dt.getMonth()+1, 2)
						+ '-' + pad(dt.getDate(), 2);
					cql+= " AND time_filter<='" + dtstring + "'";
				} else {
					cql+= " AND " + timeFilterCombo.getValue();
				}

				tree.getSelectionModel().getSelectedNode().layer.mergeNewParams({
					cql_filter: cql
				});
			}

			var propertiesTabs = new Ext.TabPanel({
				activeTab: 0,
				plain: true,
				defaults: {
					autoScroll: true,
					padding: 5
				},
				items: [{
						title: 'Properties',
						layout: 'form',
						defaults: {
							width: 250
						},
						defaultType: 'textfield',

						items: [new GeoExt.LayerOpacitySlider({
								fieldLabel: 'Transparency',
								layer: tree.getSelectionModel().getSelectedNode().layer,
								aggressive: true,
								width: 150,
								isFormField: true,
								inverse: true,
								plugins: new GeoExt.LayerOpacitySliderTip({
									template: "<div>Transparency: {opacity}%</div>"
								})
							}),
							typeFilterCombo,
							//				{
							//			xtype: "combo",
							//			fieldLabel: 'Type Filter',
							//			readOnly: false,
							//			store: new Ext.data.SimpleStore({
							//				fields: ['name', 'value'],
							//				data: tree.getSelectionModel().getSelectedNode().attributes.type_filter
							//			}),
							//			displayField: 'name',
							//			valueField: 'value',
							//			//value: tree.getSelectionModel().getSelectedNode().layer.params.CQL_FILTER,
							//			mode: "local",
							//			triggerAction: "all",
							//			typeAhead: true,
							//			listeners: {
							//				select: typeFilterComboSelected
							//			}
							//		},
							timeFilterCombo
							//				{
							//			xtype: "combo",
							//			fieldLabel: 'Time Filter',
							//			readOnly: false,
							//			store: new Ext.data.SimpleStore({
							//				fields: ['name', 'value'],
							//				data: tree.getSelectionModel().getSelectedNode().attributes.time_filter
							//			}),
							//			displayField: 'name',
							//			valueField: 'value',
							//			//value: tree.getSelectionModel().getSelectedNode().layer.params.CQL_FILTER,
							//			mode: "local",
							//			triggerAction: "all",
							//			typeAhead: true,
							//			listeners: {
							//				select: timeFilterComboSelected
							//			}
							//		}
							/*, {
                    fieldLabel: 'Originator',
                    readOnly: true,
                    name: 'originator',
                    value: tree.getSelectionModel().getSelectedNode().attributes.originator
                }, {
                    fieldLabel: 'Updated',
                    readOnly: true,
                    name: 'updated',
                    value: tree.getSelectionModel().getSelectedNode().attributes.updated
                }, {
                    fieldLabel: 'Metadata',
                    xtype: 'box',
                    html: '<a href="'+tree.getSelectionModel().getSelectedNode().attributes.metadata+'" target="_blank">'+tree.getSelectionModel().getSelectedNode().attributes.metadata+'</a>'
                }, {
                    fieldLabel: 'Abstract',
                    xtype: 'textarea',
                    readOnly: true,
                    grow: true,
                    growMax: 500,
                    width: 300,
                    value: tree.getSelectionModel().getSelectedNode().attributes.abstract
                }*/]
					}/*, {
                title: 'Abstract',
                html: "Metadata here..."
            }*/]
			});
			var propertiesWindow = new Ext.Window({
				title: tree.getSelectionModel().getSelectedNode().attributes.layer.name,
				id: 'winLogin',
				layout: 'fit',
				width: 400,
				height: 250,
				//y: 340,
				modal: true,
				resizable: true,
				closable: true,
				items: [propertiesTabs]
			});
			propertiesWindow.show();
		};
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
				mapping: "properties.displaypart1"
			}, {
				name: "city",
				mapping: "properties.displaypart2"
			}, {
				name: "state",
				mapping: "properties.displaypart3"
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
	function setCustomMap(lat, lon, zoom, pin, layer, basemap) {
	    // Switch to Map Tab
	    Ext.getCmp('centerPanel').setActiveTab(0);
	    // Set Viewport
	    if (lat && lon && zoom) {
	        map.setCenter(new OpenLayers.LonLat(lon, lat).transform(map.displayProjection, map.projection), zoom);
	    };
	    // Drop a Pin
	    if (pin === true) {
			searchMarker.clearMarkers();
			searchMarker.addMarker(new OpenLayers.Marker(map.getCenter(),searchIcon));
	    };
	    // Activate Layer
	    if (layer) {
	        var wmsLayers = map.getLayersByClass("OpenLayers.Layer.WMS");
	        for (var i = 0; i < wmsLayers.length; i++) {
	            if (wmsLayers[i].params.LAYERS === layer) {
	                wmsLayers[i].setVisibility(true);
	            };
	        };
	    };
	    // Set Basemap
	    if (basemap) {
	        if (basemap === "streets") {
	            map.setBaseLayer(map.getLayersByName("Google Streets")[0]);
	        } else if (basemap === "imagery") {
	            map.setBaseLayer(map.getLayersByName("Google Imagery With Labels")[0]);
	        };
	    };
	}
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
<? if (isset($show_download))
{ ?>
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
		Ext.BLANK_IMAGE_URL = "/resources/ext-3.3.1/resources/images/gray/s.gif";
		keyboardnav = new OpenLayers.Control.KeyboardDefaults();
		var options = {
			projection: new OpenLayers.Projection("EPSG:900913"),
			displayProjection: new OpenLayers.Projection("EPSG:4326"),
			controls: [/*new OpenLayers.Control.LayerSwitcher(), new OpenLayers.Control.PanPanel(), new OpenLayers.Control.ZoomPanel(),*/new OpenLayers.Control.Navigation(), new OpenLayers.Control.PanZoomBar(), new OpenLayers.Control.ScaleLine, new OpenLayers.Control.LoadingPanel(), new OpenLayers.Control.Attribution, keyboardnav],
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
		// #275 begin
		//        ESRI_USA_Median_Household_Income = new OpenLayers.Layer.XYZ("Median Household Income", "http://server.arcgisonline.com/ArcGIS/rest/services/Demographics/USA_Median_Household_Income/MapServer/tile/${z}/${y}/${x}", {
		//        //attribution: "<img src='img/mhi_counties_legend.png'></img>",
		//        sphericalMercator: true,
		//        numZoomLevels: 18,
		//        isBaseLayer: false,
		//        visibility: false,
		//        opacity: 0.75
		//    });
		//    #275 end
		//Override OL getFullRequestString to allow layer with a different CRS code
		//OpenLayers.Layer.WMS.prototype.getFullRequestString =
		//function (newParams, altUrl) {
		//    var projectionCode = this.projection.toString();
		//    this.params.SRS = (projectionCode == "none") ? null : "EPSG:102113";
		//    return OpenLayers.Layer.Grid.prototype.getFullRequestString.apply(
		//    this, arguments);
		//};
		// #275 begin
		//    FWS_Wetlands = new OpenLayers.Layer.WMS("FWS Wetlands", "http://137.227.242.85/ArcGIS/services/FWS_Wetlands_WMS/mapserver/wmsserver", {
		//        layers: '17',
		//        transparent: true,
		//        format: "image/png"
		//    }, {
		//        isBaseLayer: false,
		//        visibility: false,
		//        displayInLayerSwitcher: true
		//    });
		//    FWS_Wetlands.getFullRequestString = function (newParams, altUrl) {
		//        this.params.SRS = "EPSG:102113";
		//        return OpenLayers.Layer.Grid.prototype.getFullRequestString.apply(
		//        this, arguments);
		//    };
		//    us_geothermal = new OpenLayers.Layer.WMS("Geothermal Resource Potential", "http://mapsdb.nrel.gov/geoserver/wms", {
		//    	layers: 're_atlas:us_geothermal',
		//        transparent: true,
		//        format: "image/png"
		//    }, {
		//        isBaseLayer: false,
		//        visibility: false,
		//        displayInLayerSwitcher: true,
		//        opacity: 0.75
		//    });
		//    us_solarcsp = new OpenLayers.Layer.WMS("Concentrating Solar Power Radiation", "http://mapsdb.nrel.gov/geoserver/wms", {
		//    	layers: 're_atlas:us_solarcsp',
		//        transparent: true,
		//        format: "image/png"
		//    }, {
		//        isBaseLayer: false,
		//        visibility: false,
		//        displayInLayerSwitcher: true,
		//        opacity: 0.75
		//    });
		//    us_tilt_total = new OpenLayers.Layer.WMS("PV Solar Radiation - Tilt", "http://mapsdb.nrel.gov/geoserver/wms", {
		//    	layers: 're_atlas:us_tilt_total',
		//        transparent: true,
		//        format: "image/png"
		//    }, {
		//        isBaseLayer: false,
		//        visibility: false,
		//        displayInLayerSwitcher: true,
		//        opacity: 0.75
		//    });
		//    uswpc = new OpenLayers.Layer.WMS("Wind Resource Intensity", "http://mapsdb.nrel.gov/geoserver/wms", {
		//    	layers: 're_atlas:uswpc',
		//        transparent: true,
		//        format: "image/png"
		//    }, {
		//        isBaseLayer: false,
		//        visibility: false,
		//        displayInLayerSwitcher: true,
		//        opacity: 0.75
		//    });
		//    SSURGO_Soils = new OpenLayers.Layer.XYZ("SSURGO Soils", "http://server.arcgisonline.com/ArcGIS/rest/services/Specialty/Soil_Survey_Map/MapServer/tile/${z}/${y}/${x}", {
		//        sphericalMercator: true,
		//        numZoomLevels: 18,
		//        isBaseLayer: false,
		//        visibility: false,
		//        opacity: 0.75
		//    });
		//    streamFlow = new OpenLayers.Layer.WMS("USGS Stream Flow", "http://<?= GEOSERVER_URL ?>/geoserver/wms", {
		//		layers: 'pgterradex:realstx',
		//		transparent: true,
		//		format: "image/png"
		//	}, {
		//		layerid: 'realstx',
		//		isBaseLayer: false,
		//		visibility: false,
		//		displayInLayerSwitcher: true
		//	});
		// #275 end
<?php echo (isset($layers_definition) ? $layers_definition : '') ?>
		// #275 begin
		//    federallands = new OpenLayers.Layer.WMS("Federal Lands", "http://<?= GEOSERVER_URL ?>/geoserver/wms", {
		//        layers: 'pgterradex:Federal_Lands',
		//        transparent: true,
		//        format: "image/png"
		//    }, {
		//        layerid: 'Federal_Lands',
		//        isBaseLayer: false,
		//        visibility: false,
		//        displayInLayerSwitcher: true
		//    });
		// #275 end
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
			// #275 begin
			//        SSURGO_Soils,
			//        FWS_Wetlands,
			//        streamFlow,
			//        uswpc,
			//        us_tilt_total,
			//        us_solarcsp,
			//        us_geothermal,
			//        federallands,
			//        ESRI_USA_Median_Household_Income,
			// #275 end
<?php echo (isset($layers_list) ? $layers_list : ''); ?>
			searchMarker,
			clickMarker,
			layerRuler
		]);
		var nodeCleanup = new Array();
		var nodeActivity = new Array();
		var nodeCustom = new Array();
		var nodeLandwatch = new Array();
		var nodeDigClean = new Array();
		var nodeProperty = new Array();
		//    #275 begin
		//    nodeProperty.push({
		//        nodeType: "gx_layer",
		//        layer: federallands,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    })
		// #275 end
		var nodeEnvironmental = new Array();
		//    #275 begin
		//    var nodeEnvironmental = [{
		//        nodeType: "gx_layer",
		//        layer: streamFlow,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    }, {
		//        nodeType: "gx_layer",
		//        layer: FWS_Wetlands,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    }, {
		//        nodeType: "gx_layer",
		//        layer: SSURGO_Soils,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    }];
		// #275 end

<?php echo (isset($layer_nodes) ? $layer_nodes : ''); ?>

		var treeConfig = new Array();
		if (nodeCleanup.length != 0) {
			treeConfig.push({
				text: "<b>&nbsp;Cleanup</b>",
				expanded: true,
				singleClickExpand: true,
				children: nodeCleanup
			});
		}
		if (nodeCustom.length != 0) {
			treeConfig.push({
				text: "<b>&nbsp;Custom</b>",
				expanded: true,
				singleClickExpand: true,
				children: nodeCustom
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
		// #275 begin
		//    treeConfig.push({
		//        text: "<b>&nbsp;Populations</b>",
		//        expanded: true,
		//        singleClickExpand: true,
		//        children: [{
		//            nodeType: "gx_layer",
		//            layer: ESRI_USA_Median_Household_Income,
		//            listeners: {
		//                click: function () {
		//                    opacitySlider.setLayer(this.layer);
		//                }
		//            }
		//        }]
		//    });
		// #275 end
		var nodeRenewable = new Array();
		//    #275 begin
		//    nodeRenewable.push({
		//        nodeType: "gx_layer",
		//        layer: us_geothermal,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    })
		//    nodeRenewable.push({
		//        nodeType: "gx_layer",
		//        layer: us_solarcsp,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    })
		//    nodeRenewable.push({
		//        nodeType: "gx_layer",
		//        layer: us_tilt_total,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    })
		//    nodeRenewable.push({
		//        nodeType: "gx_layer",
		//        layer: uswpc,
		//        listeners: {
		//            click: function () {
		//                opacitySlider.setLayer(this.layer);
		//            }
		//        }
		//    })
		// #275 end
		if(nodeProperty.length > 0){
			treeConfig.push({
				text: "<b>&nbsp;Property</b>",
				expanded: true,
				singleClickExpand: true,
				children: nodeProperty
			});
		}
		if(nodeRenewable.length > 0){
			treeConfig.push({
				text: "<b>&nbsp;Renewable Energy Potential</b>",
				expanded: true,
				singleClickExpand: true,
				children: nodeRenewable
			})
		}
		if(nodeEnvironmental.length > 0){
			treeConfig.push({
				text: "<b>&nbsp;Environmental Background</b>",
				expanded: true,
				singleClickExpand: true,
				children: nodeEnvironmental
			});
		}
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

		function showFeatureInfo() {
			var i, frames;
			westCenterPanel.setActiveTab(1);
			//Ext.getCmp("eastPanel").expand();
			frames = document.getElementsByTagName("iframe");
			for (i = 0; i < frames.length; ++i) {
				frames[i].width = "100%";
			}

		}

		// Build our custom WMSGetFeatureInfo Control
<?php echo (isset($feature_controls) ? $feature_controls : ''); ?>
		//    #275 begin
		//    function federallandsHTML(response) {
		//        if (response.responseText.length > 687) {
		//            document.getElementById('federallands_Info').innerHTML = response.responseText;
		//            showFeatureInfo();
		//        }
		//        if (response.responseText.length <= 687) {
		//           document.getElementById('federallands_Info').innerHTML = "";
		//        }
		//    };
		//    function parcelsHTML(response) {
		//            document.getElementById('parcels_Info').innerHTML = response.responseText;
		//            showFeatureInfo();
		//    };
		//    function us_geothermalHTML(response) {
		//        if (response.responseText.length > 687) {
		//            document.getElementById('us_geothermal_Info').innerHTML = response.responseText;
		//            showFeatureInfo();
		//        }
		//        if (response.responseText.length <= 687) {
		//            document.getElementById('us_geothermal_Info').innerHTML = "";
		//        }
		//    };
		//    function us_solarcspHTML(response) {
		//	if (response.responseText.length > 687) {
		//		document.getElementById('us_solarcsp_Info').innerHTML = response.responseText;
		//		showFeatureInfo();
		//	}
		//	if (response.responseText.length <= 687) {
		//		document.getElementById('us_solarcsp_Info').innerHTML = "";
		//	}
		//    };
		//    function us_tilt_totalHTML(response) {
		//            if (response.responseText.length > 687) {
		//                    document.getElementById('us_tilt_total_Info').innerHTML = response.responseText;
		//                    showFeatureInfo();
		//            }
		//            if (response.responseText.length <= 687) {
		//                    document.getElementById('us_tilt_total_Info').innerHTML = "";
		//            }
		//    };
		//    function uswpcHTML(response) {
		//            if (response.responseText.length > 687) {
		//                    document.getElementById('uswpc_Info').innerHTML = response.responseText;
		//                    showFeatureInfo();
		//            }
		//            if (response.responseText.length <= 687) {
		//                    document.getElementById('uswpc_Info').innerHTML = "";
		//            }
		//    };
		//    function streamFlowHTML(response) {
		//        if (response.responseText.length > 687) {
		//            //alert(response.responseText.length);
		//            document.getElementById('streamFlow_Info').innerHTML = response.responseText;
		//            showFeatureInfo();
		//        }
		//        if (response.responseText.length <= 687) {
		//            document.getElementById('streamFlow_Info').innerHTML = "";
		//        }
		//    };
		// #275 end
		selectCtrl = new OpenLayers.Control.Click({
			trigger: function (event) {
				var maploc = map.getLonLatFromViewPortPx(event.xy);
				clickMarker.clearMarkers();
				clickMarker.addMarker(new OpenLayers.Marker(maploc,clickIcon));
				document.getElementById('responseText').innerHTML = "";
				//            #275 begin
				//            document.getElementById('federallands_Info').innerHTML = "";
				//            document.getElementById('us_geothermal_Info').innerHTML = "";
				//            document.getElementById('us_solarcsp_Info').innerHTML = "";
				//            document.getElementById('us_tilt_total_Info').innerHTML = "";
				//            document.getElementById('uswpc_Info').innerHTML = "";
				//            document.getElementById('streamFlow_Info').innerHTML = "";
				//            if (us_geothermal.getVisibility() == true) {
				//                document.getElementById('us_geothermal_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
				//            }
				//            if (us_solarcsp.getVisibility() == true) {
				//                document.getElementById('us_solarcsp_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
				//            }
				//            if (us_tilt_total.getVisibility() == true) {
				//                document.getElementById('us_tilt_total_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
				//            }
				//            if (uswpc.getVisibility() == true) {
				//                document.getElementById('uswpc_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
				//            }
				//            if (streamFlow.getVisibility() == true) {
				//                document.getElementById('streamFlow_Info').innerHTML = "<img src='img/loading.gif'></img>&nbsp;Loading...";
				//            }
				//            #275 end
				// Set default values for innerHTML for info windows
<?php echo (isset($html_defaults) ? $html_defaults : ''); ?>

				// Generate display code for features
<?php echo (isset($feature_displays) ? $feature_displays : ''); ?>
			}
		});
		map.addControl(selectCtrl);
		selectCtrl.activate();

		// Map Navigation control in the 'navigation' toggleGroup
		var panZoom = new Ext.Button({
			scale: "medium",
			tooltip: "Pan around the map. Hold control and drag a box to rubber-band zoom.",
			iconCls: "icon-pan",
			toggleGroup: "navigation",
			pressed: true,
			allowDepress: false,
			handler: function () {
				Ext.getCmp('map').body.applyStyles('cursor:default');
				var element = document.getElementById('output');
				element.innerHTML = "";
				layerRuler.removeFeatures(layerRuler.features);
			}
		});
		/*var panZoom = new GeoExt.Action({
			scale: "medium",
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
		});*/
		// Identify control in the 'navigation' toggleGroup
		var identify = new Ext.Button({
			scale: "medium",
			tooltip: "Identify Features",
			iconCls: "icon-identify",
			toggleGroup: "navigation",
			pressed: false,
			allowDepress: false,
			handler: function () {
				Ext.getCmp('map').body.applyStyles('cursor:help');
				var element = document.getElementById('output');
				element.innerHTML = "";
				layerRuler.removeFeatures(layerRuler.features);
			}
		});
		/*var identify = new GeoExt.Action({
			scale: "medium",
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
		});*/
		// Clear Selection control in the 'navigation' toggleGroup
		var clearSelect = new Ext.Button({
			scale: "medium",
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
			scale: "medium",
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
			scale: "medium",
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
			scale: "medium",
			tooltip: "Zoom to Previous Extent",
			iconCls: "icon-zoomprevious",
			control: navHistoryCtrl.previous,
			disabled: true
		});
		var zoomNext = new GeoExt.Action({
			scale: "medium",
			tooltip: "Zoom to Next Extent",
			iconCls: "icon-zoomnext",
			control: navHistoryCtrl.next,
			disabled: true
		});
		// Zoom Extent control
		var zoomExtentBtn = new Ext.Button({
			scale: "medium",
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
			scale: "medium",
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
			scale: "medium",
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
			width: 200,
			//pageSize: 10,
			emptyText: 'Search Active Layers (min 4 char)',
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
					cleanupStore.baseParams = {};
					for (var i = 0; i < featureLayers.length; i++) {
						eval("cleanupStore.baseParams." + escape(featureLayers[i].options.layerid) + "=" + featureLayers[i].getVisibility());
					}
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
			width: 100,
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
					//Ext.getCmp("northPanel").collapse();
					Ext.getCmp("westPanel").collapse();
					//Ext.getCmp("southPanel").collapse();
				}
				if (Ext.getCmp("fullScreenButton").pressed == false) {
					//Ext.getCmp("northPanel").expand();
					Ext.getCmp("westPanel").expand();
					//showFeatureInfo();
				}
			}
		});
		<?php if (isset($user_id))
		{ ?>
					var loginoutButton = new Ext.Button({
						text: "<b>Logout<?php echo ' ' . $first_name ?></b>",
						//cls: 'login',
						iconCls: "icon-logout",
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
		<?php
		}
		else
		{
			?>
					var loginoutButton = new Ext.Button({
						text: "Login",
						//cls: 'login',
						iconCls: "icon-login",
						handler: function () {
							win.show();
						}
					})
		<?php } ?>
		<?php if (isset($user_id)) { ?>
		var toolBar = [panZoom, identify, zoomIn, zoomOut, zoomPrevious, zoomNext, zoomExtentBtn, "-", clearSelect, "-", measureLength, measureArea, '<div id="output" style="color: red; font-weight: bold; font-size:12px; text-align: right;">&nbsp;&nbsp;&nbsp;&nbsp;</div>', '->', bookmarks, '-', addressSearch, '-', cleanupsiteSearch, '-', loginoutButton];
		<?php } else { ?>
		var toolBar = [panZoom, identify, zoomIn, zoomOut, zoomPrevious, zoomNext, zoomExtentBtn, "-", clearSelect, "-", measureLength, measureArea, '<div id="output" style="color: red; font-weight: bold; font-size:12px; text-align: right;">&nbsp;&nbsp;&nbsp;&nbsp;</div>', '->', loginoutButton];
		<?php } ?>
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

		var urlvar = {};
	    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	        urlvar[key] = value;
	    });

	    if (urlvar['target'] === "new") {
	        var lat = urlvar['lat'];
	        var lon = urlvar['lon'];
	        var zoom = urlvar['zoom'];
	        // Activate Layer
	        if (urlvar['layer']) {
	            var wmsLayers = map.getLayersByClass("OpenLayers.Layer.WMS");
	            for (var i = 0; i < wmsLayers.length; i++) {
	                if (wmsLayers[i].params.LAYERS === urlvar['layer']) {
	                    wmsLayers[i].setVisibility(true);
	                };
	            };
	        };
	        // Set Basemap
	        if (urlvar['basemap'] === 'imagery') {
	        	var basemap = ghyb;
	        } else {
	        	var basemap = gmap;
	        };
	        // Drop a Pin
	        if (urlvar['pin'] === 'true') {
	        	searchMarker.clearMarkers();
	        	var markerLocation = new OpenLayers.LonLat(lon, lat).transform(map.displayProjection, map.projection);
				searchMarker.addMarker(new OpenLayers.Marker(markerLocation,searchIcon));
	        };
	    } else {
	    	var lat = 38.354741573919696;
	    	var lon = -96.45131464812954;
	    	var zoom = 4;
	    	var basemap = gmap;
	    };

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
			center: new OpenLayers.LonLat(lon, lat).transform(map.displayProjection, map.projection),
			zoom: zoom,
			//items: [zoomSlider],
			stateId: "map",
			prettyStateKeys: false
		});
		var propertiesButton = new Ext.Button({
			id: "layerpropertiesButton",
			text: "Layer Properties",
			iconCls: "icon-layerproperties",
			handler: function() {
				layerProperties();
			}
		});
		tree = new Ext.tree.TreePanel({
			/*root: new GeoExt.tree.LayerContainer({
            text: 'Map Layers',
            layerStore: mapPanel.layers,
            leaf: false,
            expanded: true
        }),*/
			border: false,
			rootVisible: false,
			enableDD: false,
			loader: new Ext.tree.TreeLoader({
				applyLoader: false
			}),
			root: {
				nodeType: "async",
				children: treeConfig
			},
			listeners: {
				contextmenu: function(node, e) {
					if (node && node.layer) {
						node.select();
						var c = node.getOwnerTree().contextMenu;
						c.contextNode = node;
						c.showAt(e.getXY())
					}
				},
				click: function (node, e) {
					opacitySlider.setLayer(node.layer);
				},
				scope: this
			},
			contextMenu: new Ext.menu.Menu({
				items: [/*{
                text: "Zoom to Layer Extent",
                iconCls: "icon-layerextent",
                handler: function() {
                    var node = tree.getSelectionModel().getSelectedNode();
                    if (node && node.layer) {
                        mapPanel.map.zoomToExtent(new OpenLayers.Bounds(node.attributes.extent[0], node.attributes.extent[1], node.attributes.extent[2], node.attributes.extent[3]).transform(map.displayProjection, map.projection));
                    }
                }
            }, */{
						text: "Layer Properties",
						iconCls: "icon-layerproperties",
						handler: function() {
							layerProperties();
						}
					}]
			})
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
		/*if (typeof (loginoutButton) != "undefined") {
			headerPanel.add(loginoutButton);
		}*/
		var northPanel = new Ext.Panel({
			id: 'northPanel',
			region: 'north',
			height: 50,
			split: true,
			collapseMode: 'mini',
			items: [headerPanel]
		});
		/*var southPanel = new Ext.Panel({
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
   });*/
		var layersPanel = new Ext.Panel({
			id: "layersPanel",
			title: "Layers",
			//region: 'center',
			height: 250,
			autoScroll: true,
			border: false,
			iconCls: "icon-maplayers",
			items: [tree],
			//tbar: ["->",propertiesButton],
			bbar: ["Transparency:&nbsp;&nbsp;", opacitySlider, "->", propertiesButton]
		});
		var infoPanel = new Ext.Panel({
			id: "infoPanel",
			title: "Feature Info",
			autoScroll: true,
			iconCls: "icon-featureinfo",
			border: false,
			split: true,
			//height: 250,
			layout: "fit",
			collapseMode: "mini",
			//collapsed: true,
			//margins: '0 0 0 0',
			html: "<div id='responseText'><p>Click on a map feature for information.</p></div></div><?php echo (isset($layers_window_html) ? $layers_window_html : ''); ?><div id='cleanupSites_Info'></div><div id='csms_facility_polygon_Info'></div><div id='icPolygons_Info'></div><div id='groundwaterplumes_Info'></div><div id='landwatchevent_Info'></div><div id='landwatch_site_Info'></div><div id='landwatch_site_aps_Info'></div><div id='landwatch_alert_aps_Info'></div><div id='landwatch_site_basf_Info'></div><div id='landwatch_alert_basf_Info'></div><div id='landwatch_site_bp_Info'></div><div id='landwatch_alert_bp_Info'></div><div id='landwatch_site_dtsc_Info'></div><div id='landwatch_alert_dtsc_Info'></div><div id='landwatch_site_ge_Info'></div><div id='landwatch_alert_ge_Info'></div><div id='landwatch_site_nysdec_Info'></div><div id='landwatch_alert_nysdec_Info'></div><div id='landwatch_site_pge_Info'></div><div id='landwatch_alert_pge_Info'></div><div id='landwatch_site_urs_Info'></div><div id='landwatch_alert_urs_Info'></div><div id='landwatch_site_usepa_Info'></div><div id='landwatch_alert_usepa_Info'></div><div id='landwatch_site_wdig_Info'></div><div id='landwatch_alert_wdig_Info'></div><div id='daycare_Info'></div><div id='federallands_Info'></div><div id='parcels_Info'></div><div id='us_geothermal_Info'></div><div id='us_solarcsp_Info'></div><div id='us_tilt_total_Info'></div><div id='uswpc_Info'></div><div id='streamFlow_Info'></div><div id='dc_site_de_Info'></div><div id='dc_excavation_with_de_Info'></div><div id='dc_excavation_no_de_Info'></div><div id='dc_site_wv_Info'></div><div id='dc_excavation_with_wv_Info'></div><div id='dc_excavation_no_wv_Info'></div><div id='dc_site_dtsc_Info'></div><div id='dc_excavation_with_dtsc_Info'></div><div id='dc_excavation_no_dtsc_Info'></div><div id='dc_site_id_Info'></div>"
		});
		westNorthPanel = new Ext.Panel({
			region: 'north',
			border: false,
			autoScroll: false,
			height: 50,
			items: [
				new Ext.Container({
					region: 'north',
					height: 50,
					autoEl: [{
							tag: 'div',
							cls: 'header',
							html: '<img style="width: 348px; height: 40px; position:absolute; top:5px;" alt="Cleanup Deck V2.0" src="img/cleanupdeck.png" align="middle">'
						}]
				})
			]
			//html: '<img style="width: 348px; height: 40px; position:absolute; top:5px;" alt="Cleanup Deck V2.0" src="img/cleanupdeck.png" align="middle">'
		});
		westCenterPanel = new Ext.TabPanel({
			region: 'center',
			border: false,
			autoScroll: false,
			deferredRender: false,
			activeTab: 0,
			items: [layersPanel, infoPanel]
		});
		var legendPanel = new GeoExt.LegendPanel({
			id: "legendPanel",
			title: "Legend",
			iconCls: "icon-maplegend",
			border: false,
			region: 'south',
			height: 200,
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
			width: 350,
			split: true,
			collapseMode: "mini",
			items: [westNorthPanel, westCenterPanel, legendPanel]
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
		/*var eastPanel = new Ext.Panel({
			id: "eastPanel",
			border: true,
			layout: "fit",
			region: "east",
			width: 350,
			split: true,
			collapseMode: "mini",
			collapsed: true,
			items: [infoPanel]
		});*/
<? if (isset($portfolio_url)) {?>
		var portfolioPanel = new Ext.Panel({
			id: "tabPortfolio",
			title: "Portfolio",
			closable: false,
			autoScroll: true,
			border: false,
			html: portfolioHTML
		});
<? } ?>
<? if (isset($show_download))
{ ?>
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
<?php if (isset($_801))
{ ?>
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
<?php if (isset($_802))
{ ?>
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
			border: false,
			deferredRender: false,
			activeTab: 0,
			items: [mapPanel, <?= (isset($_801) ? 'dcManagementDEPanel,' : '') ?> <?= (isset($_802) ? 'dcManagementWVPanel,' : '') ?> <? if (isset($show_download))
{ ?>dataPanel,<? } ?> aboutPanel<?= (isset($portfolio_url)? ',portfolioPanel': '') ?>]
			});
			var viewport = new Ext.Viewport({
				layout: 'border',
				items: [/*northPanel,*/ westPanel, centerPanel/*, southPanel, eastPanel*/]
			});
			map.setBaseLayer(basemap);
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
			// #275 begin
			//    ESRI_USA_Median_Household_Income.events.register('visibilitychanged', this, function (feature) {
			//        if (ESRI_USA_Median_Household_Income.getVisibility() == true) {
			//            addMHILegend();
			//        }
			//        if (ESRI_USA_Median_Household_Income.getVisibility() == false) {
			//            mhi_state.hide();
			//            mhi_county.hide();
			//            mhi_tract.hide();
			//            mhi_blockgroups.hide();
			//            Ext.getCmp('legendPanel').doLayout();
			//        }
			//    });
			// #275 end
//			esri_usa_median_household_income.events.register('visibilitychanged', this, function (feature) {
//				if (esri_usa_median_household_income.getVisibility() == true) {
//					addMHILegend();
//				}
//				if (esri_usa_median_household_income.getVisibility() == false) {
//					mhi_state.hide();
//					mhi_county.hide();
//					mhi_tract.hide();
//					mhi_blockgroups.hide();
//					Ext.getCmp('legendPanel').doLayout();
//				}
//			});


			// Remove as the only layer affected by this is esri and we are no longer
			// using esri
//			map.events.register("zoomend", this, function () {
//				var scale = map.getScale();
//				if (esri_usa_median_household_income.getVisibility() == true) {
//					addMHILegend();
//				}
//				if (esri_usa_median_household_income.getVisibility() == false) {
//					mhi_state.hide();
//					mhi_county.hide();
//					mhi_tract.hide();
//					mhi_blockgroups.hide();
//					Ext.getCmp('legendPanel').doLayout();;
//				}
//			});

	<?php if (isset($_501))
{ ?>
//				parcels.events.register('visibilitychanged', this, function (feature) {
//					if (parcels.getVisibility() == true) {
//						parcelsLegend.show();
//						Ext.getCmp('legendPanel').doLayout();
//					}
//					if (parcels.getVisibility() == false) {
//						parcelsLegend.hide();
//						Ext.getCmp('legendPanel').doLayout();
//					}
//				});
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
			Ext.getCmp('map').show();
			//    tabDetails.show(); // Do automatically show the detailed tab
		}
</script>
