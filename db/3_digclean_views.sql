# SQL for Postgres DB used by Geoserver
# These scripts standardizes the views to include sitename, sitecity, and sitestateshort
# for search display on Cleanupdeck

Drop view if exists dc_site_dtsc_view;
Create view dc_site_dtsc_view as SELECT dc_site.pg_siteid, dc_site.tdx_datasetid, dc_site.sourceid, dc_site.sourceid_3, dc_site.site_type, dc_site.site_name AS sitename, dc_site.site_street, dc_site.site_city AS sitecity, dc_site.site_county, dc_site.site_state_short AS sitestateshort, dc_site.geom, dc_site.searchfield, dc_site.gtype
   FROM dc_site
  WHERE dc_site.tdx_datasetid = 1006 AND NOT dc_site.geom IS NULL;

Drop view if exists dc_site_de_view;
Create view dc_site_de_view as SELECT dc_site.pg_siteid, dc_site.tdx_datasetid, dc_site.sourceid, dc_site.sourceid_3, dc_site.site_type, dc_site.site_name AS sitename, dc_site.site_street, dc_site.site_city AS sitecity, dc_site.site_county, dc_site.site_state_short AS sitestateshort, dc_site.geom, dc_site.searchfield, dc_site.gtype
   FROM dc_site
  WHERE dc_site.tdx_datasetid = 1003 AND NOT dc_site.geom IS NULL;

Drop view if exists dc_site_wv_view;
Create view dc_site_wv_view as SELECT dc_site.pg_siteid, dc_site.tdx_datasetid, dc_site.sourceid, dc_site.sourceid_3, dc_site.site_type, dc_site.site_name AS sitename, dc_site.site_street, dc_site.site_city AS sitecity, dc_site.site_county, dc_site.site_state_short AS sitestateshort, dc_site.geom, dc_site.searchfield, dc_site.gtype
   FROM dc_site
  WHERE dc_site.tdx_datasetid = 1005 AND NOT dc_site.geom IS NULL;

Drop view if exists dc_site_id_view;
Create view dc_site_id_view as SELECT dc_site.pg_siteid, dc_site.tdx_datasetid, dc_site.sourceid, dc_site.sourceid_3, dc_site.site_type, dc_site.site_name AS sitename, dc_site.site_street, dc_site.site_city AS sitecity, dc_site.site_county, dc_site.site_state_short AS sitestateshort, dc_site.geom, dc_site.searchfield, dc_site.gtype
   FROM dc_site
  WHERE dc_site.tdx_datasetid = 1004 AND NOT dc_site.geom IS NULL;

Drop view if exists dc_site_de_view;
Create view dc_site_de_view as SELECT dc_site.pg_siteid, dc_site.tdx_datasetid, dc_site.sourceid, dc_site.sourceid_3, dc_site.site_type, dc_site.site_name AS sitename, dc_site.site_street, dc_site.site_city AS sitecity, dc_site.site_county, dc_site.site_state_short AS sitestateshort, dc_site.geom, dc_site.searchfield, dc_site.gtype
   FROM dc_site
  WHERE dc_site.tdx_datasetid = 1003 AND NOT dc_site.geom IS NULL;

Drop view if exists dc_excavation_with_wv_view;
Create view dc_excavation_with_wv_view as SELECT DISTINCT dc_excavation.pg_eventid, dc_excavation.ticket_number, dc_excavation.ticket_type, "substring"(dc_excavation.ticket_receivedate::text, 0, 11) AS ticket_receivedate, dc_excavation.work_street AS sitename, dc_excavation.work_city AS sitecity, dc_excavation.work_state_short AS sitestateshort, dc_excavation.geom, dc_excavation.gtype, y(st_centroid(dc_excavation.geom))::character varying(12) AS centerlat, x(st_centroid(dc_excavation.geom))::character varying(12) AS centerlon, tdx_dataset_info.dataset_logo_url, tdx_dataset_info.dataset_name_short, tdx_dataset_info.dataset_name_full, dc_excavation.searchfield
   FROM dc_excavation dc_excavation
   LEFT JOIN tdx_action tdx_action ON dc_excavation.pg_eventid = tdx_action.pg_eventid, tdx_dataset_info tdx_dataset_info
  WHERE tdx_dataset_info.tdx_datasetid = dc_excavation.tdx_datasetid AND tdx_dataset_info.tdx_datasetid = 1001 AND NOT tdx_action.pg_eventid IS NULL AND NOT dc_excavation.geom IS NULL AND dc_excavation.pg_eventid > 100564;

Drop view if exists dc_excavation_with_de_view;
Create view dc_excavation_with_de_view as SELECT DISTINCT dc_excavation.pg_eventid, dc_excavation.ticket_number, dc_excavation.ticket_type, "substring"(dc_excavation.ticket_receivedate::text, 0, 11) AS ticket_receivedate, dc_excavation.work_street AS sitename, dc_excavation.work_city AS sitecity, dc_excavation.work_state_short AS sitestateshort, dc_excavation.geom, dc_excavation.gtype, y(st_centroid(dc_excavation.geom))::character varying(12) AS centerlat, x(st_centroid(dc_excavation.geom))::character varying(12) AS centerlon, tdx_dataset_info.dataset_logo_url, tdx_dataset_info.dataset_name_short, tdx_dataset_info.dataset_name_full, dc_excavation.searchfield
   FROM dc_excavation dc_excavation
   LEFT JOIN tdx_action tdx_action ON dc_excavation.pg_eventid = tdx_action.pg_eventid, tdx_dataset_info tdx_dataset_info
  WHERE tdx_dataset_info.tdx_datasetid = dc_excavation.tdx_datasetid AND tdx_dataset_info.tdx_datasetid = 1002 AND NOT tdx_action.pg_eventid IS NULL AND NOT dc_excavation.geom IS NULL AND dc_excavation.pg_eventid > 100100;

Drop view if exists dc_excavation_with_dtsc_view;
Create view dc_excavation_with_dtsc_view as SELECT DISTINCT dc_excavation.pg_eventid, dc_excavation.ticket_number, dc_excavation.ticket_type, "substring"(dc_excavation.ticket_receivedate::text, 0, 11) AS ticket_receivedate, dc_excavation.work_street AS sitename, dc_excavation.work_city AS sitecity, dc_excavation.work_state_short AS sitestateshort, dc_excavation.geom, dc_excavation.gtype, y(st_centroid(dc_excavation.geom))::character varying(12) AS centerlat, x(st_centroid(dc_excavation.geom))::character varying(12) AS centerlon, tdx_dataset_info.dataset_logo_url, tdx_dataset_info.dataset_name_short, tdx_dataset_info.dataset_name_full, dc_excavation.searchfield
   FROM dc_excavation dc_excavation
   LEFT JOIN tdx_action tdx_action ON dc_excavation.pg_eventid = tdx_action.pg_eventid, tdx_dataset_info tdx_dataset_info
  WHERE tdx_dataset_info.tdx_datasetid = dc_excavation.tdx_datasetid AND (tdx_dataset_info.tdx_datasetid = 1007 OR tdx_dataset_info.tdx_datasetid = 1008) AND NOT tdx_action.pg_eventid IS NULL AND NOT dc_excavation.geom IS NULL AND dc_excavation.pg_eventid > 100100;

Drop view if exists dc_excavation_no_de_view;
Create view dc_excavation_no_de_view as SELECT DISTINCT dc_excavation.pg_eventid, dc_excavation.ticket_number, dc_excavation.ticket_type, "substring"(dc_excavation.ticket_receivedate::text, 0, 11) AS ticket_receivedate, dc_excavation.work_street AS sitename, dc_excavation.work_city AS sitecity, dc_excavation.work_state_short AS sitestateshort, dc_excavation.geom, dc_excavation.gtype, y(st_centroid(dc_excavation.geom))::character varying(12) AS centerlat, x(st_centroid(dc_excavation.geom))::character varying(12) AS centerlon, tdx_dataset_info.dataset_logo_url, tdx_dataset_info.dataset_name_short, tdx_dataset_info.dataset_name_full, dc_excavation.searchfield
   FROM dc_excavation dc_excavation
   LEFT JOIN tdx_action tdx_action ON dc_excavation.pg_eventid = tdx_action.pg_eventid, tdx_dataset_info tdx_dataset_info
  WHERE tdx_dataset_info.tdx_datasetid = dc_excavation.tdx_datasetid AND tdx_dataset_info.tdx_datasetid = 1002 AND tdx_action.pg_eventid IS NULL AND NOT dc_excavation.geom IS NULL AND dc_excavation.pg_eventid > 100100;

Drop view if exists dc_excavation_no_wv_view;
Create view dc_excavation_no_wv_view as SELECT DISTINCT dc_excavation.pg_eventid, dc_excavation.ticket_number, dc_excavation.ticket_type, "substring"(dc_excavation.ticket_receivedate::text, 0, 11) AS ticket_receivedate, dc_excavation.work_street AS sitename, dc_excavation.work_city AS sitecity, dc_excavation.work_state_short AS sitestateshort, dc_excavation.geom, dc_excavation.gtype, y(st_centroid(dc_excavation.geom))::character varying(12) AS centerlat, x(st_centroid(dc_excavation.geom))::character varying(12) AS centerlon, tdx_dataset_info.dataset_logo_url, tdx_dataset_info.dataset_name_short, tdx_dataset_info.dataset_name_full, dc_excavation.searchfield
   FROM dc_excavation dc_excavation
   LEFT JOIN tdx_action tdx_action ON dc_excavation.pg_eventid = tdx_action.pg_eventid, tdx_dataset_info tdx_dataset_info
  WHERE tdx_dataset_info.tdx_datasetid = dc_excavation.tdx_datasetid AND tdx_dataset_info.tdx_datasetid = 1001 AND tdx_action.pg_eventid IS NULL AND NOT dc_excavation.geom IS NULL AND dc_excavation.pg_eventid > 100564;

Drop view if exists dc_excavation_no_dtsc_view;
Create view dc_excavation_no_dtsc_view as  SELECT DISTINCT dc_excavation.pg_eventid, dc_excavation.ticket_number, dc_excavation.ticket_type, "substring"(dc_excavation.ticket_receivedate::text, 0, 11) AS ticket_receivedate, dc_excavation.work_street AS sitename, dc_excavation.work_city AS sitecity, dc_excavation.work_state_short AS sitestateshort, dc_excavation.geom, dc_excavation.gtype, y(st_centroid(dc_excavation.geom))::character varying(12) AS centerlat, x(st_centroid(dc_excavation.geom))::character varying(12) AS centerlon, tdx_dataset_info.dataset_logo_url, tdx_dataset_info.dataset_name_short, tdx_dataset_info.dataset_name_full, dc_excavation.searchfield
   FROM dc_excavation dc_excavation
   LEFT JOIN tdx_action tdx_action ON dc_excavation.pg_eventid = tdx_action.pg_eventid, tdx_dataset_info tdx_dataset_info
  WHERE tdx_dataset_info.tdx_datasetid = dc_excavation.tdx_datasetid AND (tdx_dataset_info.tdx_datasetid = 1007 OR tdx_dataset_info.tdx_datasetid = 1008) AND tdx_action.pg_eventid IS NULL AND NOT dc_excavation.geom IS NULL AND dc_excavation.pg_eventid > 100100;
